<html>
<head>
    <meta charset="UTF-8">
    <link href="style.css"  rel="stylesheet">
    <title id="tabTitle">Dormarr.Dev</title>
</head>
<body style="overflow: hidden">
    <div id="msg-box" class="message-container"></div>
    <div style="height: 210px;">
        <pre id="ascii" class="ascii" style="width: 100%; height: 210px;"></pre>
        <div style="position: absolute; top: 48px; left: 32px;">
            <h1>Dormarr.Dev</h1>
            <p>I'm working on it. Leave me alone.</p>
        </div>
    </div>
    <div class="line"></div>
    <div class="dir" style="justify-content: space-around;">
        <div>
            <a href="pages/quay.html">> Quay <</a>
        </div>
        <div>
            <a href="pages/tacks.html">> Tacks <</a>
        </div>
        <div>
            <a style="text-decoration: line-through;">Something else</a>
        </div>
    </div>
    <div class="line"></div>
    <div style="display: flex; flex-direction: row; width: vw; justify-content: space-around; padding: 16px;">
        <div class="square" style="border: 2px solid maroon;  width: 60%; height: fit-content;">
            <h3>Devlogs</h3>
            <p>I'll add some PHP widgets and stuff here</p>
        </div>
        <div class="square" style="border: 2px solid blueviolet; width: 40%; height: fit-content;">
            <img src="../images/PFP.png" width="248px" height="auto" style="max-width: 100%">
            <br>
            <p>It's me.</p>
        </div>
    </div>

    <?php include 'shared/footer.php';?>
</body>
</html>
<script src="../js/utils.js"></script>
<script src="../js/perlin.js"></script>
<script>

const FPS = 1000 / 30;

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
setInterval(postNewMessage, 7000)

</script>