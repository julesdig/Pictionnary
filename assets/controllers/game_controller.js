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

        // Get game data from hidden inputs
        this.gameId = this.gameIdTarget.value;
        this.words = this.drawingWordsTargets.map(el => el.value);
        this.drawingIds = this.drawingIdsTargets.map(el => el.value);

        // Start the timer
        this.timeLeft = 20;
        this.startTimer();
    }

    // Drawing functions
    startDrawing(event) {
        this.isDrawing = true;
        const [x, y] = this.getCoordinates(event);
        this.ctx.beginPath();
        this.ctx.moveTo(x, y);
    }

    draw(event) {
        if (!this.isDrawing) return;
        const [x, y] = this.getCoordinates(event);
        this.ctx.lineTo(x, y);
        this.ctx.stroke();
    }

    stopDrawing() {
        this.isDrawing = false;
    }

    clearCanvas() {
        this.ctx.clearRect(0, 0, this.canvasTarget.width, this.canvasTarget.height);
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
                this.submitDrawing();
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
    submitDrawing() {
        clearInterval(this.timer);

        // Get the drawing data
        const drawingData = this.canvasTarget.toDataURL();

        // Simulate AI guessing (random for now)
        const isRecognized = Math.random() > 0.5;
        const currentWord = this.words[this.currentDrawingIndex];
        const drawingId = this.drawingIds[this.currentDrawingIndex];

        // Save the result
        this.gameResults.push({
            word: currentWord,
            recognized: isRecognized,
            drawingData: drawingData
        });

        // Update the score
        let currentScore = parseInt(this.scoreTarget.textContent);
        if (isRecognized) {
            currentScore += 10;
            this.scoreTarget.textContent = currentScore;
            this.resultTarget.textContent = `Correct! The AI recognized your drawing of "${currentWord}"`;
            this.resultTarget.classList.remove('d-none', 'alert-danger');
            this.resultTarget.classList.add('alert-success');
        } else {
            this.resultTarget.textContent = `Sorry, the AI didn't recognize your drawing of "${currentWord}"`;
            this.resultTarget.classList.remove('d-none', 'alert-success');
            this.resultTarget.classList.add('alert-danger');
        }

        // Save the drawing to the server
        this.saveDrawing(drawingId, drawingData, isRecognized);

        // Move to the next drawing or end the game
        this.currentDrawingIndex++;

        if (this.currentDrawingIndex < this.words.length) {
            // Next drawing
            setTimeout(() => {
                this.clearCanvas();
                this.currentWordTarget.textContent = this.words[this.currentDrawingIndex];
                this.resultTarget.classList.add('d-none');
                this.resetTimer();
            }, 2000);
        } else {
            // Game over
            setTimeout(() => {
                this.endGame();
            }, 2000);
        }

        // Update remaining count
        this.remainingTarget.textContent = this.words.length - this.currentDrawingIndex;
    }

    saveDrawing(drawingId, drawingData, isRecognized) {
        // Send the drawing data to the server
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
