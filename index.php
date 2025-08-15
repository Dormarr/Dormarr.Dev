<?php
require_once __DIR__ . '/private/db.php';

$stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC");
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <link href="style.css"  rel="stylesheet">
    <title id="tabTitle">Dormarr.Dev</title>
    <link rel="manifest" href="/site.webmanifest">
</head>
<body>
    <div style="position: absolute; width:100%; top: 32px; z-index: 100; justify-items: center;">
        <div class="titleBlock">
            <h2>Dormarr.Dev</h2>
            <p>Hell yeah.</p>
        </div>
    </div>
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
            <div class="box" style="grid-area: box-4; padding: 8px;">
                <!-- This can be the ascii clock I've been working on? -->
                 <label style="color: var(--burnt-sienna)">Latest Devlog</label>
                <div style="height: 100%; text-overflow: ellipsis; position: relative;">
                    <?php
                    $latestPost = null;
                    foreach($posts as $post){
                        if($post['visibility'] === 'public' || $post['visibility'] === 'featured'){
                            $latestPost = $post;
                            break;
                        }
                    }
                    if($latestPost){
                        $excerptLength = 100;
                        $words = explode(' ', strip_tags($latestPost['content']));
                        $excerpt = implode(' ', array_slice($words, 0, $excerptLength)) . '...';
                    }
                    ?>
                    <h4><?= htmlspecialchars($latestPost['title']) ?></h4>
                    <div class="devlog-content">
                        <?= htmlspecialchars(($excerpt)) ?>
                    </div>
                    <div class="devlog-fade"></div>
                    <a href="/shared/view.php?slug=<?= urlencode($latestPost['slug']) ?>" class="read-more-overlay"></a>
                </div>
            </div>
            <div class="box" style="grid-area: box-5; overflow: hidden; position: relative;">
                <div style="display: flex; flex-direction: column; height: 100%; align-items: center; justify-content: center; background-color: #171717;">
                    <label style="font-size: 24px; z-index: 2" id="tacksLbl">0</label>
                    <button id="miniTacksBtn" style="z-index: 2">Tacks!</button>
                    <a style="position: absolute; bottom: 12px; right: 12px;" href="/pages/projects/tacks.php">Play</a>
                </div>
                <canvas id="tacksCanvas" style="margin: 0px; position: absolute; top: 0; left: 0; width: 100%; height: 100%; z-index: 1;"></canvas>
            </div>
            <div class="box" style="grid-area: box-6; overflow: hidden;">
                <div id="ascii-widget" style="background-color: #171717; white-space: pre;"></div>
            </div>
            <div class="box" id="github-stats" style="grid-area: box-7;"></div>
            <div class="box desktopOnly" style="grid-area: box-8; padding: 16px;">
                <p>Not sure what to do here.<br>Maybe Gravity?</p>
            </div>
            <div class="box" style="grid-area: box-9; margin: 0px; padding: 0px; height: 100%">
                <div style="position: relative; height: 100%; align-items: center;">
                    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; justify-items: center; align-content: center;">
                        <pre id="asciiClock" style="letter-spacing: 0.05em; line-height: 0.6em; margin: 0; font-size: 14px; width: auto; height: auto; pointer-events: none;"></pre>
                    </div>
                    <div style="display: flex; position: absolute; top: 0; left: 0; width: 100%; height: 100%; justify-content: center; align-items: center;">
                        <canvas id="clockCanvas"style="height: 200px; width: 200px; margin: 0;"></canvas>
                    </div>
                </div>
            </div>
            <div class="box" style="grid-area: box-10; margin: 0px; padding: 0px">
                <div style="position: relative; height: 100%">
                    <div style="position: absolute; top: 0; left: 0; width: 100%; height: 100%; background-color: darkblue">
                        <pre id="404" style="width: 100%; height: 100%;">> ERROR 404</pre>
                        <div style="position: absolute; bottom: 0px;">
                            <p style="font-size: 14px; margin: 0px; padding: 0px;">> <span id="404span"></span></p>
                            <p style="font-size: 14px; margin: 0px; padding: 0px;">> <span id="404time">00:00:00</span> UTC</p>
                        </div>
                    </div>
                </div>
            </div>
            <div class="box" style="grid-area: box-11; padding: 8px;">
                <!-- This can be the ascii clock I've been working on? -->
                 <label style="color: var(--burnt-sienna)">Read Next</label>
                <div style="height: 100%; text-overflow: ellipsis; position: relative;">
                    <?php
                    $latestPost = null;
                    $index = 0;
                    foreach($posts as $post){
                        if($post['visibility'] === 'public' || $post['visibility'] === 'featured'){
                            $index += 1;
                            if($index >= 2){ // Changed this value to get the nth latest post.
                                $latestPost = $post;
                                break;
                            }
                        }
                    }
                    if($latestPost){
                        $excerptLength = 100;
                        $words = explode(' ', strip_tags($latestPost['content']));
                        $excerpt = implode(' ', array_slice($words, 0, $excerptLength)) . '...';
                    }
                    ?>
                    <h4><?= htmlspecialchars($latestPost['title']) ?></h4>
                    <div class="devlog-content">
                        <?= htmlspecialchars(($excerpt)) ?>
                    </div>
                    <div class="devlog-fade"></div>
                    <a href="/shared/view.php?slug=<?= urlencode($latestPost['slug']) ?>" class="read-more-overlay"></a>
                </div>
            </div>
         </div>
    </div>
    <div style="height: 200px;"></div>
    <?php include 'shared/footer.php';?>
</body>
</html>
<script src="../js/utils.js"></script>
<script src="../js/perlin.js"></script>
<script src="../js/github_stats.js"></script>
<script>

let time = '';
let date = '';
const timeLbl = l("404time");
const tacksLbl = l("tacksLbl");

l("miniTacksBtn").addEventListener('click', context => { doTack() });

const tackSymbol = [
  " Π ",
  " T "
];


let lastTouchTime = 0;

document.addEventListener('touchend', function (e) {
    const now = new Date().getTime();
    if (now - lastTouchTime <= 300) {
        e.preventDefault(); // prevent double-tap zoom
    }
    lastTouchTime = now;
}, { passive: false });


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

function updateTime(){
    time = new Date().toLocaleTimeString();
    timeLbl.textContent = time;
}

function slowUpdate(){
    date = new Date().toLocaleDateString();
    l("404span").textContent = date;
}
slowUpdate();
setInterval(updateTime, 100);
setInterval(slowUpdate, 60 * 1000);

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

<script>

    const clockCanvas = l("clockCanvas");
    const clockCtx = clockCanvas.getContext('2d');
    const clock = l("asciiClock");
    const clockSize = 17;

    function setupClock(){
        let txt = "";
        clock.textContent = txt;
        for(i = 0; i < clockSize; i++){
            for(j = 0; j < clockSize; j++){
                txt += ".";
            }
            clock.textContent += txt + "\n";
            txt = "";
        }
    }

    const drawCircle = radius => {
        // x^2 + y^2 = radius^2 draws a circle apparently.

        const expectedVal = radius ** 2;
        const printNo = [12, 11, 1, 10, 2, 9, 3, 8, 4, 7, 5, 6];

        let printIndex = 0;
        let skip = false;

        for(let y = -radius; y <= radius; y++){
            for(let x = -radius; x <= radius; x++){
                const computedVal = x ** 2 + y ** 2;

                if(Math.abs(computedVal - expectedVal) <= radius){
                    clock.textContent += "#";
                }
                else if(computedVal <= 36 && computedVal >= 33){
                    // this is the actual numbers on the clock.
                    clock.textContent += `${printNo[printIndex]}`;
                    printIndex++;
                    if(printNo[printIndex] >= 10) skip = true;
                }
                else if(computedVal == 0){
                    clock.textContent += "@";
                }
                else{
                    //This facilitated a checkerboard
                    if(!skip){
                        clock.textContent += ' ';
                    }else{
                        skip = false;
                    }
                }
            }
            clock.textContent += "\n";
        }
    }

    drawCircle(8);

    function drawHands(){

        clockCtx.clearRect(0, 0, clockCanvas.width, clockCanvas.height);

        const now = new Date();
        const centerX = (clockCanvas.width / 2) - 7;
        const centerY = clockCanvas.height / 2;

        const s = now.getSeconds();
        const m = now.getMinutes();
        const h = now.getHours() % 12;

        const sAngle = (Math.PI / 30) * s - Math.PI / 2;
        const mAngle = (Math.PI / 30) * m - Math.PI / 2;
        const hAngle = (Math.PI / 6) * h + (Math.PI / 360) * m - Math.PI / 2;

        drawClockHand(centerX, centerY, sAngle, 0.02, 0.45, 'white', 1);
        drawClockHand(centerX, centerY, mAngle, 0.02, 0.5);
        drawClockHand(centerX, centerY, hAngle, 0.01, 0.3);

    }

    function drawClockHand(centerX, centerY, angle, mFrom, mTo, colour = 'white', lineWidth = 2){
        clockCtx.strokeStyle = colour;
        clockCtx.lineWidth = lineWidth;
        clockCtx.beginPath();
        clockCtx.moveTo(centerX + Math.cos(angle) * centerX * mFrom, centerY + Math.sin(angle) * centerY * mFrom);
        clockCtx.lineTo(centerX + Math.cos(angle) * centerX * mTo, centerY + Math.sin(angle) * centerY * mTo);
        clockCtx.stroke();
    }

    setInterval(drawHands, 100);

</script>