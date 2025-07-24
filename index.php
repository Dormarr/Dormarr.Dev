<?php
require_once __DIR__ . '/private/db.php';

$stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC");
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <link href="style.css"  rel="stylesheet">
    <title id="tabTitle">Dormarr.Dev</title>
    <link rel="manifest" href="/site.webmanifest">
</head>
<body style="overflow-x: hidden;">
    <div class="tiled-header"></div>
    <div class="line"></div>
    <div class="dir" style="justify-content: space-around;">
        <div>
            <a href="pages/demo/quay.php">> Quay <</a>
        </div>
        <div>
            <a href="pages/projects/tacks.php">> Tacks <</a>
        </div>
        <div>
            <a href="pages/projects/particles.php">> Particles <</a>
        </div>
    </div>
    <div class="line"></div>
    <div style="padding: 64px 0px; width: 100%; height: fit-content; display: flex; align-items: center; justify-content: center;">
        <!-- Bento -->
         <div class="bento-container">
            <a class="tarot left box" style="grid-area: box-1; border: 0;" href="pages/demo.php">
                <img src="/images/Dormarr_Tarot_Demo.png" class="image">
            </a>
            <a class="tarot middle box" style="grid-area: box-2; border: 0;" href="pages/projects.php">
                <img src="/images/Dormarr_Tarot_Projects.png" class="image">
            </a>
            <a class="tarot right box" style="grid-area: box-3; border: 0;" href="pages/devlog.php">
                <img src="/images/Dormarr_Tarot_Devlog.png" class="image">
            </a>
            <div class="box" style="grid-area: box-4; padding: 16px;">
                <p style="font-size: 14px;">Current time (UTC): <span id="bentoTimeLbl">tick 0</span></p>
                <p>Up time: <span id="bentoUpTimeLbl"></span></p>
            </div>
            <div class="box" style="grid-area: box-5; overflow: hidden; position: relative;">
                <div style="display: flex; flex-direction: column; height: 100%; align-items: center; justify-content: center;">
                    <label style="font-size: 24px;" id="tacksLbl">0</label>
                    <button id="miniTacksBtn">Tacks!</button>
                </div>
                <canvas id="tacksCanvas" style="margin: 0px; position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: -1;"></canvas>
            </div>
            <div class="box" style="grid-area: box-6; overflow: hidden;">
                <div id="ascii-widget" style="background-color: #171717; white-space: pre;"></div>
            </div>
            <div class="box" id="github-stats" style="grid-area: box-7;">
            </div>
            <div class="box" style="grid-area: box-8; padding: 16px;">
                <p>Not sure what to do here.<br>Maybe the devlogs.</p>
            </div>
         </div>
    </div>
    <hr>
    <div style="display: flex; flex-direction: row; width: 100%; justify-content: center; margin: 64px 0px; gap: 64px">
        <div class="square" style="min-width: 200px; width: 30%; max-width: 500px; height: fit-content;">
            <img src="../images/PFP.png" width="248px" height="auto" style="max-width: 75%">
            <br>
            <p style="width: 248px; max-width: 75%">I make things, usually for fun. Forever exploring the lengths to which I'll go to do something fun.</p>
        </div>
        <div class="square" style="width: 40%; min-width: 200px; max-width: 700px; height: fit-content;">
            <h3>Featured Devlogs</h3>
            <?php foreach ($posts as $post): ?>
                <?php if ($post['visibility'] !== 'featured') continue; ?>
                <article class="post-block">
                <!-- <img src="<?= $post['thumbnail_url'] ?>"> -->
                <h3><?= htmlspecialchars($post['title']) ?></h3>
                <?php if (!empty($post['subtitle'])): ?>
                    <p class="subtitle"><?= htmlspecialchars($post['subtitle']) ?></p>
                    <?php endif; ?>
                    <small class="post-date">Posted on <?= date('F j, Y', strtotime($post['created_at'])) ?></small>
                    <br>
                    <a href="/shared/view.php?slug=<?= urlencode($post['slug']) ?>" class="read-more">Read More</a>
                </article>
                <?php endforeach; ?>
                <a href="/../pages/devlog.php">See All</a>
            </div>
        </div>
    <?php include 'shared/footer.php';?>
</body>
</html>
<script src="../js/utils.js"></script>
<script src="../js/perlin.js"></script>
<script src="../js/github_stats.js"></script>
<script>

const uptimeLbl = l("bentoTimeLbl");
const tacksLbl = l("tacksLbl");

l("miniTacksBtn").addEventListener('click', context => { doTack() });

const tackSymbol = [
  " Π ",
  " T "
];


let activeTacks = [];
const tackCanvas = l("tacksCanvas");
const tackCtx = tackCanvas.getContext("2d");
const lineHeight = 7;
const speed = 3; // pixels per frame

function doTack() {
    let i = parseInt(tacksLbl.textContent);
    i++;
    tacksLbl.textContent = i;

    const x = Math.random() * tackCanvas.getBoundingClientRect().width;
    activeTacks.push({ x, y: -20 });
}

function animateTacks() {
    tackCtx.clearRect(0, 0, tackCanvas.width, tackCanvas.height);

    for (let i = 0; i < activeTacks.length; i++) {
        const tack = activeTacks[i];

        tackCtx.font = `14px monospace`;
        tackCtx.textBaseline = "top";
        tackCtx.fillStyle = '#ffffff';

        for (let j = 0; j < tackSymbol.length; j++) {
            tackCtx.fillText(tackSymbol[j], tack.x, tack.y + j * lineHeight);
        }
        tack.y += speed;
    }
    
    activeTacks = activeTacks.filter(t => t.y < tackCanvas.height);

    requestAnimationFrame(animateTacks);
}

animateTacks();


let i = 0;
function updateTime(){
    i++;
    uptimeLbl.textContent = `tick ${i}`;
}

setInterval(updateTime, 1000);

const asciiWidget = l("ascii-widget");

function renderAscii() {

    let output = "";
    const width = asciiWidget.clientWidth / 6;

    if(width == 0) console.log("Issue with width of Perlin ASCII at index.php");
    const height = 12;

    for (let y = 0; y < height; y++) {
      for (let x = 0; x < width; x++) {
        const value = perlin(x * 0.1, y * 0.1, t);
        output += mapValueToChar(value);
      }
      output += "\n";
    }

    asciiWidget.textContent = output;
    t += 0.01;
}

setInterval(renderAscii, 1000/30);

</script>