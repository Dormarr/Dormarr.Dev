// #region Save Logic

function newGame() {

	// Should probably have an "Are you sure" bit before actually reseting.
	console.log("New Game triggered.");
	localStorage.removeItem("tacksSave");
	loadGame(true);
	resetTime();
	pointers.subtract(_owned.pointer);
	workers.subtract(_owned.worker);
	printers.subtract(_owned.printer);
	_owned.pointer = 0;
	_owned.worker = 0;
	_owned.printer = 0;
	onRefresh();
	resetPrices();
	resetAchievements();
	printNotification("New Game Started", COLOURS.green);
	discreetSave();
	console.log("New Game complete.");
}

function resetTime(){
	_upTimeElapsed = 0;
	_playedTimeElapsed = 0;
}

const FIXED_KEY = "tackstack-key123"; // Shared secret
const FIXED_SALT = new TextEncoder().encode("tackstack-salt");

// Derive a persistent key using PBKDF2
async function getKey() {
	if (!window.encryptionKey) {
		const keyMaterial = await crypto.subtle.importKey(
			"raw",
			new TextEncoder().encode(FIXED_KEY),
			{ name: "PBKDF2" },
			false,
			["deriveKey"]
		);

		window.encryptionKey = await crypto.subtle.deriveKey(
			{
				name: "PBKDF2",
				salt: FIXED_SALT,
				iterations: 100000,
				hash: "SHA-256"
			},
			keyMaterial,
			{ name: "AES-GCM", length: 256 },
			false,
			["encrypt", "decrypt"]
		);
	}
	return window.encryptionKey;
}

async function saveGame(discreet = false) {

	await saveIntoData();

	const key = await getKey();
	const iv = crypto.getRandomValues(new Uint8Array(12));
	const encoded = new TextEncoder().encode(JSON.stringify(DATA));
	const encrypted = await crypto.subtle.encrypt({ name: "AES-GCM", iv }, key, encoded);

	const encryptedData = {
		iv: Array.from(iv),
		cipherText: Array.from(new Uint8Array(encrypted))
	};

	localStorage.setItem("tacksSave", JSON.stringify(encryptedData));

	if (!discreet) printNotification("Saved Game", getColor(COLOURS.green));
}

function autosaveGame() {
	saveGame(true);
	printNotification("Autosaved", COLOURS.green, 2000);
}

function discreetSave(){
	saveGame(true)
}

async function loadGame(newGame) {

	if(newGame){
		_tacksPerSecond = 0;
		_tacksPerClick = 1;
		_totalTacksEarned = 0;
		_seed = generateSeed();
		_tacks =  0;
		for(let i = 0; i < _owned.length; i++){
			_owned[i] = 0;
		}
		printers.subtract(_owned.printer);
		_playedTimeElapsed = 0;
		_clicks = 0;
		DATA.version = getVersion();
		// await getAchievementArray();
		_achievementsEarned = 0;
		
		DATA.theme = _lastKnownTheme || THEME.LIGHT; // Is there a way to get this to be system default?
		onRefresh();
		return;
	}

	const raw = localStorage.getItem("tacksSave");
	if (!raw) return;


	try {
		const encryptedData = JSON.parse(raw);
		const key = await getKey();
		const iv = new Uint8Array(encryptedData.iv);
		const cipherText = new Uint8Array(encryptedData.cipherText);

		const decrypted = await crypto.subtle.decrypt({ name: "AES-GCM", iv }, key, cipherText);
		const json = new TextDecoder().decode(decrypted);
		const parsed = JSON.parse(json);

		_seed = parsed.seed || generateSeed();
		_tacks = parsed.tacks || 0;
		_clicks = parsed.clicks || 0;
		_owned.pointer = parsed.pointersOwned || 0;
		_owned.worker = parsed.workersOwned || 0;
		_owned.printer = parsed.printersOwned || 0;
		_owned.dropshipper = parsed.dropshippersOwned || 0;
		_playedTimeElapsed = parsed.playedTime || 0;
		DATA.version = parsed.version || getVersion();
		_lastKnownTheme = parsed.theme || THEME.LIGHT;
		_totalTacksEarned = parsed.totalTacksEarned || 0;
		_achievements = parsed.achievements || [];
		_achievementsEarned = parsed.achievementsEarned || 0;
		await saveIntoData();
		onRefresh();
		printNotification("Game Loaded", COLOURS.green);
	} catch (err) {
		console.error("Decryption failed:", err);
	}
	
	await loadAchievements();
	
	updateLabel["pointerCost"](COST.pointer);
	updateLabel["workerCost"](COST.worker);
	updateLabel["printerCost"](COST.printer);
	// updateLabel["dropperCost"](COST.dropshipper);
}

async function saveIntoData(){
	DATA.seed = _seed;
	DATA.tacks = _tacks;
	DATA.clicks = _clicks;
	DATA.pointersOwned = _owned.pointer;
	DATA.workersOwned = _owned.worker;
	DATA.printersOwned = _owned.printer;
	// DATA.dropshippersOwned = _owned.dropshipper;
	DATA.playedTime = _playedTimeElapsed;
	DATA.theme = _lastKnownTheme;
	DATA.totalTacksEarned = _totalTacksEarned;
	DATA.achievements = await getAchievementArray();
	DATA.achievementsEarned = _achievementsEarned;

	return;
}