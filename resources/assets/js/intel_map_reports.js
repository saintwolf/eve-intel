// ---------------------------------------------------------------

var reports = [];
var reportsLatest = {};

// ---------------------------------------------------------------

$(document).ready(function() {
	setInterval(function() {
		console.log("Timer: clean reports");
		reportsClean();
		applyData(false);
	}, 60000);
});

// ---------------------------------------------------------------

function reportsAdd(incoming) {
	if (incoming.length == 0) { return; }

	var latest = reports.slice(Math.max(reports.length - 10, 0));

	for (i in incoming) {
		var commit = true;

		for (l in latest) {
			if (incoming[i].textHash == latest[l].textHash && incoming[i].submittedAt == latest[l].submittedAt) {
				commit = false;	} }

		if (commit == true) {
			reports.push(incoming[i]);
			reportsLatestUpdate(incoming[i]);
			applyData(true); } }

}

// ---------------------------------------------------------------

function reportsLatestUpdate(report) {
	submitted = report['submittedAt'];
	for (i in report['systems']) {
		system = report['systems'][i];
		reportsLatest[system] = submitted;
		if (timestampToAgo(submitted) == "new") {
			drawDivBlink("blink-" + system); } }
}

function reportsLatestGet(system) {
	var last = reportsLatest[system];
	if (last == undefined) {
		return 0; }
	return last;
}

// ---------------------------------------------------------------

function reportsClean() {
	var now = new Date().getTime();
	i = reports.length;
	while (i--) {
		if (now - reports[i]['submittedAt'] < 1000 * 60 * 90) {
			continue; }
		reports.splice(i, 1); }
}

// ---------------------------------------------------------------
