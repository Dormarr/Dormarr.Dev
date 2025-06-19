// #region Tackonomics

function canAfford(price){ return true ? price <= parseInt(_tacks, 10) : false; }

function getPrice(type){ return COST[type]; }

function buy(type, amount = 1, cheat = false){
	let price = cheat? 0 : getPrice(type);
	if(canAfford(price)){
		subtractTacks(price);
		inflatePrice(type, amount);
		create(type, amount);
		_owned[type] += amount;
		printNotification("Bought: " + amount + "x " + type, COLOURS.text, 2000);
		return true;
	}
	else{
		printNotification("Can't afford another " + type, COLOURS.red, 2000);
		return false;
	}
}

function inflatePrice(asset, amount){
	if(COST.hasOwnProperty(asset)){
		
		for(let i = 0; i < amount; i++){
			COST[asset] *= 1.2;
			COST[asset] = Math.floor(COST[asset]);
		}
		
	}else{
		console.warn(`Property ${asset} doesn't exist.`);
	}
}

function catchUpInflation(){
	
	for(const asset in COST){
		const rounds = _owned[asset];
		inflatePrice(asset, rounds);
		// console.log(`Inflated ${asset} ${rounds} times.`);
		// updateLabel[asset + "Cost"](COST[asset]);
	}
}

function resetPrices(){
	COST.pointer = DEFAULT_COST.pointer;
	COST.worker = DEFAULT_COST.worker;
	COST.printer = DEFAULT_COST.printer;
}

// #endregion