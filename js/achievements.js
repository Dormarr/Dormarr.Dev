// #region Achievements


// Add this to the save data. Maybe minimise storage with a binstring, you know what I mean.
const achievementsUnlocked = {
	'firstTack': false,
	'unskilledLabour': false,
	'daysWork': false,
	'eatTheRich': false,
	'million': false,
	'thousandClicks' : false,
	'handOfGod': false,
	'capitalistUtopia': false,
	'passiveIncome': false,
	'homeProduction': false,
}

let milestones = [];

setInterval(getAchievementArray, 2500);

// Reorder milestones to be in order of achievement (on average).
// When it displays in order, I want it to make chronological sense.

function checkAchievements(){
	milestones = [
		{ id: 'firstTack', condition: _tacks >= 1, label: 'The First Tack', description: 'Recieve your first tack.'},
		{ id: 'unskilledLabour', condition: _workersOwned >= 1, label: 'Buy a mans time', description: 'Buy a worker.'},
		{ id: 'daysWork', condition: _playedTimeElapsed > 86400, label: 'All in a days work', description: 'Play for 24 hours.'},
		{ id: 'eatTheRich', condition: _tacks >= 1000000000, label: 'Eat the rich', description: 'Reach 1 billion tacks.'},
		{ id: 'million', condition: _tacks >= 1000000, label: 'A cool mil', description: 'Reach 1 million tacks.'},
		{ id: 'thousandClicks', condition: _clicks >= 1000, label: 'Obsessive Clicker', description: 'Click 1000 times.'},
		{ id: 'handOfGod', condition: _tacksPerClick >= 1000, label: 'Hand of God', description: '1000 tacks per click.'},
		{ id: 'capitalistUtopia', condition: GetMaxCost() >= Infinity, label: 'Capitalist Utopia', description: 'Reach infinite cost on product.'},
		{ id: 'passiveIncome', condition: _tacksPerSecond >= 1000, label: 'Passive Income', description: '1000 tacks per second.'},
		{ id: 'homeProduction', condition: _printersOwned >= 1, label: 'Home Production', description: 'Buy a 3D printer.'},
	]
	
	milestones.forEach(m => {
		if(m.condition && !achievementsUnlocked[m.id]){
			achievementsUnlocked[m.id] = true;
			printNotification('Achievement: ' + m.label, COLOURS.green, 7000, m.description);
			console.log("Achievement Unlocked: " + m.label);
			discreetSave();
		}
	})
}

function resetAchievements(){
	for(let a in achievementsUnlocked){
		achievementsUnlocked[a] = false;
	}
	_achievementsEarned = 0;
	_achievements = 0;
	DATA.achievements = 0;
	console.log('Achievements Reset');
}

async function getAchievementArray(){
	let ar = [];
	for(const [key, value] of Object.entries(achievementsUnlocked)){
		let num = value ? 1 : 0;
		ar.push(num);
	}
	// console.log(`Achievement Array: ${ar}.`);
	_achievementsEarned = ar.reduce((p, a) => p + a, 0);

	return ar;
}

async function loadAchievements(){
	console.log("DATA.achievements before loading:", DATA.achievements);

	const bits = DATA.achievements;
	const keys = Object.keys(achievementsUnlocked);

	for (let i = 0; i < keys.length; i++) {
		const key = keys[i];
		achievementsUnlocked[key] = returnBoolFromBinString(bits[i]);
	}
	// console.log(`Loaded Achievements:`, achievementsUnlocked);
}

function returnBoolFromBinString(bit) {
	return bit === 1 || bit === '1';
}

function GetMaxCost(){
	let c = 0;
	for(const _c in COST){
		_c > c ? c = _c : c = c;

	}
	return c;
}

// #endregion