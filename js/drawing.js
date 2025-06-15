// #region Ascii Shared

const WIGGLE_AMPLITUDE = 2; // max wiggle in pixels
const WIGGLE_SPEED = 0.01;  // speed of wiggle
const REPULSION_RADIUS = 450; // smaller radius so repulsion looks nicer
const REPULSION_FORCE = 2; // how strongly guys get pushed away

const MAX_SPRITE_COUNT = 256;

const P_BASE_FONT_SIZE = 8;
const W_BASE_FONT_SIZE = 12;
const W_MAX_FONT_SIZE = 16;
const P_MAX_FONT_SIZE = 13;
const SCALE_RADIUS = 100;
const ROT_RADIUS = 100; // UNUSED

const canvasHeight = 200;

let time = 0;

function getExclusion(canvas, height = 150, width = 300){
	return {
    x: canvas.width / 2 - 175,
    y: canvas.height / 2 - 90,
    width: width,
    height: height,
	};
}

function createSprite(sprite, canvas, collection, amount, animated = false, pb = 0, pt = 0, pl = 0, pr = 0){
	populateSprite(amount, sprite, canvas, collection, animated, pb, pt, pl, pr);
}

function populateSprite(count, sprite, canvas, collection, animated, pb = 0, pt = 0, pl = 0, pr = 0){
	const exclusion = getExclusion(canvas);
	count = Math.min(count, MAX_SPRITE_COUNT);	

	// I need to find a way to move this to a sort of universal draw function.
	let spr;
	
	for (let i = 0; i < count; i++) {
		spr = animated ? sprite[0] : sprite[Math.floor(Math.random() * sprite.length)];
		const {x: X, y:Y} = getValidPosition(canvas, exclusion, pb, pt, pl, pr)
		collection.push({
			homeX: X,
			homeY: Y,
			id: i,
			sprite: spr
		});
	}
}

// #endregion

// #region Art

//reformat to be a central source for ASCII art.

const CHAR_TACK = [
[ " Π ",
  " T " ]];

const CHAR_GUY = [
[
	"  o  ",
	" /#\\ ",
	" / \\ "
],
[
	"  o  ",
	" <#v ",
	" / \\ "
],
[
	"  o  ",
	" <T> ",
	" / \\ "
],
[
	"  o  ",
	" /#> ",
	" / \\ "
],
[
	"  o  ",
	" /X\\ ",
	" / \\ "
],
[
	"  o  ",
	" (Y) ",
	" / \\ "
],
];

const CHAR_POINTER = [[
	 "   |  ",
	 "r^^|  ",
	"\\__|) "
],
[
	 "  |   ",
	 "r^'^  ",
	"\\__|) "
]];

const CHAR_PRINTER = [
	// First Layer
 ["  ┌─┐ ",
  "  │▒│ ",
  "╔═╧═╧╗",
  "║    ║",
  "╠═╤■═╣",
  "▀▀▀▀▀▀"],
 ["  ┌─┐ ",
  "  │▒│ ",
  "╔═╧═╧╗",
  "║    ║",
  "╠══╤■╣",
  "▀▀▀▀▀▀"],
 ["  ┌─┐ ",
  "  │▒│ ",
  "╔═╧═╧╗",
  "║    ║",
  "╠═╤■═╣",
  "▀▀▀▀▀▀"],
 ["  ┌─┐ ",
  "  │▒│ ",
  "╔═╧═╧╗",
  "║    ║",
  "╠╤■══╣",
  "▀▀▀▀▀▀"],

 ["  ┌─┐ ",
  "  │▒│ ",
  "╔═╧═╧╗",
  "╠╤■══╣",
  "║ █▄ ║",
  "▀▀▀▀▀▀"],
 ["  ┌─┐ ",
  "  │▒│ ",
  "╔═╧═╧╗",
  "╠═╤■═╣",
  "║ █▄ ║",
  "▀▀▀▀▀▀"],
 ["  ┌─┐ ",
  "  │▒│ ",
  "╔═╧═╧╗",
  "╠══╤■╣",
  "║ █▄ ║",
  "▀▀▀▀▀▀"],
 ["  ┌─┐ ",
  "  │▒│ ",
  "╔═╧═╧╗",
  "╠═╤■═╣",
  "║ █▄ ║",
  "▀▀▀▀▀▀"],
];

// #endregion

// #region Pointers Render

function populatePointers(){
	populateSprite(_pointersOwned, CHAR_POINTER, pointerCanvas, pointers);
}

function createPointer(amount){
	_pointersOwned += amount;
	createSprite(CHAR_POINTER, pointerCanvas, pointers, amount);
}

const pointerCanvas = l("pointerCanvas");
const pointerCtx = pointerCanvas.getContext("2d");
pointerCanvas.width = window.innerWidth;
pointerCanvas.height = canvasHeight;

let pointerCount = 0;
const pointerMouse = { x: 0, y: 0 }
const pointers = [];

pointerCanvas.addEventListener("mousemove", e => {
    const rect = pointerCanvas.getBoundingClientRect(); // get canvas position & size
    pointerMouse.x = e.clientX - rect.left; // mouse x relative to canvas
    pointerMouse.y = e.clientY - rect.top;  // mouse y relative to canvas
});

function subtractPointer(amount = 1){
	for(i = 0; i < amount; i++){
		pointers.pop();
	}
}

function drawPointer(pointerSprite, x, y, scale) {
    const pointerFontSize = P_BASE_FONT_SIZE * scale;
    const lineHeight = pointerFontSize;

    pointerCtx.font = pointerFontSize + "px monospace";
    pointerCtx.textBaseline = "top";
    pointerCtx.fillStyle = getColor(COLOURS.ascii);

    for (let i = 0; i < pointerSprite.length; i++) {
        pointerCtx.fillText(pointerSprite[i], x, y + i * lineHeight);
    }
}



function updatePointer() {
    pointerCtx.clearRect(0, 0, pointerCanvas.width, pointerCanvas.height);
    time += WIGGLE_SPEED;

    pointers.forEach(p => {
	const v = wiggle(p, pointerMouse, 1.2);
        drawPointer(p.sprite, v[0], v[1], v[2]);
    });
    
    requestAnimationFrame(updatePointer);
}

// #endregion

// #region Guys Render

function populateGuys(){
	populateSprite(_workersOwned, CHAR_GUY, workerCanvas, workers);
}

function createWorker(amount){
	_workersOwned += amount;
	createSprite(CHAR_GUY, workerCanvas, workers, amount);
}

const workerCanvas = l("workerCanvas");
const workerCtx = workerCanvas.getContext("2d");
workerCanvas.width = window.innerWidth;
workerCanvas.height = canvasHeight;

const workerMouse = { x: 0, y: 0 };
const workers = [];

workerCanvas.addEventListener("mousemove", e => {
	const rect = workerCanvas.getBoundingClientRect();
	workerMouse.x = e.clientX - rect.left; // mouse x relative to canvas
	workerMouse.y = e.clientY - rect.top;  // mouse y relative to canvas
});

function subtractGuy(amount = 1){
	for(i = 0; i < amount; i++){
		workers.pop();
	}
}

function drawGuy(guySprite, x, y, scale) {
	const fontSize = W_BASE_FONT_SIZE * scale;
	const lineHeight = fontSize;

	workerCtx.font = fontSize + "px monospace";
	workerCtx.textBaseline = "top";
	workerCtx.fillStyle = getColor(COLOURS.ascii);
	for (let i = 0; i < guySprite.length; i++) {
		workerCtx.fillText(guySprite[i], x, y + i * lineHeight);
	}
}

function updateWorker() {
	workerCtx.clearRect(0, 0, workerCanvas.width, workerCanvas.height);
	time += WIGGLE_SPEED;

	workers.forEach(g => {
		const v = wiggle(g, workerMouse, 0);
		drawGuy(g.sprite, v[0], v[1], v[2]);
	});

	requestAnimationFrame(updateWorker);
}

// #endregion

// #region Printer Render

function populatePrinter(){
	populateSprite(_printersOwned, CHAR_PRINTER, printerCanvas, printers);
}

function createPrinter(amount = 1){
	_printersOwned += amount;
	createSprite(CHAR_PRINTER, printerCanvas, printers, amount, true);
}

const printerCanvas = l("printerCanvas");
const printerCtx = printerCanvas.getContext("2d");
printerCanvas.width = window.innerWidth;
printerCanvas.height = canvasHeight;

const PRNTR_BASE_FONT_SIZE = 12;
const printerMouse = { x: 0, y: 0 };
let printers = [];

printerCanvas.addEventListener("mousemove", e => {
	const rect = printerCanvas.getBoundingClientRect();
	printerMouse.x = e.clientX - rect.left; // mouse x relative to canvas
	printerMouse.y = e.clientY - rect.top;  // mouse y relative to canvas
});

function drawPrinter(printerSprite, x, y, scale, animated = false){
	const printerFontSize = PRNTR_BASE_FONT_SIZE * scale;
	const lineHeight = printerFontSize;// * 0.75;

	printerCtx.font = `${printerFontSize}px monospace`;
	printerCtx.textBaseline = "top";
	printerCtx.fillStyle = getColor(COLOURS.ascii);	
	
	for(let i = 0; i < printerSprite.length; i++){
		
		f = Math.floor((frame + 1) % 8 );


		spr = animated ? CHAR_PRINTER[f][i] : printerSprite[i];
		printerCtx.fillText(spr, x , y + i * lineHeight);
	}
}

function updatePrinter(){
	// clearCanvas(printerCanvas);
	printerCtx.clearRect(0, 0, printerCanvas.width, printerCanvas.height);

	var pos = getValidPosition(printerCanvas, getExclusion(printerCanvas));

	printers.forEach(p => {
		const v = wiggle(p, printerMouse, 0.3, 0.2)
		drawPrinter(p.sprite, v[0], v[1], v[2], true);
	})

	requestAnimationFrame(updatePrinter);
}

function subtractPrinter(amount = 1){
	for(let i = 0; i < amount; i++){
		printers.pop();
	}
}

// #endregion

// #region Tack Render

function createTack(amount = 1) {
	if(tacks.length >= MAX_SPRITE_COUNT) return;
	createSprite(CHAR_TACK, tackCanvas, tacks, amount)
}

function populateTack(){
	populateSprite(tackCount, CHAR_TACK, tackCanvas, tacks);
}

const tackCanvas = l("tackCanvas");
const tackCtx = tackCanvas.getContext("2d");
tackCanvas.width = window.innerWidth;
tackCanvas.height = canvasHeight;

const T_BASE_FONT_SIZE = 18;
const yVelocity = 2;
let tackCount = 0;
const tackMouse = { x: 0, y: 0 };
let tacks = [];

function drawTack(tackSprite, x, y, scale) {
	const tackFontSize = T_BASE_FONT_SIZE * scale;
	const lineHeight = tackFontSize / 2;

	tackCtx.font = `${tackFontSize}px monospace`;
	tackCtx.textBaseline = "top";
	tackCtx.fillStyle = getColor(COLOURS.ascii);

	for (let i = 0; i < tackSprite.length; i++) {
		tackCtx.fillText(tackSprite[i], x, y + i * lineHeight);
	}
}

function updateTack() {
	tackCtx.clearRect(0, 0, tackCanvas.width, tackCanvas.height);

	tacks = tacks.filter(p => p.homeY < tackCanvas.height + 200);

	tacks.forEach(p => {
		p.homeY += Math.random() * yVelocity + 1;
		const v = wiggle(p, tackMouse, 0);
		drawTack(p.sprite, v[0], p.homeY - canvasHeight, v[2]);
	});
	
	requestAnimationFrame(updateTack);
}

// #endregion