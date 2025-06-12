function l(what){ return document.getElementById(what);}
function choose(arr){ return arr[Math.floor(Math.random()*arr.length)]; }
function randomFloor(x) {if ((x%1)<Math.random()) return Math.floor(x); else return Math.ceil(x);}