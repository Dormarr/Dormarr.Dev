<!--
Title: Procedural Generation
Description: Perlin generated terrain with data driven pixel art
-->


<!DOCTYPE html>
<html>
<head>
    <link href="/./style.css" rel="stylesheet">
    <title id="tabTitlePG">Procedural Generation</title>
</head>
<body>
    <?php include '../../shared/header.php';?>
    <div style="display: flex; flex-direction: column; width: 100%; justify-content: center; align-items: center;">
        <h2 style="text-align: center; margin-top: 32px;">Procedural Generation</h2>
        <p style="max-width: 512px; word-wrap: normal;">
            The below shows a system I built a while ago, where you use two perlin maps to
            simulate temperature and precipitation to generate a semi-realistic biome model.
            I reused an old colour map to represent these biome values. 
            Minecraft, for example, uses this same method.
            <br>
            <br>
            Play with the sliders to get a sense of what does what.
        </p>
        <canvas id="proceduralCanvas" style="width: 512px; height: 512px; background-color: aliceblue;"></canvas>
        <img id="colourMap" src="/./images/colourMap.png" style="display: none; width:8px; height: 8px;">
        <canvas id="colourCanvas" style="display: none;"></canvas>
        <button onClick="drawPerlin()" style="width: 128px;">Redraw</button>
        <br>
        <div style="display: flex; flex-direction: row; gap: 24px;">
            <div style="display: flex; flex-direction: column;">
                <h3>Temperature</h3>
                <div class="sliderDiv">
                    <label>Scale</label>
                    <input id="sliderS" class="slider" type="range" min="0" max="1" value="0.5" step="0.01" onInput="updateValue(this.value, 1)">
                    <label id="sliderSLabel">0.5</label>
                </div>
                <div class="sliderDiv">
                    <label>Frequency</label>
                    <input id="sliderF" class="slider" type="range" min="0.01" max="1" value="0.5" step="0.01" onInput="updateValue(this.value, 2)">
                    <label id="sliderFLabel">0.5</label>
                </div>
                <div class="sliderDiv">
                    <label>Octaves</label>
                    <input id="sliderO" class="slider" type="range" min="1" max="10" value="5" step="1" onInput="updateValue(this.value, 3)">
                    <label id="sliderOLabel">5</label>
                </div>
                <div class="sliderDiv">
                    <label>Persistence</label>
                    <input id="sliderP" class="slider" type="range" min="0.01" max="1" value="0.5" step="0.01" onInput="updateValue(this.value, 4)">
                    <label id="sliderPLabel">0.5</label>
                </div>
                <div class="sliderDiv">
                    <label>Amplitude</label>
                    <input id="sliderA" class="slider" type="range" min="0.01" max="1" value="0.5" step="0.01" onInput="updateValue(this.value, 5)">
                    <label id="sliderALabel">0.5</label>
                </div>
            </div>
            <div style="display: flex; flex-direction: column;">
                <h3>Precipitation</h3>
                <div class="sliderDiv">
                    <label>Scale</label>
                    <input id="sliderS2" class="slider" type="range" min="0.01" max="1" value="0.5" step="0.01" onInput="updateValue(this.value, 6)">
                    <label id="sliderS2Label">0.5</label>
                </div>
                <div class="sliderDiv">
                    <label>Frequency</label>
                    <input id="sliderF2" class="slider" type="range" min="0.01" max="1" value="0.5" step="0.01" onInput="updateValue(this.value, 7)">
                    <label id="sliderF2Label">0.5</label>
                </div>
                <div class="sliderDiv">
                    <label>Octaves</label>
                    <input id="sliderO2" class="slider" type="range" min="1" max="10" value="5" step="1" onInput="updateValue(this.value, 8)">
                    <label id="sliderO2Label">5</label>
                </div>
                <div class="sliderDiv">
                    <label>Persistence</label>
                    <input id="sliderP2" class="slider" type="range" min="0.01" max="1" value="0.5" step="0.01" onInput="updateValue(this.value, 9)">
                    <label id="sliderP2Label">0.5</label>
                </div>
                <div class="sliderDiv">
                    <label>Amplitude</label>
                    <input id="sliderA2" class="slider" type="range" min="0.01" max="1" value="0.5" step="0.01" onInput="updateValue(this.value, 10)">
                    <label id="sliderA2Label">0.5</label>
                </div>
            </div>
        </div>
        <p style="max-width: 512px; word-wrap: normal;">
            I won't go into how it works specifically, but noise functions can be used for all sorts.
            Having a sort of weighted location system is invaluable, particularly in game design.
            Pair that with logic and condition gates and you can generate an incredibly detailed, natural looking, world.
        </p>
        <div style="width: 90%; height: 2px; background-color: white; margin: 32px;"></div>
        <h3>Dynamic Tiles (WIP)</h3>
        <div style="display: flex; flex-direction: row; align-items: center;">
            <div style="display: flex; flex-direction: column; align-items: center;">
                <canvas id="tileCanvas" style="width: 512px; height: 512px; background-color: blue;"></canvas>
                <!-- <input type="text" id="seedInput"> -->
                <button onclick="drawTiles()" style="max-width: 256px;">Redraw Tiles</button>
                <div class="sliderDiv">
                    <label>Tile Count</label>
                    <input id="tileCountSlider" class="slider" type="range" min="0" max="24" value="12" step="1" onInput="updateValue(this.value, 11)">
                    <label id="tileCountValue">12</label>
                </div>
            </div>
            <p style="max-width: 400px; word-wrap: normal;">
                Having designed a single UV tile, I converted it to coordinate data and generate tiles entirely with code.
                By pulling the biome data, I can then recolour each segment to create smooth terrain transitions, using a colour map of rgb values.
                The result is a wide range of terrain, stemming from a single pieces of art.
                <br>
                <br>
                I originally developed this technique for my game Khôra, but found it too computationally taxing to use at the time.
                Although now having made this, maybe I should revisit it...
            </p>
        </div>
        <div style="height: 800px;"></div>
    </div>
</body>
</html>
<script src="/./js/utils.js"></script>
<script src="/./js/canvasUtils.js"></script>
<script src="/./js/perlin.js"></script>
<script src="/./js/proc-gen/colourMap.js"></script>
<script>

const gridCount = 64;
const canvas = l("proceduralCanvas");
const ctx_pg = canvas.getContext("2d");
const canvasWidth = getCanvasWidth(canvas);
const squareSize = getCanvasGridSquareSize(canvas, canvasWidth, gridCount);

const colourMapCanvas = l("colourCanvas");
const img = l("colourMap");
const ctx_cm = colourMapCanvas.getContext("2d", { willReadFrequently: true });

let tileGridCount = 12;
const tileCanvas = l("tileCanvas");
const ctx_tc = tileCanvas.getContext('2d');
const tileCanvasWidth = getCanvasWidth(tileCanvas);
const tileSquareSize = getCanvasGridSquareSize(tileCanvas, tileCanvasWidth, tileGridCount);

const sliderSValue = l("sliderSLabel");
const sliderFValue = l("sliderFLabel");
const sliderOValue = l("sliderOLabel");
const sliderPValue = l("sliderPLabel");
const sliderAValue = l("sliderALabel");

const sliderS2Value = l("sliderS2Label");
const sliderF2Value = l("sliderF2Label");
const sliderO2Value = l("sliderO2Label");
const sliderP2Value = l("sliderP2Label");
const sliderA2Value = l("sliderA2Label");

const tileCountValue = l("tileCountValue");

let _s = 0.3;
let _f = 0.2;
let _o = 2;
let _p = 0.7;
let _a = 1;

let _s2 = 0.3;
let _f2 = 0.3;
let _o2 = 2;
let _p2 = 1;
let _a2 = 1;

// const seedInput = l("seedInput");
let seed;

function updateValue(value, id){
    // This ID system was thrown together without much thought. It should probably be changed but it's good enough for now.
    switch(id){
        case(1):
            sliderSValue.textContent = value;
            _s = parseFloat(value);
            drawPerlin();
            break;
        case(2):
            sliderFValue.textContent = value;
            _f = parseFloat(value);
            drawPerlin();
            break;
        case(3):
            sliderOValue.textContent = value;
            _o = parseFloat(value);
            drawPerlin();
            break;
        case(4):
            sliderPValue.textContent = value;
            _p = parseFloat(value);
            drawPerlin();
            break;
        case(5):
            sliderAValue.textContent = value;
            _a = parseFloat(value);
            drawPerlin();
            break;
        case(6):
            sliderS2Value.textContent = value;
            _s2 = parseFloat(value);
            drawPerlin();
            break;
        case(7):
            sliderF2Value.textContent = value;
            _f2 = parseFloat(value);
            drawPerlin();
            break;
        case(8):
            sliderO2Value.textContent = value;
            _o2 = parseFloat(value);
            drawPerlin();
            break;
        case(9):
            sliderP2Value.textContent = value;
            _p2 = parseFloat(value);
            drawPerlin();
            break;
        case(10):
            sliderA2Value.textContent = value;
            _a2 = parseFloat(value);
            drawPerlin();
            break;
        case(11):
            tileCountValue.textContent = value;
            tileGridCount = value;
            drawTiles();
            break;
    }
}

let tempMap = [];
let precMap = [];

// Grayscale array for debugging
const COLOURS = [
    'rgb(1,1,1)',
    'rgb(20,20,20)',
    'rgb(40,40,40)',
    'rgb(60,60,60)',
    'rgb(80,80,80)',
    'rgb(90,90,90)',
    'rgb(100,100,100)',
    'rgb(120,120,120)',
    'rgb(130,130,130)',
    'rgb(140,140,140)',
    'rgb(160,160,160)',
    'rgb(180,180,180)',
    'rgb(200,200,200)',
    'rgb(220,220,220)',
    'rgb(240,240,240)',
    'rgb(255,255,255)',
]

// Hardcoded pixel art coords. 8x8
const UV_COORDS = [
    [[0,0],[1,0],[0,1]],
    [[2,0],[2,1],[3,1],[3,2]],
    [[3,0],[4,0],[4,1],[5,1],[5,2]],
    [[5,0],[6,0],[7,0],[7,1]],
    [[1,1],[0,2],[1,2],[0,3],[0,4]],
    [[2,2],[1,3],[2,3],[2,4]],
    [[4,2],[3,3],[4,3],[5,3]],
    [[6,1],[6,2],[7,2],[7,3]],
    [[1,4],[0,5],[1,5],[1,6]],
    [[3,4],[2,5],[3,5],[2,6]],
    [[4,4],[4,5],[5,5]],
    [[6,3],[5,4],[6,4],[7,4],[6,5],[7,5]],
    [[0,6],[0,7],[1,7]],
    [[3,6],[2,7],[3,7]],
    [[4,6],[5,6],[6,6],[4,7]],
    [[7,6],[5,7],[6,7],[7,7]]
]

function loadCanvas(){
    colourMapCanvas.width = img.width;
    colourMapCanvas.height = img.height;

    ctx_cm.drawImage(img, 0, 0);
}

loadCanvas();

function generateSeed(){
    seed = Math.floor((Math.random()) * 255);
}

function regeneratePerlin(value, id){
    updateValue(value, id);
    generateSeed();
    drawPerlin();
}

function drawPerlin(){
    let scale = _s

    // let seedIn = seedInput.textContent;
    // seed = (seedIn != undefined || seedIn != null) ? seedIn : generateSeed();
    generateSeed();

    for(let y = 0; y < gridCount; y++){
        tempMap[y] = [];
        precMap[y] = [];
        for(let x = 0; x < gridCount; x++){
            // const value = octavePerlin(x * scale, y * scale, seed * scale, 0.5, 1, 0.5, 0.5);
            const temperature =     octavePerlin(x * _s, y * _s, seed * _s, _o, _p, _f, _a);
            const precipitation =   octavePerlin(x * _s2, y * _s2, seed * _s2, _o2, _p2, _f2, _a2);

            tempMap[y][x] = normaliseToColourMap(temperature);
            precMap[y][x] = normaliseToColourMap(precipitation);
            
            output = getColour(tempMap[y][x], precMap[y][x]);
            mark(ctx_pg, x, y, output, squareSize);
        }
    }
    console.log("Drawn Perlin");
}

function drawTiles() {
    const tileSize = tileCanvasWidth / tileGridCount;
    const pixelSize = tileSize / 8;
    // let seedIn = seedInput.textContent;
    generateSeed();

    for(let x = 0; x < tileGridCount; x++){
        for(let y = 0; y < tileGridCount; y++){

            const col = getAdjacentColours(x, y);
            const bcol = getBlendedColour(col);


            for(let i = 0; i < UV_COORDS.length; i++){

                for(const [fx, fy] of UV_COORDS[i]){
                    
                    // ctx_tc.fillStyle = COLOURS[i]; // Grayscale
                    // ctx_tc.fillStyle = col[i];
                    ctx_tc.fillStyle = bcol[i];
                    ctx_tc.fillRect((fx * pixelSize) + (x * tileSize), (fy * pixelSize) + (y * tileSize), pixelSize + 1, pixelSize + 1);
                }
            }
        }
    }
    console.log(`Drawn ${tileGridCount * tileGridCount} Tiles.`);
}

function getAdjacentColours(x, y){
    let adj = [];
    let i = 0;
    
    let ht, hp;

    for(let fy = y-2; fy <= y+2; fy++){
        for(let fx = x-2; fx <= x+2; fx++){
            const temperature =     octavePerlin((-fx / 2) * _s, (-fy / 2) * _s, seed * _s, _o, _p, _f, _a);
            const precipitation =   octavePerlin((-fx / 2) * _s2, (-fy / 2) * _s2, seed * _s2, _o2, _p2, _f2, _a2);
            
            const t = normaliseToColourMap(temperature);
            const p = normaliseToColourMap(precipitation);

            if(fy === 0 && fx === 0){
                ht = t;
                hp = p;
            }
            adj[i] = getColour(t, p);
            i++;
        }
    }

    return adj;
}

function lerpColour(c1, c2, t) {
    const regex = /\d+/gm;
    const [r1, g1, b1] = c1.match(regex).map(Number);
    const [r2, g2, b2] = c2.match(regex).map(Number);
    const r = Math.round(r1 + (r2 - r1) * t);
    const g = Math.round(g1 + (g2 - g1) * t);
    const b = Math.round(b1 + (b2 - b1) * t);
    return `rgb(${r},${g},${b})`;
}

function adjustColour(c, a){
    const regex = /\d+/gm;
    const [r1, g1, b1] = c.match(regex).map(Number);
    const r = r1 + a.r;
    const g = g1 + a.g;
    const b = b1 + a.b;
    return `rgb(${r},${g},${b})`;
}

function getBlendedColour(col){
    const cTL = col[6];
    const cTR = col[8];
    const cBL = col[16];
    const cBR = col[18];
    const cT = col[7];
    const cL = col[11];
    const cR = col[13];
    const cB = col[17];
    
    const c_ = col[12];

    const ratio = 0.4;

    let bcol = [];

    // I kind of hate this but it works so meh.

    bcol[0] = lerpColour(cT, cL, ratio);
    bcol[1] = lerpColour(cT, c_, ratio);
    bcol[2] = lerpColour(cT, c_, ratio);
    bcol[3] = lerpColour(cT, cR, ratio);
    bcol[4] = lerpColour(cL, c_, ratio);
    bcol[5] = adjustColour(c_, {r: -2, g: -4, b: -3});
    bcol[6] = c_;
    bcol[7] = lerpColour(cR, c_, ratio);
    bcol[8] = lerpColour(cL, c_, ratio);
    bcol[9] = c_;
    bcol[10] = adjustColour(c_, {r: -2, g: -4, b: -3});
    bcol[11] = lerpColour(cR, c_, ratio);
    bcol[12] = lerpColour(cB, cL, ratio);
    bcol[13] = lerpColour(cB, c_, ratio);
    bcol[14] = lerpColour(cB, c_, ratio);
    bcol[15] = lerpColour(cB, cR, ratio);

    return bcol;

}

function bilerp(tl, tr, bl, br, x, y) {
    const top = tl + (tr - tl) * x;
    const bottom = bl + (br - bl) * x;
    return top + (bottom - top) * y;
}

function getDataSafe(tempMap, precMap, x, y) {
    const gx = Math.max(0, Math.min(gridCount - 1, x));
    const gy = Math.max(0, Math.min(gridCount - 1, y));
    return {
        temp: tempMap[gy][gx],
        prec: precMap[gy][gx]
    };
}

function createTile(temperature, precipitation, x, y){
    // use normalised values to read colourmap.
    const c = getColour(temperature, precipitation);
    const colour = `rgb(${c.r},${c.g},${c.b})`;

    mark(ctx_pg, x, y, colour, squareSize);
}

function normaliseToColourMap(n){
    return Math.max(0, Math.min(Math.ceil((n + 1 / 2) * 16), 15));
}

function getColour(x, y, ad = null){
    
    const index = y * 16 + x;
    const c = colourMap[index];

    // console.log(`Index ${index} of colourMap returned ${colour}.`);

    if(c === undefined) console.log("Botched.");
    // else{ console.log(c); }
    const colour = undefined ? "rgb(1,1,1)" : `rgb(${c.r + ad},${c.g + ad},${c.b + ad})`;

    return colour;
}

// DEPRECATED
function mapValueToRange(val, rng){
    const normalised = (val + 1) / 2;
    const index = Math.floor(normalised * (rng.length - 1));
    return rng[index];
}

function getCanvasWidth(_canvas){
    return parseFloat(getComputedStyle(_canvas).width);
}

drawPerlin();
drawTiles();
// drawCheckerboard(canvas, canvasWidth, 32);

</script>