<!--
Title: Quay Code
Description: A 2D CMYK Matrix Code
-->

<!DOCTYPE html>
<html>
<head>
    <link href="/./style.css" rel="stylesheet">
    <title id="tabTitleQ">Quay</title>
</head>
<body class="light">
    <?php include '../../shared/header.php';?>
    <div class="slice">
        <h1>Quay</h1>
    </div>
    <div class="slice" style="align-items: center; flex-direction: column; gap: 8px;">
            <canvas id="quayCanvas"></canvas>
            <label id="debug1">Test</label>
            <label id="debug2">Test 2</label>
            <input id="toEncode">
            <button id="generateBtn" onclick="generateCode()">Generate</button>
            <button onclick="downloadCanvas(l('checkerboard'))">Download PNG</button>
    </div>
</body>
</html>
<script src="/./js/utils.js"></script>
<script src="https://cdn.jsdelivr.net/npm/@bnb-chain/reed-solomon/dist/index.aio.js"></script>
<script>

const canvas = l("quayCanvas");
const ctx = canvas.getContext("2d");

let gridSize = 0;
let squareSize = 0;

let rawText;

const inputField = l("toEncode");
const debug1 = l("debug1");
const debug2 = l("debug2");

const rs = new RS.ReedSolomon();

function recalculateCanvas(){
    squareSize = 450 / gridSize;
    canvas.width = gridSize * squareSize;
    canvas.height = gridSize * squareSize;
}

async function generateCode(){
    ctx.clearRect(0, 0, canvas.width, canvas.height);
    parity = await preprocessInputText();
    var encoded = await processToFinalText(rawText, parity);
    drawCode(encoded);
    debug2.textContent = encoded + " -- Parity: " + parity;
}

function drawCode(data){
    drawSection(COLOURS.K, SIZE.Frame);
    drawSection(COLOURS.W, SIZE.Whites);
    drawData(data, SIZE.Data);
    //drawCheckerboard();
}

function generateHeader(){
    // I need to make sure the definition for this is solid and future proof.
    // Needs to include text (or parity) amount, 
}

function drawData(data, coords){
    for(i = 0; i < coords.length; i++){
        mark(coords[i][0], coords[i][1], pairToColour[data[i]]);
    }
}

function drawSection(colour, coords){
    for(i = 0; i < coords.length; i++){
        mark(coords[i][0], coords[i][1], colour);
    }
}

function drawCheckerboard() {
	for (let y = 0; y < gridSize; y++) {
		for (let x = 0; x < gridSize; x++) {
			let colour = (x + y) % 2 === 0 ? "#ffbb69ff" : "#D64E6E";
			mark(x, y, colour);
		}
	}
}

function mark(x, y, colour){
    ctx.fillStyle = colour;
    ctx.fillRect(x * squareSize, y * squareSize, squareSize + 1, squareSize + 1);
}

const SIZE = {
    Frame: [],
    Whites: [],
    Header: [],
    Data: [],   
}

const COLOURS = {
    C: "#01BAEF",
    M: "#E5446D",
    Y: "#FFC130",
    K: "#2D2A32",
    W: "#F8F4E3"
}

const pairToColour = {
    "00": COLOURS.C,
    "01": COLOURS.M,
    "10": COLOURS.Y,
    "11": COLOURS.K,
}

async function preprocessInputText(){
    // Determine input field size as an amount of chars.
    // 2 d-bits per symbol, 

    rawText = inputField.value;

    var ar = rawText.split('');
    var input = ar.length;

    var parity = 0;

    if(input <= 5){
        gridSize = 7;
        parity = 5 - input;
    }
    else{
        gridSize = 0;
        parity =  0;
    }

    getCoords(gridSize);
    recalculateCanvas();
    debug1.textContent = `${ar}, Parity: ${parity}`;
    return parity;
}

function getCoords(size){
    // I need to transfer the coordinates over.
    switch(size){
        case 7:
            SIZE.Frame = [[0,0],[1,0],[2,0],[3,0],[4,0],[5,0],[6,0],[0,1],[6,1],[0,2],[6,2],[0,3],[6,3],[0,4],[6,4],[0,5],[6,5],[0,6],[1,6],[2,6],[3,6],[4,6],[5,6],[6,6]];
            SIZE.Whites = [];
            SIZE.Header = [[1,1],[2,1],[3,1],[4,1],[5,1]];
            SIZE.Data = [[1,2],[2,2],[3,2],[4,2],[5,2],[1,3],[2,3],[3,3],[4,3],[5,3],[1,4],[2,4],[3,4],[4,4],[5,4],[1,5],[2,5],[3,5],[4,5],[5,5]];
            break;
        case 0:
            console.log("Unsupported amount.");
            drawCheckerboard();
            break;
    }
}

async function processToFinalText(input, parity){

    const dataBytes = textToUint8Array(input);
    const eccEncoded = await encodeECC(dataBytes, parity);
    const binaryPairs = uint8ArrayToBinaryPairs(eccEncoded);

    

    // const decoder = new TextDecoder();
    // const decoded = decoder.decode(eccEncoded);

    // const charArray = rawText.split('');
    // const bin = charArrayToBinaryPairs(charArray);
    // debug1.textContent = `Char Binary: ${bin}`;

    //debug1.textContent = `Data Bytes: ${dataBytes},\n Encoded: ${eccEncoded}`;//,\n BinaryPairs: ${binaryPairs}`;

    return binaryPairs;
}


function textToUint8Array(text) {
    return new TextEncoder().encode(text);
}

async function encodeECC(data, parityBytes) {
    return await rs.encode(data, parityBytes);
}

function uint8ArrayToBinaryPairs(uint8array) {
    var binString = "";
    for(var i = 0; i < uint8array.length; i++){
        binString += uint8array[i].charCodeAt(0).toString(2).padStart(8, '0') + "";
    }
    const pairs = [];
    for (let i = 0; i < binString.length; i += 2) {
        pairs.push(binString.slice(i, i + 2));
    }
    return pairs;
}

function charArrayToBinaryPairs(charArray) {
    var binString = "";
    for(var i = 0; i < charArray.length; i++){
        binString += charArray[i].charCodeAt(0).toString(2).padStart(8, '0') + "";
    }
    const pairs = [];
    for (let i = 0; i < binString.length; i += 2) {
        pairs.push(binString.slice(i, i + 2));
    }
    return pairs;
}

function downloadCanvas(canvas, filename = "quay.png") {
	const link = document.createElement("a");
	link.download = filename;
	link.href = canvas.toDataURL("image/png");
	link.click();
}

</script>
