<!--
Title: Particles
Description: A falling sand physics sim.
-->

<!DOCTYPE html>
<html>
    <head>
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <meta charset="UTF-8">
        <title>Particle Engine</title>
        <link href="/./style.css" rel="stylesheet">
    </head>
    <body>
        <?php include '../../shared/header.php';?>
        <div style="display: flex; flex-direction: column; align-items: center;">
            <h1>Particle Engine</h1>
            <canvas id="sandCanvas" style="width: 800px; height: 400px; border: 1px solid var(--dim-gray)">
                <!-- SAND -->
            </canvas>
            <input type="range" class="slider" min="2" max="12" value="8" onchange="setCellSize(this.value)">
            <label id="cellSizeLbl">8</label>
            <div>
                <button onclick="switchParticle(Sand)">
                    Sand
                </button>
                <button onclick="switchParticle(Water)">
                    Water
                </button>
                <button onclick="switchParticle(Stone)">
                    Stone
                </button>
            </div>
            <div style="margin-top: 32px;">
                <p>
                    I'm still working on it.
                </p>
            </div>
        </div>
    </body>
</html>
<script src="/./js/utils.js"></script>
<script src="/./js/sandbox/particle.js"></script>
<script src="/./js/sandbox/particle/sand.js"></script>
<script src="/./js/sandbox/particle/water.js"></script>
<script src="/./js/sandbox/particle/stone.js"></script>
<script src="/./js/sandbox/sandGrid.js"></script>
<script src="/./js/sandbox/sandbox.js"></script>
<script>
let cellSizeLbl = l("cellSizeLbl");

function setCellSize(size){
    cellSize = size;
    cellSizeLbl.textContent = size;
    
    grid = new SandGrid(width, height, cellSize);
}

function switchParticle(particle){
    selectedParticleType = particle;
}
</script>