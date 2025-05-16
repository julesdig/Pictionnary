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
        ctx.clearRect(0, 0, canvas.width, canvas.height);

        ctx.lineWidth = 5;
        ctx.lineCap = 'round';
        ctx.strokeStyle = '#000';

        const originalWidth = 800;
        const originalHeight = 600;

        const scaleX = canvas.width / originalWidth;
        const scaleY = canvas.height / originalHeight;

        drawingData.forEach(stroke => {
            const [xCoords, yCoords] = stroke;

            if (xCoords.length > 0 && yCoords.length > 0) {
                ctx.beginPath();
                ctx.moveTo(xCoords[0] * scaleX, yCoords[0] * scaleY);

                for (let i = 1; i < xCoords.length; i++) {
                    ctx.lineTo(xCoords[i] * scaleX, yCoords[i] * scaleY);
                }

                ctx.stroke();
            }
        });
    }
}