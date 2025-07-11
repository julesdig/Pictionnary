import { Controller } from '@hotwired/stimulus';

/**
 * Game controller for the Pictionary game
 */
export default class extends Controller {
    static targets = [
        'canvas', 'timer', 'score', 'remaining', 'result', 
        'currentWord', 'gameId', 'drawingWords', 'drawingIds'
    ];

    connect() {
        // Initialize canvas
        this.ctx = this.canvasTarget.getContext('2d');
        this.ctx.lineWidth = 5;
        this.ctx.lineCap = 'round';
        this.ctx.strokeStyle = '#000000';

        // Initialize game state
        this.isDrawing = false;
        this.currentDrawingIndex = 0;
        this.drawings = [];
        this.gameResults = [];
        this.submissionTimer = null;

        // Initialize drawing coordinates tracking
        this.currentStroke = [];
        this.strokes = [];
        this.timestamps = [];
        this.isRecognized = false;

        // Get game data from hidden inputs
        this.gameId = this.gameIdTarget.value;
        this.words = this.drawingWordsTargets.map(el => el.value);
        this.drawingIds = this.drawingIdsTargets.map(el => el.value);

        // Start the timer
        this.timeLeft = 20;
        this.startTimer();
    }

    startDrawing(event) {
        this.isDrawing = true;
        const [x, y] = this.getCoordinates(event);
        this.ctx.beginPath();
        this.ctx.moveTo(x, y);


        const t = 0;
        // Start a new stroke and record the first point with timestamp
        this.timestamps = Date.now();
        this.currentStroke = [[x], [y], [t]];
    }

    draw(event) {
        if (!this.isDrawing) return;
        const [x, y] = this.getCoordinates(event);
        this.ctx.lineTo(x, y);
        this.ctx.stroke();

        // Record the point with timestamp
        const t = Date.now() - this.timestamps;
        this.currentStroke[0].push(x);
        this.currentStroke[1].push(y);
        this.currentStroke[2].push(t);
    }

    stopDrawing() {
        if (this.isDrawing && this.currentStroke.length > 0) {
            // Add the completed stroke to the strokes array
            this.strokes.push(this.currentStroke);
            this.currentStroke = [];
            this.submitDrawing();
        }
        this.isDrawing = false;
    }

    clearCanvas() {
        // Clear the canvas
        this.ctx.clearRect(0, 0, this.canvasTarget.width, this.canvasTarget.height);

        // Clear any pending submission timer
        if (this.submissionTimer) {
            clearTimeout(this.submissionTimer);
            this.submissionTimer = null;
        }

        // Clear the strokes data
        this.strokes = [];
        this.currentStroke = [];

        // Hide the result area
        this.resultTarget.classList.add('d-none');
    }

    // Helper to get coordinates from mouse or touch event
    getCoordinates(event) {
        let x, y;
        const rect = this.canvasTarget.getBoundingClientRect();

        if (event.type.includes('touch')) {
            x = event.touches[0].clientX - rect.left;
            y = event.touches[0].clientY - rect.top;
        } else {
            x = event.clientX - rect.left;
            y = event.clientY - rect.top;
        }

        return [x, y];
    }

    // Timer functions
    startTimer() {
        this.timer = setInterval(() => {
            this.timeLeft--;
            this.timerTarget.textContent = this.timeLeft;

            if (this.timeLeft <= 0) {
                this.submitDrawing(true);
            }
        }, 1000);
    }
    resetTimer() {
        clearInterval(this.timer);
        this.timeLeft = 20;
        this.timerTarget.textContent = this.timeLeft;
        this.startTimer();
    }

    // Game flow functions
    submitDrawing(forceNext = false) {


        // Show processing indicator
        if (!this.resultTarget.textContent.trim()) {
            this.resultTarget.textContent = "Processing your drawing...";
            this.resultTarget.classList.remove('d-none', 'alert-success', 'alert-danger');
            this.resultTarget.classList.add('alert-info');
        }

        // Get the drawing data as vector coordinates
        const drawingData = this.strokes;
        const currentWord = this.words[this.currentDrawingIndex];
        const drawingId = this.drawingIds[this.currentDrawingIndex];

        // Call the server to get AI guesses
        fetch('/api/drawing/' + drawingId + '/guess', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                drawing: drawingData
            })
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {

                const isRecognized = data.isRecognized;
                const guess = data.guess;

                // Save the result
                this.gameResults.push({
                    word: currentWord,
                    recognized: isRecognized,
                    drawingData: drawingData,
                    guess: guess
                });

                // Update the score
                let currentScore = parseInt(this.scoreTarget.textContent);
                if (isRecognized || forceNext) {
                    clearInterval(this.timer);
                    if (isRecognized) {
                        // Calculate score based on remaining time
                        const baseScore = 5;
                        const timeBonus = this.timeLeft;
                        const totalScore = baseScore + timeBonus;

                        currentScore += totalScore;
                        this.scoreTarget.textContent = currentScore;

                        this.resultTarget.innerHTML = `<p>Correct! The AI recognized your drawing:</p>
                            <p>1. <strong>${guess}</strong> (correct!)</p>
                            <p><strong>+${totalScore}</strong> points (${baseScore} base + ${timeBonus} bonus)</p>`;
                        this.resultTarget.classList.remove('d-none', 'alert-danger');
                        this.resultTarget.classList.add('alert-success');
                    }
                    this.saveDrawing(drawingId, drawingData, isRecognized);
                    this.currentDrawingIndex++;

                    if (this.currentDrawingIndex < this.words.length) {
                        // Next drawing
                        setTimeout(() => {
                            this.clearCanvas();
                            this.currentWordTarget.textContent = this.words[this.currentDrawingIndex];
                            this.resultTarget.classList.add('d-none');
                            this.resetTimer();
                        }, 3000); // Increased timeout to give users more time to see the AI guesses
                    } else {
                        // Game over
                        setTimeout(() => {
                            this.endGame();
                        }, 3000); // Increased timeout to give users more time to see the AI guesses
                    }

                    // Update remaining count
                    this.remainingTarget.textContent = this.words.length - this.currentDrawingIndex;
                } else {
                    this.resultTarget.innerHTML = `<p>Sorry, the AI didn't recognize your drawing of "${currentWord}":</p>
                        <p>1. ${guess}</p>`;
                    this.resultTarget.classList.remove('d-none', 'alert-success');
                    this.resultTarget.classList.add('alert-danger');
                }



            } else {
                console.error('Error getting AI guesses:', data.error);
                this.resultTarget.textContent = "Error processing your drawing. Please try again.";
                this.resultTarget.classList.remove('d-none', 'alert-success', 'alert-info');
                this.resultTarget.classList.add('alert-danger');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            this.resultTarget.textContent = "Error processing your drawing. Please try again.";
            this.resultTarget.classList.remove('d-none', 'alert-success', 'alert-info');
            this.resultTarget.classList.add('alert-danger');
        });
    }

    saveDrawing(drawingId, drawingData, isRecognized) {
        // Send the drawing data to the server (now as vector coordinates)
        fetch('/api/drawing/' + drawingId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                drawing: drawingData,
                recognized: isRecognized
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Drawing saved:', data);
        })
        .catch(error => {
            console.error('Error saving drawing:', error);
        });
    }

    endGame() {
        // Save final score to the server and redirect to recap page
        this.saveGameScore();
    }

    saveGameScore() {
        fetch('/api/game/' + this.gameId, {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
            },
            body: JSON.stringify({
                score: parseInt(this.scoreTarget.textContent)
            })
        })
        .then(response => response.json())
        .then(data => {
            console.log('Game score saved:', data);
            // Redirect to the recap page
            window.location.href = '/game/' + this.gameId + '/recap';
        })
        .catch(error => {
            console.error('Error saving game score:', error);
        });
    }

}
