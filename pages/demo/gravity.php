<!--
Title: Gravity
Description: Exploring gravitation equations.
-->

<!DOCTYPE html>
<html>
    <head>
        <link href="/./style.css" rel="stylesheet">
        <title id="tabTitle">Gravity</title>
    </head>
    <body class="dark">
        <?php include '../../shared/header.php';?>
        <div class="slice">
            <h1>Gravity (WIP)</h1>
        </div>
        <br>
        <div class="slice" style="align-items: center; flex-direction: column; gap: 8px;">
            <p>Just some experiments with gravity simultion...</p>
            <canvas id="orbitCanvas" width="800" height="500" style="border: 1px solid var(--cosmic-latte); margin: 0; padding: 0;"></canvas>
            <pre id="mousePosLbl" style="margin: 0; padding: 0;">Mouse Pos: 0, 0</pre>
            <button onclick="togglePause()" id="pauseBtn">Pause</button>
        </div>
    </body>
</html>
<script src="/./js/utils.js"></script>
<script>

// The 2D Orbit code.

let paused = false;

const mousePosLbl = l("mousePosLbl");
const orbitCanvas = l("orbitCanvas");
const orbitCtx = orbitCanvas.getContext('2d');
let mousePos;

orbitCanvas.addEventListener('mousemove', function(e){
    getMousePos(orbitCanvas, e);
    mousePosLbl.textContent = `Mouse Pos: ${mousePos.x}, ${mousePos.y}`;
})

function getMousePos(canvas, e){
    const rect = canvas.getBoundingClientRect();
    const x = Math.round(e.clientX - rect.x);
    const y = Math.round(e.clientY - rect.y);
    mousePos = { x, y };
}

const G = 6.67430e-3;

let particles = [];
let bodies = [];

function createBody(x, y, r, m){
    bodies.push({x, y, r, m});
    orbitCtx.beginPath();
    orbitCtx.arc(x, y, r, 0, 360);
    orbitCtx.fillStyle = 'coral';
    orbitCtx.fill();
}

function createLight(){
    const rayCount = 24;

    const s = 2;
    const m = 0;

    for(i = 0; i < rayCount; i++){
        const y = orbitCanvas.height / rayCount * i + (orbitCanvas.height / rayCount) / 2;
        const x = 0;
        const phi = 0;
        const sx = 0;
        const sy = 0;
        particles.push({ x, y, s, m, sx, sy})
    }
}

function stepParticles(){
    if(paused) return;
    particles = particles.filter(p => p.x < orbitCanvas.width);
    // Particle behaviour
    particles.forEach(p =>{
        bodies.forEach(b => {
            const dx = b.x - p.x;
            const dy = b.y - p.y;
            const distSq = dx * dx + dy * dy;
            const dist = Math.sqrt(distSq);

            if(dist > b.r){
                const a = G * b.m / distSq; // Acceleration magnitude
                const ax = a * (dx / dist);
                const ay = a * (dy / dist);

                p.sx = (p.sx || p.s) + ax;
                p.sy = (p.sy || 0) + ay;
            }

            // p.phi = Math.atan2(dx,dy);
            // p.phi += -2 / 1 * dx;
        });
        p.x += p.sx;
        p.y += p.sy;
    });
}

function renderParticles(){
    if(paused) return;
    particles.forEach(p =>{
        orbitCtx.beginPath();
        orbitCtx.arc(p.x, p.y, 0.5,0,360);
        orbitCtx.fillStyle = 'white';
        orbitCtx.fill();
    })
}

function togglePause(){
    paused = paused ? false : true;
    l("pauseBtn").textContent = paused ? "Play" : "Pause";
}

// createBody(550, 250, 12.742, 5.972e24); // Earth
createBody(550, 250, 50, 21.552e4);
createLight();

setInterval(stepParticles, 20);
setInterval(renderParticles, 1);

</script>