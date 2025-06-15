// #region Tackonomics

function canAfford(price){ return true ? price <= parseInt(_tacks, 10) : false; }

function getPrice(type){ return COST[type]; }

function buy(type, amount = 1, cheat = false){
	let price = cheat? 0 : getPrice(type);
	if(canAfford(price)){
		subtractTacks(price);
		inflatePrice(type);
		create(type, amount);
		printNotification("Bought: " + amount + "x " + type, COLOURS.text, 2000);
		return true;
	}
	else{
		printNotification("Can't afford another " + type, COLOURS.red, 2000);
		return false;
	}
}

function inflatePrice(asset){
	if(COST.hasOwnProperty(asset)){
		COST[asset] *= 1.2;
		COST[asset] = Math.floor(COST[asset]);
		updateLabel[asset + "Cost"](COST[asset]);
		
	}else{
		console.warn(`Property ${asset} doesn't exist.`);
	}
}

function catchUpInflation(){
	
	for(const asset in COST){
		const rounds = DATA[asset + "sOwned"];
		for(i = 0; i < rounds; i++){
			inflatePrice(asset);
		}	
		//console.log(`Inflated ${asset} ${rounds} times.`);
	}
}

// #endregion