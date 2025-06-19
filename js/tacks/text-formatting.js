
function getDateArray(){
	const cd = new Date();
	var y = cd.getFullYear();
	var m = cd.getMonth() + 1; //zero indexed
	if(m <= 9) m = "0" + m; //add zero to front of month (9 -> 09)
	var d = cd.getDate();
	return (`${d}/${m}/${y}`);
}

function getVersionDate(){
	// This stopped working and I don't know why but I have a new version below.
	// var date = getDateArray();
	// const re = /^(3[01]|[12][0-9]|0?[1-9])(\/|-)(1[0-2]|0[1-9])\2([0-9]{2})?[0-9]{2}$/;
	// var r = date.toString(re);
	// return (`${r[8]}${r[9]}.${r[3]}${r[4]}`);


    const date = new Date();
    const year = date.getFullYear().toString().slice(2);
    const month = (date.getMonth() + 1).toString().padStart(2, '0');
    return `${year}.${month}`;
}

function setCopyright(){
	const ra = "© Dormarr ";
	let y = new Date().getFullYear();
	updateLabel["copyright"](ra + romanize(y));
}

// taken from https://blog.stevenlevithan.com/archives/javascript-roman-numeral-converter
function romanize(num) {
  var lookup = {M:1000,CM:900,D:500,CD:400,C:100,XC:90,L:50,XL:40,X:10,IX:9,V:5,IV:4,I:1},roman = '',i;
  for ( i in lookup ) {
    while ( num >= lookup[i] ) {
      roman += i;
      num -= lookup[i];
    }
  }
  return roman;
}

function getVersion(){
	var V = `v${MAJOR}.${MINOR}.${BUILD}${REVISION}-${VERSION_DATE}`;
	if(!V) return "Error fetching Version";
	return V;
}

function convertTime(timeIn, detail = 3){
	// This needs fixing, it's going up to like 100 minutes.
	// I don't know why? Am I being stupid?
	const hours = Math.floor(timeIn / 3600);
	const minutes = Math.floor((timeIn % 36000) / 60 % 60);
	const seconds = Math.floor(timeIn % 60);
	
	var str = "uh oh";
	var bits = [];

	if(detail >= 1) bits.push(hours + " hour" + ((hours==1)?"":"s"));
	if(detail >= 2) bits.push(minutes + " minute" + ((minutes==1)?"":"s"));
	if(detail >= 3) bits.push(seconds + " second" + ((seconds==1)?"":"s"));
	
	str = bits.join(', ');
	return str;
}

// I need a new version of this.
function numberWithCommas(x) {
    x = x.toString();
    var pattern = /(-?\d+)(\d{3})/;
    while (pattern.test(x))
        x = x.replace(pattern, "$1,$2");
    return x;
}

function generateFactoryName(){
	// randomly choose 2 (or 3?) words from lists that create a dumb name.
	var str = '';
	var list=[];
	// First name
	list.push(choose(['Something', 'Another', 'Cool']));

	// Second
	list.push(choose(['Something', 'Another', 'Cool']));

	// Third
	list.push(choose(['Inc.', 'Co.', 'Ltd.']));

	str = list.join(' ');

	return str;
	
}

function generateWorkerName(){
	// randomly choose a name from a list.
	return "Winston";
}

function generateDropshipName(){
	return "Lametown Express";
	// Maybe? Choose random words to create a super cringe only shop name. 
}

function generateNewsHeadlines(){
	return "Extra Extra! Something about tacks!";
	// It's like a staple of the genre. I have a ticker so I may as well.
}

function getNewTicker(){
	var list=[];

	// Will always play a generic one on refresh because values aren't always set.
	list.push(choose([
		`<q>Without thumbs, we are nothing. Without tacks, we are even less.</q><sig>  -Unknown</sig>`,
		`<q>Give me a place to stand and a thumb tack long enough, and I will pin the world.</q><sig>  -Archimedes</sig>`,
		`<q>I think, therefore I tack.</q><sig>  -Rene Descartes</sig>`,
		`<q>Float like a butterfly, sting like a tack.</q><sig>  -Muhammad Ali</sig>`,
		`<q>Judge an idea by the sharpness of the tack, not the stickiness of the tape.</q><sig>  -Korean Elvis</sig>`,
		`<q>You miss 100% of the tacks you don't pin.</q><sig>  -Wayne Gretzky</sig>`,
		`<q>He who would move the world should first tack his own board.</q><sig>  -Socrates</sig>`,
		`<q>Man is condemned to be tacked.</q><sig>  -Jean-Paul Sartre</sig>`,
		`<q>The unexamined tack is not worth pinning.</q><sig>  -Socrates</sig>`,
		`<q>I tack, therefore I conquer.</q><sig>  -Rene Descartes</sig>`,
		`<q>What is a tack but a will to power.</q><sig>  -Friedrich Nietzsche</sig>`,
		`<q>A single tack can change the fate of nations</q><sig>  -Unknown</sign>`,
		`<q>The only certainties in life are death and tacks</q><sig>  -Unknown</sign>`,
		`<q>The supreme art of war is to pin the enemy without a fight</q><sig>  -Sun Tzu [The Art of A Tack]</sig>`,
		`<q>Even a thumb tack, perfectly placed, casts a long shadow.</q><sig>  -Laozi</sig>`,
		`<q>To be is to be pinned.</q><sig>  -George Berkeley</sig>`,
		`<q>All that is tacked melts into air.</q><sig>  -Karl Marx</sig>`,
		`<q>We are but fleeting notes, pinned briefly in the corkboard of time.</q><sig>  -Marcus Aurelius</sig>`,
		`<q>Freedom is the space between the pins.</q><sig>  -Camus</sig>`,
		`<q>Buffalo</q><sig>  -CMH</sig>`,
		`<p>The system doesn't work.</p>`,
	]));
	
	if(_owned.printer > 10 & Math.random() < 0.1){ list.push(choose([
		`<q>Reap the tacks of a pointed finger.</q><sig>  -Unknown</sig>`,
		`<q>Two hands do less work than ten fingers</q><sig>  -Unknown</sig>`
	]));}
	
	if(_owned.worker > 10 & Math.random() < 0.1){ list.push(choose([
		`<p class="ticker">NEWS : Workers flocking to growing markets.</p>`,
		`<p class="ticker">NEWS : Sellotape factory set ablaze.</p>`,
	]));}

	if(_totalTacksEarned > 1000000 & Math.random() < 0.1){ list.push(choose([
		`<p class="ticket">That's a lot of tacks bro.</p>`
	]));}

	

	return choose(list);
}