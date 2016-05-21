<?php if (!defined('INTEL')) die('go away'); ?>

<!-- Map -->
    <div id="map-heat" style="position:absolute; top:60px; bottom: 5px; left:10px; width: 69%; z-index: -3;">
	<canvas id="canvas-heat"></canvas>
    </div>

    <div id="map-heat-legend" style="position:absolute; bottom:5px; left:10px; width: 114px; height: 29px; z-index: -2;">
	<canvas id="canvas-heat-legend" width="110" height="25"></canvas>
    </div>

    <div id="map" style="position:absolute; top:60px; bottom:5px; left:10px; width:69%; z-index: -1;">
	<img id="marker" src="img/marker.png" style="position:absolute; top:10px; left:10px; z-index: 23; opacity:0;;">
	<canvas id="canvas"></canvas>
    </div>
<!-- Map -->

<!-- Logs -->
    <button id="map-log-btn-hidden" type="button" class="btn btn-default btn-xs hide" onclick="logsToggle();" style="position:absolute; right: 5px; top: 60px; z-index: 42;"><span class="glyphicon glyphicon-resize-small" aria-hidden="true"></span></button>
    <div id="map-log" style="position:absolute; top:60px; bottom:0px; left:71%; right:0px; padding-right:5px; overflow-y: scroll; overflow-x: auto; z-index:-1;">
	<p style="line-height:200%;">
	    <span class="pull-right"><button type="button" class="btn btn-default btn-xs" onclick="logsToggle();"><span class="glyphicon glyphicon-resize-full" aria-hidden="true"></button></span>
	    <button type="button" id="filter-clear" class="btn btn-default btn-xs" onclick="logsFilterSystemsClear(); applyFilter(); blur(); return false;" data-toggle="tooltip" data-placement="right" title="Reset all filters."><span class="glyphicon glyphicon-retweet" aria-hidden="true"></span></button>
	    <button type="button" id="filter-suspend" class="btn btn-default btn-xs" onclick="logsFilterSuspendToggle(); applyFilter(); blur(); return false;" data-toggle="tooltip" data-placement="right" title="Enable / Disable filtering."><span class="glyphicon glyphicon-filter" aria-hidden="true"></span></button>
	    <button type="button" id="filter-unknown" class="btn btn-default btn-xs" onclick="logsFilterUnknownsToggle(); applyFilter(); blur(); return false;" data-toggle="tooltip" data-placement="right" title="Filter out messages with no 'intel'.">Unknown</button>
	    <span id="regionfilter"></span>
	    <span id="sysfilter"></span>
	</p>

	<table class="table table-condensed table-striped">
	    <thead>
		<tr>
		    <th>Time</th>
                    <th></th>
		    <th>Intel <span class="text-muted pull-right">Reporter</span></th>
		</tr>
	    </thead>
	    <tbody id="logs">
		<tr class="keep"><td colspan="2" class="small">No intel matching your criteria ...</td></tr>
	    </tbody>
	</table>
    </div>
<!-- Logs -->

<!-- Popups -->
      <div class="hide" id="popsys" style="min-width:120px"  onclick="$('#popsys').addClass('hide')">
        <table class="table table-condensed" style='border: 2px solid black;'>
          <thead>
            <tr>
              <th style='border: 2px solid black; text-align:center;'>
		    <span id="popsys-name" style="font-size: 120%;">&nbsp;</span>
              </th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td style='border: 2px solid black;' id="popsys-content">&nbsp;</td>
            </tr>
          </tbody>
        </table>
      </div>
<!-- Popups -->
