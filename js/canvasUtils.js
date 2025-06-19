function getCanvasGridSquareSize(canvas, canvasSize, gridCount){
    let squareSize = canvasSize / gridCount;
    canvas.width = gridCount * squareSize;
    canvas.height = gridCount * squareSize;
    return squareSize;
}


function drawCheckerboard(canvas, canvasSize, gridCount) {
    const squareSize = getCanvasGridSquareSize(canvas, canvasSize, gridCount);
    const ctx = canvas.getContext("2d");
	for (let y = 0; y < gridCount; y++) {
		for (let x = 0; x < gridCount; x++) {
			let colour = (x + y) % 2 === 0 ? "#ffbb69ff" : "#D64E6E";
			mark(ctx, x, y, colour, squareSize);
		}
	}
    console.log("Checkerboard successfully drawn.");
}

function mark(ctx, x, y, colour, squareSize){
    ctx.fillStyle = colour;
    ctx.fillRect(x * squareSize, y * squareSize, squareSize + 1, squareSize + 1);
}