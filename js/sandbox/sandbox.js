// For falling sand simulation
// Include other states, like stone, anti-sand?


/* ========================================================

To-do:
- Adapt functions to allow for different states (solid, gas, liquid)
- Different materials may have different properties (effected by gravity, conductive, etc.)
- Liquid checks side to side and skims surface.
- It's defining and properly using the properties. Modularity and scalability.

======================================================== */


const canvas = l("sandCanvas");
canvas.width = 800;
canvas.height = 400;
const ctx = canvas.getContext('2d');
ctx.imageSmoothingEnabled = false;

let selectedParticleType = Sand;

let dragging = false;
let brushSize = 1;
let mouse = {
    x: 0,
    y: 0,
}

const width = canvas.width;
const height = canvas.height;
let cellSize = 8;

let grid = new SandGrid(width, height, cellSize);

canvas.addEventListener("mousedown", (e) =>{
    if(e.button === 0){
        dragging = true;
        mouse.x = getMousePos(e).x;
        mouse.y = getMousePos(e).y;
    }
})

canvas.addEventListener("mouseup", (e) => {
    if(e.button === 0){
        dragging = false;
    }
})

canvas.addEventListener("mousemove", (e) => {
    if(dragging){
        // console.log(`Mouse: ${getMousePos(e).x}, ${getMousePos(e).y}`);
        mouse.x = getMousePos(e).x;
        mouse.y = getMousePos(e).y;
    }
})

canvas.addEventListener("mouseleave", (e) => {
    dragging = false;
    console.log("Mouse left.");
})

window.addEventListener("keydown", (e) => {
    console.log(`Brush size changed to ${brushSize}`);
    if (e.key === "ArrowUp") brushSize++;
    if (e.key === "ArrowDown") brushSize = Math.max(0, brushSize - 1);
});

function getMousePos(e) {
    var rect = canvas.getBoundingClientRect();
    return {
        x: Math.floor((e.x - rect.left)),
        y: Math.floor((e.y - rect.top))
    };
}


function applyBrush(x, y, tool) {
    const i0 = Math.floor(x / cellSize);
    const j0 = Math.floor(y / cellSize);
    
    for (let dx = -brushSize; dx <= brushSize; dx++) {
        for (let dy = -brushSize; dy <= brushSize; dy++) {
            const di = i0 + dx;
            const dj = j0 + dy;
            if (grid.inBounds(di, dj)) {
                if (tool === "erase") {
                    grid.set(di, dj, null);
                } else {
                    grid.set(di, dj, new selectedParticleType());
                }
            }
        }
    }
}


function getColourOffset(h, s, l){
    h += Math.random() * 4;
    s += Math.random() * 4;
    l += Math.random() * 10;
    
    return `hsl(${h},${s}%,${l}%)`;
}

const animate = () => {
    ctx.clearRect(0,0,width,height);
    if(dragging){
        // grid.insert(mouse.x, mouse.y, new selectedParticleType());
        applyBrush(mouse.x, mouse.y, 'brush');
    }
    requestAnimationFrame(animate);
    grid.update(ctx);
}
animate();