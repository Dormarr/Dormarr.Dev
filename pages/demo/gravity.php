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
<script src="/./js/gravity.js"></script>
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


document.addEventListener('DOMContentLoaded', function(){
    statsLbl.textContent = `M/P: ${metersPerPixel.toString()}`;
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
})

function fadeCanvas(){
    orbitCtx.fillStyle = "rgba(0, 0, 0, 0.01)";
    orbitCtx.fillRect(0,0,orbitCanvas.width, orbitCanvas.height);
    bodyCtx.clearRect(0,0,bodyCanvas.width, bodyCanvas.height);
}


function step(){
    stepBodies(bodyCanvas);
    stepParticles(orbitCanvas);
}

function render(){
    fadeCanvas();
    renderGravity(orbitCtx, bodyCtx);
}

// createBody(550e6, 250e6, 6.371e6, 5.972e24); // Earth
// createBody(250e6, 200e6, 6.371e7, 8e34); // Earth 2
// createBody(550e6, 250e6, 6.371e6, 2e36); // Earth 2

createBody(400e6, 400e6, 2e7, 8e34, -7e7, -6e5); // Orbitter 1
createBody(500e6, 200e6, 2e7, 8e34, 7e7, 6.219e5); // Orbitter 2
createSpawnLights(orbitCanvas, 48);

setInterval(step, dt);
setInterval(render, 1);

</script>