<?php
require_once __DIR__ . '/../private/db.php';

$stmt = $pdo->query("SELECT * FROM posts ORDER BY created_at DESC");
$posts = $stmt->fetchAll(PDO::FETCH_ASSOC);
?>

<html>
    <head>
        <meta charset="UTF-8">
        <link href="../style.css"  rel="stylesheet">
        <title id="tabTitle">Devlogs</title>
        <link rel="manifest" href="/site.webmanifest">
    </head>
<body>
    <?php include '../shared/header.php';?>
    <div id="msg-box" class="message-container"></div>
    <div style="margin-top: 16px; margin-bottom: -112px">
        <pre id="ascii" class="ascii" style="height: 180px; width: 100%; overflow: hidden; color: var(--dim-gray)"></pre>
    </div>
    <div style="width: 100%; height: 100%; display: flex; flex-direction: column; align-items: center">
        <div class="square" style=" width: 60%; height: fit-content;">
            <h2 style="margin: 32px 0px; background-color: var(--eerie-black); padding: 8px 32px">Devlogs</h2>
            <?php foreach ($posts as $post): ?>
                <?php if ($post['visibility'] !== 'public' && $post['visibility'] !== 'featured') continue; ?>
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
        </div>
    </div>
</body>
</html>
<script src="../js/perlin.js"></script>
<script src="../js/utils.js"></script>
<script>

const FPS = 1000/30;
const userScreen = l("ascii");

const maxMessages = 7;
const container = l("msg-box");


function getNewMessage(){
    var list=[];

    list.push(choose([
        `Unhandled Exception: Message too sad.`,
        `Host your own websites!`,
        `I did this all myself, are you proud?`,
        `How long until everything changes?`,
        `Why has nobody been here?`,
        `Aren't we so lucky to be alive?`,
        `Are you still there?`,
        `Try Tacks!`,
    ]));

    return choose(list);
}

function postNewMessage(){
    // Remove existing "latest" class
    [...container.children].forEach(child => child.classList.remove("latest"));

    const p = document.createElement("p");
    p.classList.add("terminal", "latest");
    p.textContent = getNewMessage();

    container.appendChild(p);

    if(container.children.length > maxMessages){
        container.removeChild(container.children[0]);
    }
}

function renderFrame() {

    let output = "";
    const width = userScreen.clientWidth / 6;

    if(width == 0) console.log("Issue with width of Perlin ASCII at index.php");
    const height = 14;

    for (let y = 0; y < height; y++) {
      for (let x = 0; x < width; x++) {
        const value = perlin(x * 0.1, y * 0.1, t);
        output += mapValueToChar(value);
      }
      output += "\n";
    }

    userScreen.textContent = output;
    t += 0.01;
}

setInterval(renderFrame, FPS);

</script>