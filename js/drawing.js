// #region Art

//reformat to be a central source for ASCII art.

const CHAR_TACK = [
[ " Π ",
  " T " ]];

const CHAR_WORKER = [
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

// I'm really not sure what to do for this.
const CHAR_DROPPER = [
	[
		"",
		"",
		"",
		"",
		""
	]
]

// #endregion

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

const FONT_SIZE = {
	pointer: {base: 8, max: 13},
	worker: {base: 12, max: 16},
	printer: {base: 12, max: 12}
}

const SCALE_RADIUS = 100;
const ROT_RADIUS = 100; // UNUSED

const CANVAS_HEIGHT= 200;

let time = 0;

function behaviour(g, mouse, name){
	
	let yMove = 0;
	let amplification = 1.5;

	switch(name){
		case "pointer":
			amplification = 0.75;
			yMove = 2;
			break;
		case "printer":
			amplification = 0.2;
			break;
	}

	const dx = g.homeX - mouse.x;
	const dy = g.homeY - mouse.y;
	const dist = Math.sqrt(dx * dx + dy * dy);

	// Perlin noise wiggle
	const noiseX = perlin(time + g.id * 10, 0, 0) * 2 - 1; // [-1, 1]
	const noiseY = perlin(0, time + g.id * 10, 0) * 2 - 1;
	const wiggleX = noiseX * WIGGLE_AMPLITUDE;
	const wiggleY = noiseY * WIGGLE_AMPLITUDE;

	let sinY = sinWave(wiggleY * yMove, 10);

	let offsetX = wiggleX;
	let offsetY = wiggleY + sinY;

	// If within repulsion radius, push away from mouse
	if (dist < REPULSION_RADIUS && dist > 0.01) {
	const nx = dx / dist;
	const ny = dy / dist;
	const repulseStrength = (REPULSION_RADIUS - dist) / REPULSION_RADIUS * REPULSION_FORCE;
	offsetX += nx * repulseStrength;
	offsetY += ny * repulseStrength;
	}

	let scale = 1;
	if(dist < SCALE_RADIUS){
		const t= 1 - dist / SCALE_RADIUS;
		scale = 1 + t * ((W_MAX_FONT_SIZE / W_BASE_FONT_SIZE) -1);
	}

	const drawX = g.homeX + offsetX * amplification;
	const drawY = g.homeY + offsetY * amplification;
	

	const drawVector = [drawX, drawY, scale, sinY];

	return drawVector;
}

function getExclusion(canvas, height = 150, width = 300){
	return {
    x: canvas.width / 2 - 175,
    y: canvas.height / 2 - 90,
    width: width,
    height: height,
	};
}

function setupCanvas(id){
	const canvas = l(id);
	console.log(id);
	canvas.width = window.innerWidth;
	canvas.height = CANVAS_HEIGHT;
	return [canvas, canvas.getContext('2d')];
}

function setupRenderer(name, spriteArray, fontSize){
	const [canvas, ctx] = setupCanvas(`${name}Canvas`);
	const mouse = { x: 0, y: 0 };
	const collection = [];
	const state = { owned: 0 };

	canvas.addEventListener("mousemove", e => {
		const rect = canvas.getBoundingClientRect();
		mouse.x = e.clientX - rect.left;
		mouse.y = e.clientY - rect.top;
	})

	function update(){
		ctx.clearRect(0, 0, canvas.width, canvas.height);
		//var pos = getValidPosition(canvas, getExclusion(canvas));

		updateOwnedLabel();

		time += WIGGLE_SPEED;
		for(const entity of collection){
			const [x, y, scale] = behaviour(entity, mouse, name);
			drawSprite(ctx, entity.sprite, x, y, scale, fontSize);
		}

	}

	function create(amount = 1, animated = false){
		state.owned += amount;
		createSprite(spriteArray, canvas, collection, amount, animated);
	}

	function subtract(amount = 1){
		state.owned -= amount;
		collection.splice(-amount);


	}

	function updateOwnedLabel(){
		updateLabel[name + "sOwned"](_owned[name]);
	}

	return { canvas, ctx, mouse, collection, state, create, subtract, update, updateOwnedLabel};
}

function drawSprite(ctx, sprite, x, y, scale, fontSize){
	const scaledSize = fontSize * scale;
	ctx.font = `${scaledSize}px monospace`;
	ctx.textBaseline = "top";
	ctx.fillStyle = getColor(COLOURS.ascii);

	for (let i = 0; i < sprite.length; i++) {
        ctx.fillText(sprite[i], x, y + i * fontSize);
    }
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
tackCanvas.height = CANVAS_HEIGHT;

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
		drawTack(p.sprite, v[0], p.homeY - CANVAS_HEIGHT, v[2]);
	});
	
	requestAnimationFrame(updateTack);
}

// #endregion