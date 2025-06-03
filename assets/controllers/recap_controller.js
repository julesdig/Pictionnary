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
            this.renderDrawingNormalized(canvas, drawingData);
        });
    }

    renderDrawingNormalized(canvas, drawingData) {
        const ctx = canvas.getContext('2d');
        ctx.clearRect(0, 0, canvas.width, canvas.height);
        ctx.lineWidth = 3;
        ctx.lineCap = 'round';
        ctx.strokeStyle = '#000';

        // 1. Récupérer tous les points pour connaître les bornes
        let allX = [];
        let allY = [];

        drawingData.forEach(stroke => {
            const [xCoords, yCoords] = stroke;
            allX = allX.concat(xCoords);
            allY = allY.concat(yCoords);
        });

        const minX = Math.min(...allX);
        const maxX = Math.max(...allX);
        const minY = Math.min(...allY);
        const maxY = Math.max(...allY);

        const drawingWidth = maxX - minX;
        const drawingHeight = maxY - minY;

        // 2. Calcul du ratio d’échelle
        const scaleX = canvas.width / drawingWidth;
        const scaleY = canvas.height / drawingHeight;
        const scale = Math.min(scaleX, scaleY); // Pour garder les proportions

        // 3. Centrer le dessin dans le canvas
        const offsetX = (canvas.width - drawingWidth * scale) / 2;
        const offsetY = (canvas.height - drawingHeight * scale) / 2;

        // 4. Dessiner
        drawingData.forEach(stroke => {
            const [xCoords, yCoords] = stroke;

            if (xCoords.length > 0 && yCoords.length > 0) {
                ctx.beginPath();
                ctx.moveTo(
                    (xCoords[0] - minX) * scale + offsetX,
                    (yCoords[0] - minY) * scale + offsetY
                );

                for (let i = 1; i < xCoords.length; i++) {
                    ctx.lineTo(
                        (xCoords[i] - minX) * scale + offsetX,
                        (yCoords[i] - minY) * scale + offsetY
                    );
                }

                ctx.stroke();
            }
        });
    }
}