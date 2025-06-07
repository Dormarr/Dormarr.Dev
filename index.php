<html>
<head>
    <meta charset="UTF-8">
    <link href="style.css"  rel="stylesheet">
    <title id="tabTitle">Dormarr.Dev</title>
</head>
<body>
    <div id="msg-box" class="message-container"></div>
    <h1>Stinky buttholes</h1>
    <h2>More stinky buttholes</h2>
    <p>Something something eat poo</p>
    <div class="slice" style="justify-content: space-around;">
        <div>
            <a href="pages/quay.html">Quay</a>
        </div>
        <div>
            <a href="pages/tacks.html">Tacks</a>
        </div>
        <div>
            <a>Something else</a>
        </div>
    </div>
    <div>
        <div class="square" style="background-color: var(--cerise);"></div>
        <div class="square" style="background-color: var(--burnt-sienna);"></div>
        <div class="square" style="background-color: var(--earth-yellow);"></div>
    </div>    
    <?php include 'shared/footer.php';?>
</body>
</html>
<script src="utils.js"></script>
<script>

const maxMessages = 5;
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


setInterval(postNewMessage, 7000)

</script>