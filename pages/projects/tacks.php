<!--
Title: Tacks
Description: A cheeky little idle clicker.
-->

<!DOCTYPE html>
<html lang="en-UK">
<head>
	<meta charset="utf-8"/>
	<title id="title">Tacks</title>
	<meta name="viewport" content="width=device-width"/>
	<link href="/./tacks.css" rel="stylesheet">
</head>
<! ========================================================================= >
<body>
	<div style="width: 100%; display: flex; justify-content: center;">
		<div style="width: 90%; height: fit-content; display: flex; padding-top: 8px; padding-bottom: 8px; flex-direction: row; justify-content: space-between;">
			<div style="width: fit-content;">
				<a href="/./index.php">Home</a>
			</div>
			<div style="display: flex; flex-direction: row; gap: 64px;">
				<label class="small button" id="statsBtn">Stats</label>
				<label class="small button" id="openChangelog">Changelog</label>
				<label class="small button" id="openAchievements">Achievements</label>
			</div>
		</div>
	</div>
	<div class="line"></div>
	<div style="height: 300px">
		<pre id="ascii" style="z-index: -1;"></pre>
		<div class="line"></div>
		<div id="tickerContainer">
		<p class="marquee">
			<span id="ticker"></span>
		</p>
		</div>
		<div style="display: flex; flex-direction: column; align-items: center; width: 100%">
			<div class="widget buy">
				<h1 class="title">Tacks</h1>
				<p style="color: var(--black); margin-top: 0px;">In perpetual development</p>
				<br>
				<p class="tps" style="text-align:center" id="tpsLbl">Click to increment.</p>
				<label id="tacksLbl">0</label>
				<br>
				<button id="btn" style="margin-top: 24px; width: 128px">Boop</button>
			</div>
		</div>
	</div>
	<div class="line"></div>
	<div id="tackSlice" class="slice">
		<canvas id="tackCanvas"></canvas>
	</div>
	<div class="line"></div>
	
	<h2>Buy Stuff</h2>
	
	<div id="sliceContainer" style="display: flex; flex-direction: column; width: 100%">
		<div style="width: 100vw; display: flex; max-width: 100vw; flex-direction: column">
			<div class="line"></div>
			<div id="pointerSlice" class="slice">
				<canvas id="pointerCanvas"></canvas>
				<div class="widget buy">
					<button id="pointerBtn">Buy Extra Finger</button>
					<div>
						<label class="small">+0.25 per click</label> <br>
						<label class="small">Cost: <label class="small" id="pointerCostLbl">20</label></label>
						<br>
						<label class="small">Owned:<label class="small" id="pointersOwnedLbl">0</label></label>
					</div>
				</div>
			</div>
			<div class="line"></div>
			<div id="workerSlice" class="slice">
				<canvas id="workerCanvas"></canvas>
				<div class="widget buy">
					<button id="workerBtn">Buy Worker</button>
					<div>
						<label class="small">+0.4 per second</label><br>
						<label class="small">Cost: <label class="small" id="workerCostLbl">100</label></label>
						<br>
						<label class="small">Owned:<label class="small" id="workersOwnedLbl">0</label></label>
					</div>
				</div>
			</div>
			<div class="line"></div>
			<div id="printerSlice" class="slice">
				<canvas id="printerCanvas"></canvas>
				<div class="widget buy">
					<button id="printerBtn">Buy 3D Printer</button>
					<div>
						<label class="small">+1 per second</label><br>
						<label class="small">Cost: <label class="small" id="printerCostLbl">400</label></label>
						<br>
						<label class="small">Owned:<label class="small" id="printersOwnedLbl">0</label></label>
					</div>
				</div>
			</div>
			<div class="line"></div>
			<div id="dropperSlice" class="slice">
				<canvas id="dropperCanvas"></canvas>
				<div class="widget buy">
					<button id="workerBtn">Buy Dropshipper (not yet)</button>
					<div>
						<label class="small">+100 per minute (randomly)</label><br>
						<label class="small">Cost: <label class="small" id="dropperCostLbl">1000</label></label>
						<br>
						<label class="small">Owned:<label class="small" id="droppersOwnedLbl">0</label></label>
					</div>
				</div>
			</div>
			<div class="line"></div>
			<!-- TEMPLATE ==================================================================== -->
			<div id="templateSlice" class="slice">
				<canvas id="templateCanvas"></canvas>
				<div class="widget buy">
					<button id="templateBtn">Buy Template</button>
					<div>
						<label class="small">+X per Y</label><br>
						<label class="small">Cost: <label class="small" id="templateCostLbl">Z</label></label>
						<br>
						<label class="small">Owned:<label class="small" id="templatesOwnedLbl">0</label></label>
					</div>
				</div>
			</div>
			<div class="line"></div>
			<!-- TEMPLATE ==================================================================== -->
		</div>
	</div>
	<div style="align-content: left; z-index: 1; position: fixed; bottom: 16px; left: 16px;">
		<h2>Preferences</h2>
		<button id="toggleDark">Toggle Theme</button><br>
		<button id="saveGame">Save</button>
		<button id="newGame">New</button>
		<button id="cheat" style="display: none;">Cheat</button>
		<button id="dummyBtn">Dummy</button>
	</div>
	<div style="text-align: right; position: fixed; right: 16px; bottom: 16px;">
		<label class="small"; id="dateTimeLbl">DateTime</label>
	</div>
	<div id="notificationContainer" style="z-index: 20; position: fixed; display: flex; bottom: 148px; right: 16px; text-align: center; align-items:end; width: auto; flex-direction: column-reverse;"></div>
	
	<div class="slice overlay hide" id="statsWindow">
		<div class="widget overlay" style="min-width: 400px;">
			<h2>Statistics</h2>
			<div style="display: flex; flex-direction: column;">
				<label class="small" id="tacksEarnedLbl">x</label>
				<label class="small" id="totalClicksLbl">x</label>
				<label class="small" id="achievementsEarnedLbl">x</label>
				<label class="small"; id="seedLbl">Seed</label>
				<label class="small"; id="upTimeLbl">UpTime</label>
				<label class="small"; id="playedTimeLbl">PlayedTime</label>
				<br>
				<button id="closeStats">Close</button>
			</div>
		</div>
	</div>
	<div class="slice overlay" id="updateWindow">
	</div>
	<div class="slice overlay" id="changelogWindow">
		<div id="changelogContent" class="widget changelog hide"></div>
	</div>
	<div class="slice overlay" id="achievementsWindow">
		<div id="achievementsContent" class="widget changelog hide"></div>
	</div>
	<footer style="pointer-events: none; padding-bottom: 16px;">
		<label class="small" id="copyright"></label>	
		<br>
		<label class="small" id="versionLbl"></label>
	</footer>	
</body>
<script src="/./js/utils.js"></script>
<script src="/./js/perlin.js"></script>
<script src="/./js/tacks/tackonomics.js"></script>
<script src="/./js/tacks/drawing.js"></script>
<script src="/./js/tacks/achievements.js"></script>
<script src="/./js/tacks/save.js"></script>
<script src="/./js/tacks/text-formatting.js"></script>
<script>

/*=========================================================================

== TO-DO ==
✓ Redo purchase slices to be generated in code. TEST IN HOME ENVIRONMENT
✓ Add achievements window
✓ Tweaked achievement calculations code
- Implement asset meta tooltip on buy button hover
- Release Dropshipper
- Implement upgrades
- Tool tips and dynamic HTML - Make more persistent templates
✓ Add a changelog overlay window (scrollable) with all the update info going forward. Add to InnerHTML from external doc.
- Ensure backwards support.
- Fix printer positioning - adjust exclusion zone code to allow for padding.
✓ Sort out string storage 	i.e: preferences.theme?'1':'0'; etc.
- Finalise progression ideas
- Detailed breakdown of TPS per asset as tooltip on hover.

== Tackonomics ==
I've sort of just ballparked the inflation. Do some research and play with values.
The open market needs to exist too. I want a fluctuating market based on semi-realistic
	simulations of stocks.
Also do I just use the tacks as the money? Or do I implement a separate money system?
Strategic investments could work nicely alongside Politacks.

== Politacks ==
Worm your way into politics by buying off government officials, lobbying for bills, and funding wars abroad.
Basically becomes a whole other game akin to like, risk.
An ASCII board room could be really cool. Like a separate screen entirely.
Lobbyist could be its own asset, speeding up the progress of bills.
You need more negatives though. Politacks is rich with opportunity there, but otherwise idk.

== Progression ==
Finger 		Increase per click
Scavenger	Increase per second
Dropshipper	Increase per minute (randomly)
Warehouse Upgrade	Intro to commercial industry
3D Printer	Increase per second
Workers		Increase per second (during day only: pending labour law change)
R&D Upgrade		Branches off to researching new methods of production - A research/tech tree?
Board Room Upgrade	Opens up a whole coruption line of stuff

Beyond this it could go anywhere.

== Negatives/Obstacles ==
I need more things to get in the way of progress, things to overcome.
Various challenges that maintain engagement and amplify enjoyment.
These should be consequences and reactions to the players actions.

Workers Strike: Workers stop working if you abuse the working hours and pay too much?
Scavenger: Split your profits. If you set too low, they might start stealing from you.

Present an option as to how to deal with it. Make a promise or retaliate.
For example, promise to increase worker wages. If unfulfilled in x amount of time, strikes get more intense (or other consequence).
How will you handle this kind of data though? It can scale very quickly and I want to keep it minimal.
Maybe if I have a binary events system then I can save into binary strings?
Redo the achievements string system to work more widely with boolean values in general.

== Skill/Research Tree ==
Have 2 main threads, corrupt and honest.
Honest leans more towards socialism and utopian views on labour.
Corrupt goes full capitalist hellscape. Power abuses and the like.

Tack coin: Rename yourself? Dynamically name.
Invent a cryptocurrency to produce tacks and give access to stock market.

Track corruption level and have random events happen as you progress.
Make sure to make the honest line be eventful too though.

An interactable ASCII research tree. Yes please.
I need to nail tooltips and alt windows and stuff first though.


WORKS ON CANVAS
─│┌┐└┘├┤┬┴┼═║╒╓╔╕╖╗╘╙╚╛╜╝╞╟╠╡╢╣╤╥╦╧╨╩╪╫╬▀▄█▌▐■▬▲►▼◄◦☺☻●░▒▓₸ θϴԚ

Scavenger: Dead variant. 
 x
/#\
/ \

Worker: Head changes number depending on dissatisfaction.
 4
/£\
/ \

Dropshipper - Box opening?
╔══╗
║░░║
╚══╝

=========================================================================*/

// #region Data

/*=========================================================================

VERSIONING
Major 0 for pre-release, 1 for release
Minor: Increment for playable versions.
Build: Defined by feature additions. Add 'a' or 'b' for alphas and beta respectively.
Revision: Don't really need. But would increment per commit.
Date: Formatted as 'YY.MM'.

=========================================================================*/

const userScreen = l("ascii");

const MAJOR = "0";
const MINOR = "2";
const BUILD = "7a";
const REVISION = ".02"; //add decimal if using.

const VERSION_DATE = getVersionDate();
const VERSION = getVersion();

const THEME = {
	DARK: 'dark',
	LIGHT: 'light'
}

// Internal Data
let _tacks = 0;
let _tacksPerSecond;
let _tacksPerClick;

let _seed = 0;

const _owned = {
	pointer: 0,
	worker: 0,
	printer: 0,
	dropshipper: 0,
}

let _totalTacksEarned = 0;
let _totalHandmadeTacks = 0;
let _clicks = 0;

let _lastKnownTheme;

let _achievements;
let _achievementsEarned;


const DATA = {
	version: VERSION,
	seed: 0,
	tacks: 0,
	clicks: 0,
	tacksPerClick: 0,
	totalHandmadeTacks: 0,
	totalTacksEarned: 0,
	pointersOwned: 0,
	workersOwned: 0,
	printersOwned: 0,
	dropshippersOwned: 0,
	playedTime: 0,
	theme: _lastKnownTheme || THEME.LIGHT,
	achievements: [],
	achievementsEarned: 0,

}

// Light and Dark refer to the themes they are paired with.
const COLOURS = {
	green: {
		light: "#035F32",
		dark: "#43FFAE"
	},
	red: "#FC5130",
	text: {
		light: "#121212",
		dark: "#F7F7F7"
	},
	ascii: {
		light: "#5c5b59",
		dark: "#B1A688"
	},
	base: {
		light: "#f5f1ec",
		dark: "#171717"
	},
	baseInv: {
		light: "#171717",
		dark: "#f5f1ec"
	}
}

let AUTO_VALUE = {
	pointer: 0.25,
	worker: 0.4,
	printer: 1,
	dropshipper: 100,
}

let DEFAULT_COST = {
	pointer: 20,
	worker: 100,
	printer: 400,
	dropshipper: 1000,
}

let COST = {
	pointer: 20,
	worker: 100,
	printer: 400,
	dropshipper: 1000,
}

const FPS = 1000 / 32;
let _upTimeElapsed = 0;
let _playedTimeElapsed = 0;
let startTime = Date.now();

const notificationContainer = l("notificationContainer");

// Initialise html elements prior to this.
const updateLabel = {
	version: (val) => { l("versionLbl").textContent = val; },
	tacks: (val) => { l("tacksLbl").textContent = val; },
	tps: (val) => { l("tpsLbl").textContent = val; },
	pointersOwned: (val) => { l("pointersOwnedLbl").textContent = val; },
	workersOwned: (val) => { l("workersOwnedLbl").textContent = val; },
	printersOwned: (val) => { l("printersOwnedLbl").textContent = val; },
	droppersOwned: (val) => { l("droppersOwnedLbl").textContent = val; },
	title: (val) => { l("title").textContent = val; },
	dateTime: (val) => { l("dateTimeLbl").textContent = val; },
	upTime: (val) => { l("upTimeLbl").textContent = val; },
	playTime: (val) => { l("playedTimeLbl").textContent = val; },
	seed: (val) => { l("seedLbl").textContent = val; },
	copyright: (val) => { l("copyright").textContent = val; },
	pointerCost: (val) => { l("pointerCostLbl").textContent = val; },
	workerCost: (val) => { l("workerCostLbl").textContent = val; },
	printerCost: (val) => { l("printerCostLbl").textContent = val; },
	// dropshipperCost: (val) => { l("dropshipperCostLbl").textContent = val; },

	tacksEarned: (val) => { l("tacksEarnedLbl").textContent = val; },
	totalClicks: (val) => { l("totalClicksLbl").textContent = val; },
	achievementsEarned: (val) => { l("achievementsEarnedLbl").textContent = val; },
};

// #endregion

const pointers = setupRenderer("pointer", CHAR_POINTER, FONT_SIZE.pointer.base);
const workers = setupRenderer("worker", CHAR_WORKER, FONT_SIZE.worker.base);
const printers = setupRenderer("printer", CHAR_PRINTER, FONT_SIZE.printer.base);
const tacksRenderer = setupRenderer("tack", CHAR_TACK, FONT_SIZE.tack.base);

async function bootstrap(){
	loadGame();
	createAssetSlices();
	initialiseEventListeners();
	setCopyright();

	setInterval(refreshDisplay, 1000 / FPS);
	setInterval(secondDisplay, 1000);
	setInterval(autosaveGame, 600000);
	setInterval(animationHandler, 1000);

	updateLabel["version"](VERSION);
}

function initialiseEventListeners(){
	l("btn").addEventListener("click", context =>{ addTack(_tacksPerClick); _clicks++;});
	l("pointerBtn").addEventListener("click", context =>{ buy("pointer"); });
	l("workerBtn").addEventListener("click", context =>{ buy("worker"); });
	l("printerBtn").addEventListener("click", context => { buy("printer"); });
	l("toggleDark").addEventListener("click", function() { toggleTheme() });
	l("saveGame").addEventListener("click", function() { saveGame() })
	l("newGame").addEventListener("click", function() { newGame() })
	l("cheat").addEventListener("click", function() { cheat() });
	l("dummyBtn").addEventListener("click", function() { dummyClick() });
	l("statsBtn").addEventListener("click", function() { openL("statsWindow") })
	l("closeStats").addEventListener("click", function() { closeL("statsWindow") })
	l("openChangelog").addEventListener("click", function() { openChangelog() })
	l("openAchievements").addEventListener("click", function() { openAchievements() })
}

function dummyClick(){
	// openUpdate();
	cheat();
}

function cheat(){
	addTack((Math.random() * 10) * 1234567);
	buy("worker", 20, true);
	buy("pointer", 20, true);
	buy("printer", 20, true);
}

// #region Dynamic HTML

function openUpdate(){
	// Need a better system for this. Something more user friendly for my end.

	//add into update window.
	const container = l("updateWindow");
	openL("updateWindow");


	container.innerHTML = `
		<div class="widget overlay" style="min-width: 400px; border: 2px solid var(--feature)">
			<h2>UPDATE</h2>
			<div style="display: flex; flex-direction: column;">
				<label class="small">Achievements tweaks.</label>
				<label class="small">Lots of broken things but I'll get to it.</label>
				<br>
				<button id="closeUpdate">Close</button>
			</div>
		</div>
	`

	l("closeUpdate").addEventListener("click", function(){ closeL("updateWindow"); })
}

async function loadChangelog(){
	const response = await fetch('/../files/tacks/changelog.json');
	const data = await response.json();
	return data;
}

async function openChangelog(){
	const changelog = await loadChangelog();
	const container = l("changelogContent");
	openL("changelogContent");

	container.innerHTML = `<label id="closeChangelog" class="small button">Close</label>`;

	changelog.forEach(entry => {
		const section = document.createElement('div');
		section.innerHTML += `
			<h2>${entry.type}</h2>
			<h4>Version ${entry.version} - ${entry.date}</h4>
			<h3>Additions</h3>
			<ul>
				${entry.additions.map(change => `<li>${change}</li>`).join('')}
			</ul>
			<h3>Fixes</h3>
			<ul>
				${entry.fixes.map(change => `<li>${change}</li>`).join('')}
			</ul>
			<h3>Notes</h3>
			<ul>
				${entry.notes.map(change => `<li>${change}</li>`).join('')}
			</ul>
			<hr>
		`
		container.appendChild(section);
	})

	l("closeChangelog").addEventListener("click", function(){ closeL("changelogContent"); })

	container.style.display = 'block';
}

function openAchievements(){
	// List all the achievements, and indicate what's done and what's not.
	// Maybe show a visual thing of progression for numerical ones.

	// Handles differently because achievementsUnlocked is a const rather than JSON.

	const container = l("achievementsContent");
	openL("achievementsContent");

	container.innerHTML = `
	<label id="closeAchievements" class="small button">Close</label>
	<hr>
	<label class="small">Achievements unlocked: ${_achievementsEarned} / ${totalAchievementsCount}</label>
	`;


	for(i = 0; i < milestones.length; i++){
		var m = milestones[i];
		var a = achievementsUnlocked[m.id] ? `<span style="color: ${getColor(COLOURS.green)}; font-weight: bold;">Yepp</span>` : `<span style="color: ${getColor(COLOURS.red)}; font-weight: bold;">Nope</span>`;
		const section = document.createElement('div');
		section.innerHTML += `
		<h3 style="margin: 0px;">${m.label}</h3>
		<div style="display: flex; justify-content: space-between; margin-bottom: 16px;">
			<label class="smallish">${m.description}</label>
			<br>
			<label class="small">Achieved: ${a}</label>
		</div>
		<hr>
		`

		container.appendChild(section);
	}

	l("closeAchievements").addEventListener("click", function(){ closeL("achievementsContent"); })
}

async function loadAssets(){
	const response = await fetch('../files/tacks/assets.json');
	const data = await response.json();
	return data;
}

async function createAssetSlices(){
	const assets = await loadAssets();
	const container = l("sliceContainer");

	assets.forEach(asset => {
		const section = document.createElement('div');
		section.id = `${asset}Slice`;
		section.className = "slice";
		section.innerHTML += `
			<canvas id="${asset.ID}Canvas"></canvas>
			<div class="widget buy">
				<button id="${asset.ID}Btn">Buy ${asset.Name}</button>
				<div>
					<label class="small">+${asset.Produce} per ${asset.Type}</label><br>
					<label class="small">Cost: <label class="small" id="${asset.ID}CostLbl">${asset.Cost}</label></label>
					<br>
					<label class="small">Owned:<label class="small" id="${asset.ID}OwnedLbl">0</label></label>
				</div>
			</div>
			<div class="line"></div>
		`
	})		
}

// #endregion

// #region Logic

async function onRefresh(){
	forceUpdateTheme();
	catchUpInflation();
	await repopulate();
}

async function repopulate(){
	pointers.subtract(pointers.state.owned);
	workers.subtract(workers.state.owned);
	printers.subtract(printers.state.owned);
	create("pointer", _owned.pointer);
	create("worker", _owned.worker);
	create("printer", _owned.printer);
}

function create(type, amount){
	switch(type){
		case "pointer":
			pointers.create(amount);
			break;
		case "worker":
			workers.create(amount);
			break;
		case "printer":
			printers.create(amount, true);
			break;
		case "tack":
			tacksRenderer.create(amount);
			break;
	}
}

function clearCanvas(canvas){
	const ctx = canvas.getContext("2d");
	ctx.clearRect(0, 0, canvas.width, canvas.height);
}

function addTack(amount){
	_totalTacksEarned += amount;
	_tacks += amount;
	create("tack", amount);
}

// function addToLabel(name, amount) {
// 	const label = l(name + "Lbl");

// 	if (!label) {
// 		console.warn(`Label element not found: ${name}Lbl`);
// 		return;
// 	}

// 	let currentVal = parseFloat(label.textContent);
// 	if (isNaN(currentVal)) currentVal = 0;

// 	const newVal = currentVal + amount;
// 	label.textContent = Math.floor(newVal);
// }

function subtractTacks(amount){ _tacks -= amount; }


function calculateTPS(){
	_tacksPerSecond = (AUTO_VALUE.worker * _owned.worker) +
			(AUTO_VALUE.printer * _owned.printer);
}

function calculateTPC(){
	_tacksPerClick = (AUTO_VALUE.pointer * _owned.pointer) + 1;
}
// #endregion

// #region Utility

function generateSeed(){
	// Maybe the seed should be numerical for more efficient storage?
	var chars = 'abcdefghijklmnopqrstuvwxyz1234567890'.split('');
	var str = '';
	for(var i=0; i<10; i++){ str += choose(chars); }
	return str;
}

function getValidPosition(canvas, exclusion, pb = 0, pt = 0, pl = 0, pr = 0){
	let x, y;
    do {
       	x = Math.random() * (canvas.width - 80);
       	y = Math.random() * (canvas.height - 20);
    } while (
        x > exclusion.x + pl &&
        x < exclusion.x + exclusion.width - pr &&
        y > exclusion.y + pt &&
        y < exclusion.y + exclusion.height - pb
    );
    return { x, y };
}

// #endregion

const totalAchievementsCount = Object.entries(achievementsUnlocked).length;

// #region Display

// Called at FPS (1000 / 30);
function refreshDisplay(){
	calculateTPS();
	calculateTPC();
	_tacks += _tacksPerSecond / FPS;
	_totalTacksEarned += _tacksPerSecond / FPS
	updateLabels();

	create("tack", _tacksPerSecond / FPS);

	requestAnimationFrame(tacksRenderer.update);
	requestAnimationFrame(pointers.update);
	requestAnimationFrame(workers.update);
	requestAnimationFrame(printers.update);
	
}//buffalo

function updateLabels(){
	updateLabel["tacks"](numberWithCommas(Math.floor(_tacks)));
	updateLabel["tps"](_tacksPerSecond.toFixed(2) + " tps");
	updateLabel["dateTime"](new Date().toLocaleString());
	updateLabel["playTime"](`Played Time: ${convertTime(_playedTimeElapsed)}`);
	updateLabel["upTime"](`Up Time: ${convertTime(_upTimeElapsed)}`);
	updateLabel["pointerCost"](COST.pointer);
	updateLabel["tacksEarned"](`Total tacks earned: ${numberWithCommas(Math.floor(_totalTacksEarned))}`);
	updateLabel["totalClicks"](`Total clicks: ${_clicks}`);
	updateLabel["achievementsEarned"](`Achievements unlocked: ${_achievementsEarned} / ${totalAchievementsCount}`);

	updateLabel["pointerCost"](COST.pointer);
	updateLabel["workerCost"](COST.worker);
	updateLabel["printerCost"](COST.printer);
}

// Called per second
function secondDisplay(){
	// create("tack", _tacksPerSecond);
	updateLabel["title"](`${Math.floor(_tacks)} Tacks!`);
	
	//Move to initialisation
	updateLabel["seed"](`Seed: ${DATA.seed}`);


	_upTimeElapsed++;
	_playedTimeElapsed++;
	checkAchievements();
	// saveIntoData();
}

function toggleTheme(){
	document.body.classList.toggle("light");
	updateTheme();
}

function updateTheme(){
	if(getCurrentTheme() === "light"){
		_lastKnownTheme = THEME.LIGHT;
	}
	else{
		_lastKnownTheme = THEME.DARK;
	}
}

function forceUpdateTheme(){
	if(_lastKnownTheme != getCurrentTheme()){ toggleTheme(); }
}

function getCurrentTheme(){
	return isLightMode() ? THEME.LIGHT: THEME.DARK;
}

function isLightMode() {
	return document.body.classList.contains("light");
}

function getColor(type) {
	if (typeof type === "string") return type;
	return isLightMode() ? type.light : type.dark;
}

function printNotification(message, color, duration = 3000, description = ""){
	const note = document.createElement("div");
	note.className = "notification";
	note.textContent = message;
	note.style.color = getColor(color);
	note.style.backgroundColor = getColor(COLOURS.base);
	note.style.border = "solid 1px " + getColor(COLOURS.baseInv);
	
	if(description != ""){
		const desc = document.createElement("div");
		desc.className = "hidden";
		desc.textContent = description;
		desc.style.color = getColor(COLOURS.text);
		note.appendChild(desc);
	}
	notificationContainer.appendChild(note);
	
	setTimeout(() =>{
		note.classList.remove("show");
		note.classList.add("hide");
		setTimeout(() => note.remove(), 500);
	}, duration);
}

window.addEventListener("resize", debounce(resizeWindow, 150));

function resizeWindow(){
	resizeCanvas(workerCanvas, workers);
	resizeCanvas(pointerCanvas, pointers);
	resizeCanvas(tackCanvas, tacksRenderer);
	resizeCanvas(printerCanvas, printers);
}

function resizeCanvas(canvas, collection) {
	canvas.width = window.innerWidth;
	const exclusion = getExclusion(canvas);

	requestAnimationFrame(() => repopulate());
}

function renderFrame() {
	const userScreen = l("ascii");
    if (!paused) {
        let output = "";
        const width = userScreen.clientWidth / 6;

		if(width == 0) console.log("Issue with width of Perlin ASCII in tacks.html");
        const height = 18;

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
}

let frame = 0;

function animationHandler(){
	// This runs the animated ASCII stuff, right now only the 3D printer.
	frame += 1;
	if(frame>31){ frame = 0 }
	// console.log(frame);
}

var ticker = l("ticker");

function runTicker(){
	var str = getNewTicker();
	ticker.innerHTML = str;
}

// #endregion

setInterval(runTicker, 17000);
setInterval(renderFrame, FPS);

bootstrap();

</script>
</html>