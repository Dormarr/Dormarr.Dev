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

const FONT_SIZE = {
	pointer: {base: 8, max: 12},
	worker: {base: 12, max: 16},
	printer: {base: 12, max: 14},
	tack: {base: 18, max: 18},
}

const SCALE_RADIUS = 75;

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
		case "tack":
			amplification = 0;
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
		scale = 1 + t * ((FONT_SIZE[name].max / FONT_SIZE[name].base) -1);
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
	canvas.width = window.innerWidth;
	canvas.height = CANVAS_HEIGHT;
	return [canvas, canvas.getContext('2d')];
}

function setupRenderer(name, spriteArray, fontSize){
	const [canvas, ctx] = setupCanvas(`${name}Canvas`);
	const mouse = { x: 0, y: 0 };
	let collection = [];
	const state = { owned: 0 };
	const yVel = (name === "tack") ? 4 : 0;
	const lineHeightMult = (name === "tack") ? 0.5 : 1;

	canvas.addEventListener("mousemove", e => {
		const rect = canvas.getBoundingClientRect();
		mouse.x = e.clientX - rect.left;
		mouse.y = e.clientY - rect.top;
	})

	function update(){
		ctx.clearRect(0, 0, canvas.width, canvas.height);
		//var pos = getValidPosition(canvas, getExclusion(canvas));
		if(name == "tack"){
			collection = collection.filter(p => p.homeY < canvas.height + 200);

			// time += WIGGLE_SPEED;
			for(const p of collection){
				p.homeY += yVel + (Math.random() * 4);
				const [x, y, scale] = behaviour(p, mouse, name);
				drawSprite(name, ctx, p.sprite, x, p.homeY - CANVAS_HEIGHT, scale, FONT_SIZE.tack.base, lineHeightMult);
			};
			
		}else{

			updateOwnedLabel();
	
			time += WIGGLE_SPEED;
			for(const entity of collection){
				const [x, y, scale] = behaviour(entity, mouse, name);
				drawSprite(name, ctx, entity.sprite, x, y, scale, fontSize, lineHeightMult);
			}
		}
	}

	function create(amount = 1, animated = false){
		state.owned += amount;
		if(collection.length <= MAX_SPRITE_COUNT){
			createSprite(spriteArray, canvas, collection, amount, animated);
		}
	}

	function subtract(amount = 1){
		state.owned -= amount;
		collection.splice(-amount);
	}

	function updateOwnedLabel(){
		try{
			updateLabel[name + "sOwned"](_owned[name]);
		}catch(e){
			// Do nothing. Just here for tacks.
		}
	}

	return { canvas, ctx, mouse, collection, state, create, subtract, update, updateOwnedLabel};
}

function drawSprite(name, ctx, sprite, x, y, scale, fontSize, lineHeightMult = 1){
	const scaledSize = fontSize * scale;
	ctx.font = `${scaledSize}px monospace`;
	ctx.textBaseline = "top";
	ctx.fillStyle = getColor(COLOURS.ascii);
	let lineHeight = fontSize * lineHeightMult * scale;

	let animated = (name == "printer") ? true : false;

	for (let i = 0; i < sprite.length; i++) {

		f = Math.floor((frame + 1) % 8);
		spr = animated ? CHAR_PRINTER[f][i] : sprite[i];
        ctx.fillText(spr, x, y + i * lineHeight);
    }
}

function createSprite(sprite, canvas, collection, amount, animated, pb = 0, pt = 0, pl = 0, pr = 0){
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