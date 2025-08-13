<!--
Title: Gravity
Description: Exploring gravitation equations.
Image: /./images/patches/Dormarr_Patch_Gravity.png
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
            <pre>
Just some experiments with gravity simulations...
I need to add some customisations and fix the jolt on body collision.
            </pre>
            <div class="sim-container">
                <canvas class="orbitCanvas" id="orbitCanvas" height="500" width="800"></canvas>
                <canvas class="orbitCanvas" id="bodyCanvas" height="500" width="800" style="pointer-events: none;"></canvas>
            </div>
            <pre id="statsLbl">Stats</pre>
            <pre id="mousePosLbl" style="margin: 0; padding: 0;">Mouse Pos: 0, 0</pre>
            <button onclick="togglePause()" id="pauseBtn">Pause</button>
        </div>
    </body>
</html>
<style>
.sim-container{
    position: relative;
    width: 800px;
    height: 500px;
}
.orbitCanvas{
    width: 800px;
    height: 500px;
    border: 1px solid var(--cosmic-latte);
    position: absolute;
    top: 0;
    left: 0;
    margin: 0;
    padding: 0;
}
</style>
<script src="/./js/utils.js"></script>
<script>

// The 2D Orbit code.

// Add interatable stuff, custom settings etc.

let paused = false;

const statsLbl = l("statsLbl");

const mousePosLbl = l("mousePosLbl");
const orbitCanvas = l("orbitCanvas");
const orbitCtx = orbitCanvas.getContext('2d');

const bodyCanvas = l("bodyCanvas");
const bodyCtx = bodyCanvas.getContext('2d');

let mousePos;

orbitCanvas.addEventListener('mousemove', function(e){
    getMousePos(orbitCanvas, e);
    const phys = pxToPhys(mousePos.x, mousePos.y);
    mousePosLbl.textContent = `Mouse Pos: ${mousePos.x}, ${mousePos.y} \nPhys Pos: ${phys.x}, ${phys.y}`;
})

orbitCanvas.addEventListener('click', function(e){
    getMousePos(orbitCanvas, e);
    const phys = pxToPhys(mousePos.x, mousePos.y);
    createLight(phys.x, phys.y);
})

document.addEventListener('DOMContentLoaded', function(){
    statsLbl.textContent = `M/P: ${metersPerPixel.toString()}`;
})

function getMousePos(canvas, e){
    const rect = canvas.getBoundingClientRect();
    const x = Math.round(e.clientX - rect.x);
    const y = Math.round(e.clientY - rect.y);
    mousePos = { x, y };
}

function physToPx(xm, ym){
    return { x: xm / metersPerPixel, y: ym / metersPerPixel};
}

function pxToPhys(xpx, ypx){
    return { x: xpx * metersPerPixel, y: ypx * metersPerPixel};
}

function drawBodyRadiusPx(physRm){
    return Math.max(physRm / metersPerPixel, minRadPx);
}

const metersPerPixel = 1e6; // Comes to 1,000km per pixel
const G = 6.67430e-11; // Gravitational Constant
const c = 2.998e8; // Speed of light
const dt = 1e-3;
const epsilon = 1e4;

const minRadPx = 4;

let particles = [];
let bodies = [];

function createBody(x_m, y_m, r_m, m_kg, vx = 0, vy = 0){
    bodies.push({ x_m, y_m, r_m, m_kg, vx, vy});
}

function createSpawnLights(){
    const rayCount = 72;

    for(i = 0; i < rayCount; i++){
        createLight(0, (orbitCanvas.height / rayCount * i + (orbitCanvas.height / rayCount) / 2) * metersPerPixel);
    }
}

function createLight(x, y){
    const x_m = x;
    const y_m = y;
    const vx = c;
    const vy = 0;
    particles.push({ x_m, y_m, vx, vy})

}

function stepParticles(){
    if(paused) return;
    particles = particles.filter(p => {
        // Particle behaviour
        if(p.x_m / metersPerPixel < 0 || p.x_m / metersPerPixel > orbitCanvas.width){
            return false;
        }

        let ax = 0, ay = 0;
        let collided = false;

        bodies.forEach(b => {
            const dx = b.x_m - p.x_m;
            const dy = b.y_m - p.y_m;
            const distSq = dx*dx + dy*dy + epsilon*epsilon;
            const dist = Math.sqrt(distSq);
            const a = G * b.m_kg / distSq;
            ax += a * (dx / dist);
            ay += a * (dy / dist);
            if(dist <= b.r_m){
                collided = true;
            }
        });

        if(collided) return false;

        p.vx += Math.min(ax * dt, c);
        p.vy += Math.min(ay * dt, c);
        p.x_m += p.vx * dt;
        p.y_m += p.vy * dt;

        return true;
    });
}

function stepBodies(){
    if(paused) return;
    bodies.forEach(b => {
        let ax = 0; ay = 0;
        bodies.forEach(b2 => {
            if(b === b2) return;
            const dx = b2.x_m - b.x_m;
            const dy = b2.y_m - b.y_m;
            const distSq = dx*dx + dy*dy + epsilon*epsilon;
            const dist = Math.sqrt(distSq);
            const a = G * b2.m_kg / distSq;
            ax += a * (dx / dist);
            ay += a * (dy / dist);
            
            handleCollisions();
            
        });
        b.vx += ax * dt;
        b.vy += ay * dt;
    });
    
    bodies.forEach(b => {
        b.x_m += b.vx * dt;
        b.y_m += b.vy * dt;    
    });
}

function handleCollisions() {
    for (let i = 0; i < bodies.length; i++) {
        for (let j = i + 1; j < bodies.length; j++) {
            const b1 = bodies[i];
            const b2 = bodies[j];
            const dx = b2.x_m - b1.x_m;
            const dy = b2.y_m - b1.y_m;
            const dist = Math.sqrt(dx*dx + dy*dy);

            if (dist <= b1.r_m + (b2.r_m / 2)) {
                const totalMass = b1.m_kg + b2.m_kg;
                const newX = (b1.x_m * b1.m_kg + b2.x_m * b2.m_kg) / totalMass;
                const newY = (b1.y_m * b1.m_kg + b2.y_m * b2.m_kg) / totalMass;
                const newVx = (b1.vx * b1.m_kg + b2.vx * b2.m_kg) / totalMass;
                const newVy = (b1.vy * b1.m_kg + b2.vy * b2.m_kg) / totalMass;
                const newRadius = Math.cbrt( Math.pow(b1.r_m,3) + Math.pow(b2.r_m,3) );

                // replace b1 with merged and remove b2
                bodies[i] = {
                    x_m: newX, y_m: newY, r_m: newRadius,
                    m_kg: totalMass, vx: newVx, vy: newVy
                };
                bodies.splice(j, 1);
                j--;
            }
        }
    }
}

function fadeCanvas(){
    orbitCtx.fillStyle = "rgba(0, 0, 0, 0.01)";
    orbitCtx.fillRect(0,0,orbitCanvas.width, orbitCanvas.height);
    bodyCtx.clearRect(0,0,bodyCanvas.width, bodyCanvas.height);
}

function render(){
    fadeCanvas();

    for(const b of bodies){
        const p = physToPx(b.x_m, b.y_m);
        bodyCtx.beginPath();
        bodyCtx.arc(p.x, p.y, drawBodyRadiusPx(b.r_m), 0, Math.PI*2);
        bodyCtx.fillStyle = 'coral';
        bodyCtx.fill();
    }

    for(const p of particles){
        const pos = physToPx(p.x_m, p.y_m)
        orbitCtx.beginPath();
        orbitCtx.arc(pos.x, pos.y, 0.25,0,360);
        orbitCtx.fillStyle = 'white';
        orbitCtx.fill();
    }
}

function step(){
    stepBodies();
    stepParticles();
}

function togglePause(){
    paused = paused ? false : true;
    l("pauseBtn").textContent = paused ? "Play" : "Pause";
}

// createBody(550e6, 250e6, 6.371e6, 5.972e24); // Earth
// createBody(250e6, 200e6, 6.371e7, 8e34); // Earth 2
// createBody(550e6, 250e6, 6.371e6, 2e36); // Earth 2

createBody(400e6, 400e6, 2e7, 8e34, -7e7, -6e5); // Orbitter 1
createBody(500e6, 200e6, 2e7, 8e34, 7e7, 6.219e5); // Orbitter 2
createSpawnLights();

setInterval(step, dt);
setInterval(render, 1);

</script>