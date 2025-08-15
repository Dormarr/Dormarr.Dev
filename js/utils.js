// A collection of general purpose utility functions.

function l(what){ return document.getElementById(what);}

// Make a chooseSeeded function to manage the randomness procedurally.
function choose(arr){ return arr[Math.floor(Math.random()*arr.length)]; }

function randomFloor(x) {if ((x%1)<Math.random()) return Math.floor(x); else return Math.ceil(x);}

function sinWave(radian, radius){ return Math.sin(radian) * radius; }

function debounce(func, delay) {
	let timeoutId;
	return (...args) => {
		clearTimeout(timeoutId);
		timeoutId = setTimeout(() => func.apply(this, args), delay);
	};
}

function openL(el){
	try{
		l(el).classList.remove("hide");
		l(el).classList.add("show");
	}catch(e){
		console.warn(e);
	}
}

function closeL(el){
	try{
		l(el).classList.remove("show");
		l(el).classList.add("hide");
	}catch(e){
		console.warn(e);
	}
}

function getContrastColor(hexColor) {
	// Remove leading hash if present
	hexColor = hexColor.replace(/^#/, '');

	// Expand shorthand (e.g. #abc -> #aabbcc)
	if (hexColor.length === 3) {
		hexColor = hexColor.split('').map(c => c + c).join('');
	}

	// Parse RGB values
	const r = parseInt(hexColor.substr(0, 2), 16);
	const g = parseInt(hexColor.substr(2, 2), 16);
	const b = parseInt(hexColor.substr(4, 2), 16);

	// Calculate relative luminance
	const luminance = (0.299 * r + 0.587 * g + 0.114 * b);

	// Threshold of 128 is a common cutoff
	return luminance > 128 ? 'dark' : 'light';
}

function getMousePos(canvas, e){
    const rect = canvas.getBoundingClientRect();
    const x = Math.round(e.clientX - rect.x);
    const y = Math.round(e.clientY - rect.y);
    mousePos = { x, y };
}