import { Controller } from '@hotwired/stimulus';

/**
 * Recap controller for displaying drawings from vector coordinates
 */
export default class extends Controller {
    static targets = ['canvas', 'drawingData'];

    connect() {
        // For each canvas, render the corresponding drawing
        this.canvasTargets.forEach((canvas, index) => {
            const drawingData = JSON.parse(this.drawingDataTargets[index].value);
            this.renderDrawing(canvas, drawingData);
        });
    }

    renderDrawing(canvas, drawingData) {
        const ctx = canvas.getContext('2d');
        ctx.lineWidth = 5;
        ctx.lineCap = 'round';
        ctx.strokeStyle = '#000000';

        // Clear the canvas
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        // Draw each stroke
        drawingData.forEach(stroke => {
            if (stroke.length > 0) {
                ctx.beginPath();
                ctx.moveTo(stroke[0][0], stroke[0][1]);
                
                for (let i = 1; i < stroke.length; i++) {
                    ctx.lineTo(stroke[i][0], stroke[i][1]);
                }
                
                ctx.stroke();
            }
        });
    }
}