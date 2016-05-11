// ---------------------------------------------------------------

var logsFilterSystems = [];
var logsFilterRegions = [];
var logsFilterUnknowns = true;
var logsFilterSuspended = false;
var logsDisplayedLatest = 0;

// ---------------------------------------------------------------

function logsToggle() {
    if ($('#map-log').hasClass('hide')) {
	$('#map').css('width', '69%');
	$('#map-heat').css('width', '69%');
	$('#map-log-btn-hidden').addClass('hide');
	$('#map-log').removeClass('hide');
    } else {
	$('#map-log').addClass('hide');
	$('#map-log-btn-hidden').removeClass('hide');
	$('#map').css('width', '98%');
	$('#map-heat').css('width', '98%');
    }

    drawResize();
}

// ---------------------------------------------------------------

function logsClear() {
    $("#logs > tr").each(function() {
	if (!$(this).hasClass("keep")) {
	$(this).remove();
	}
    });
    $('#logs .keep').removeClass("hide");
}

function logsRefresh(sound) {
    logsClear();

    var logsAddedLatest = 0;
    jQuery.each(reports, function(i, report) {
        var addIt = false;
        if (logsFilterSuspended) { 
           addIt = true;
        } else if (report['systems'].length == 0 && report['regions'].length == 0) {
	   addIt = logsFilterUnknowns;
	} else if (logsFilterSystems.length == 0 && logsFilterRegions.length == 0) {
           addIt = true;
	} else {
	    for (i in report['regions']) {
	        if (jQuery.inArray(report['regions'][i], logsFilterRegions) != -1) {
		    addIt = true;
                    break;
	        }
	    }
            if (!addIt) {
	        for (i in report['systems']) {
	            if (jQuery.inArray(report['systems'][i], logsFilterSystems) != -1) {
		        addIt = true;
                        break;
	            }
	        }
            }
        }
        if (addIt) {
            logsAddedLatest = Math.max(logsAddedLatest, report['submittedAt']);
            logsAdd(report);
        }
    });


    if (sound && logsDisplayedLatest != 0 && logsAddedLatest > logsDisplayedLatest) {
        settingsDoPlay();
    }

    logsDisplayedLatest = logsAddedLatest;
}

function logsAdd(report) {
    $('#logs .keep').addClass("hide");

    cnt = "";
    cnt += '<tr>';
    cnt += '<td class="small"><a href="#" ';
    if ( report['systems'] && report['systems'][0] ) {
        cnt += "onclick=\"systemLucky('" + report['systems'][0] + "')\" title=\"Go to " + report['systems'][0] + " on map.\"";
    }
    cnt += '><span class="ago pull-right" timestamp="' + report['submittedAt'] + '">...</span></a></td>';
    cnt += '<td class="small">';
    if ( report['regions'] && report['regions'][0] ) {
        cnt += "<a href='javascript:logsRegionsClicked([\"" + report['regions'][0] + "\"]);' title=\"Add " + report['regions'][0] + ' to filtered systems."><span class="text-muted pull-right">' + report['regions'][0].substring(0,3) + '</span></a>';
    }
    cnt += '</td>';
    cnt += '<td class="small">' + report['textInterpreted'] + ' <span class="text-muted pull-right">' + report['reporter'] + '</span></td>'
    cnt += '</tr>';
    $('#logs tr:first').after(cnt);

}

// ---------------------------------------------------------------

function logsRefreshTimestamps() {
    $(".ago").each(function( index ) {
	var ts = $(this).attr('timestamp');
	$(this).text(timestampToAgo(ts));

	var color = timestampToColor(ts);
	if (color !== false) {
	    $(this).css('color', color);
	} else {
	    $(this).css('color', '');
	}
    });
}

// ---------------------------------------------------------------

function logsFilterSystemsClear() {
    logsFilterSystemsReplace([]);
    logsFilterRegionsReplace([]);
    logsFilterUnknownsSet(true);
}

// ---------------------------------------------------------------

function logsFilterUnknownsToggle() {
    logsFilterUnknowns = !logsFilterUnknowns;
}

function logsFilterSuspendToggle() {
    logsFilterSuspended = !logsFilterSuspended;
}

function logsFilterUnknownsSet(value) {
    logsFilterUnknowns = value;
}

// ---------------------------------------------------------------

function logsFilterSystemToggle(name) {
    logsFilterSystemsToggle([name]);
}
function logsFilterSystemsToggle(names) {
    for (i in names) {
	if (jQuery.inArray(names[i], logsFilterSystems) == -1) {
	    logsFilterSystemAdd(names[i]);
	} else {
	    logsFilterSystemRemove(names[i]);
	}
    }
}


function logsFilterSystemAdd(name) {
    logsFilterSystemsAdd([name]);
}
function logsFilterSystemsAdd(names) {
    for (i in names) {
	if (jQuery.inArray(names[i], logsFilterSystems) == -1) {
	    logsFilterSystems.push(names[i]);
	}
    }
}


function logsFilterSystemReplace(name) {
	logsFilterSystemsReplace([name]);
}
function logsFilterSystemsReplace(names) {
    logsFilterSystems = [];
    logsFilterSystemsAdd(names);
}


function logsFilterSystemRemove(name) {
    logsFilterSystemsRemove([name]);
}
function logsFilterSystemsRemove(names) {
    for (i in names) {
	logsFilterSystems.splice( $.inArray(names[i], logsFilterSystems), 1 );
    }
}

// ---------------------------------------------------------------

function logsFilterRegionToggle(name) {
    logsFilterRegionsToggle([name]);
}
function logsFilterRegionsToggle(names) {
    for (i in names) {
	if (jQuery.inArray(names[i], logsFilterRegions) == -1) {
	    logsFilterRegionAdd(names[i]);
	} else {
	    logsFilterRegionRemove(names[i]);
	}
    }
}


function logsFilterRegionAdd(name) {
    logsFilterRegionsAdd([name]);
}
function logsFilterRegionsAdd(names) {
    for (i in names) {
	if (jQuery.inArray(names[i], logsFilterRegions) == -1) {
	    logsFilterRegions.push(names[i]);
	}
    }
}


function logsFilterRegionReplace(name) {
	logsFilterRegionsReplace([name]);
}
function logsFilterRegionsReplace(names) {
    logsFilterRegions = [];
    logsFilterRegionsAdd(names);
}


function logsFilterRegionRemove(name) {
    logsFilterRegionsRemove([name]);
}
function logsFilterRegionsRemove(names) {
    for (i in names) {
	logsFilterRegions.splice( $.inArray(names[i], logsFilterRegions), 1 );
    }
}

// ---------------------------------------------------------------

function logsFilterRefresh() {
    logsFilterSystems.sort();
    logsFilterRegions.sort();

    $('#filter-unknown').removeClass();
    if (logsFilterUnknowns) {
	$('#filter-unknown').addClass("btn btn-xs btn-success");
    } else {
	$('#filter-unknown').addClass("btn btn-xs btn-danger");
    }

    $('#filter-suspend').removeClass();
    if (logsFilterSuspended) {
	$('#filter-suspend').addClass("btn btn-xs btn-danger");
    } else {
	$('#filter-suspend').addClass("btn btn-xs btn-success");
    }

    $("#sysfilter button").each(function( index ) {
	$(this).remove();
    });

    for (i in logsFilterSystems) {
	cnt = '<button type="button" class="btn btn-xs btn-success" onclick="logsFilterSystemRemove(\'' + logsFilterSystems[i] + '\'); applyFilter();">' + logsFilterSystems[i] + '</button> ';
	$('#sysfilter').append(cnt);
    }

    $("#regionfilter button").each(function( index ) {
	$(this).remove();
    });

    for (i in logsFilterRegions) {
	cnt = '<button type="button" class="btn btn-xs btn-success" onclick="logsFilterRegionRemove(\'' + logsFilterRegions[i] + '\'); applyFilter();">' + logsFilterRegions[i] + '</button> ';
	$('#regionfilter').append(cnt);
    }
}

// ---------------------------------------------------------------

