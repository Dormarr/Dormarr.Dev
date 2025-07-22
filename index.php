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
    <!-- <p>I still need to spruce up the projects and demos pages, but it's coming along :)</p>
    <div style="height: 192px;"></div>
    <div style="display: flex; flex-direction: row; width: 100%; gap: 8%; justify-content: center;">
        <a class="tarot left" href="pages/demo.php">
            <img src="/images/Dormarr_Tarot_Demo.png" class="image">
        </a>
        <a class="tarot middle" href="pages/projects.php">
            <img src="/images/Dormarr_Tarot_Projects.png" class="image">
        </a>
        <a class="tarot right" href="pages/devlog.php">
            <img src="/images/Dormarr_Tarot_Devlog.png" class="image">
        </a>
    </div>
    <div style="height: 192px;"></div>
    <hr> -->
    <div style="padding: 64px 0px; width: 100%; height: fit-content; display: flex; align-items: center; justify-content: center;">
        <!-- Bento -->
         <div class="bento-container">
            <a class="tarot left box" style="grid-area: box-1;" href="pages/demo.php">
                <img src="/images/Dormarr_Tarot_Demo.png" class="image">
            </a>
            <a class="tarot middle box" style="grid-area: box-2" href="pages/projects.php">
                <img src="/images/Dormarr_Tarot_Projects.png" class="image">
            </a>
            <a class="tarot right box" style="grid-area: box-3" href="pages/devlog.php">
                <img src="/images/Dormarr_Tarot_Devlog.png" class="image">
            </a>
            <div class="box" style="grid-area: box-4; padding: 16px;">
                <p style="font-size: 14px;">Current time (UTC): <span id="bentoTimeLbl">tick 0</span></p>
                <p>Up time: <span id="bentoUpTimeLbl"></span></p>
            </div>
            <div class="box" style="grid-area: box-5; padding: 16px;">
                <div style="display: flex; flex-direction: column; align-items: center; justify-items: center;">
                    <label style="font-size: 24px;" id="tacksLbl">0</label>
                    <button id="miniTacksBtn">Tacks!</button>
                </div>
            </div>
            <div class="box" style="grid-area: box-6; padding: 16px;">
                <p>Perlin</p>
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
<script>

const uptimeLbl = l("bentoTimeLbl");
const tacksLbl = l("tacksLbl");

l("miniTacksBtn").addEventListener('click', context => { doTack() });


function doTack(){
    let i = parseInt(tacksLbl.textContent);
    i++;
    tacksLbl.textContent = i;
}


let i = 0;
function updateTime(){
    i++;
    uptimeLbl.textContent = `tick ${i}`;
}

setInterval(updateTime, 1000);

</script>