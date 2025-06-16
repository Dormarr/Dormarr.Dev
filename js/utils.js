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