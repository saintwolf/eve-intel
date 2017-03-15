<?php if (!defined('INTEL')) die('go away'); ?>

<?php
function addMenu($act, $id, $link, $name, $c) {
    echo "<li";
    if ($act == $id) {
	echo " class='" . $c . "'";
  }
    echo "><a href='" . $link . "'>" . $name . "</a></li>";
}
?>

<!-- Navigation -->
    <div class="navbar navbar-default navbar-fixed-top" role="navigation">
	<div class="navbar-header">
	    <button type="button" class="navbar-toggle" data-toggle="collapse" data-target=".navbar-default-collapse">
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
		<span class="icon-bar"></span>
	    </button>
	    <a class="navbar-brand" href="<?=$cfg_url_base?>"><?=$cfg_header_img_html?></a>
	</div>

	<div class="navbar-collapse collapse navbar-default-collapse navbar-right">
	    <ul class="nav navbar-nav">

<?php if ($active == 'map'): ?>
		<?php require('tpl_nav_map.php'); ?>
		<li class="dropdown">
		    <a href="#" class="dropdown-toggle" data-toggle="dropdown">More <span class="caret"></span></a>
		    <ul class="dropdown-menu">
<?php endif; ?>
<?php if ($active != 'map'): ?>
			<?php addMenu($active, "map", $cfg_url_base, "Map", "disabled"); ?>
<?php endif; ?>
			<?php addMenu($active, "bridges", "?nav=bridges", "Bridges", "disabled"); ?>
			<?php addMenu($active, "uploader", "?nav=uploader", "Uploader", "disabled"); ?>
			<?php addMenu($active, "help", "?nav=help", "Help", "disabled"); ?>

<?php if ($active != 'map'): ?>
		<li class="dropdown">
		    <a href="#" class="dropdown-toggle" data-toggle="dropdown"><?=$name?><span class="caret"></span></a>
		    <ul class="dropdown-menu">
<?php endif; ?>

			<?php addMenu($active, "settings", "?nav=settings", '<span class="glyphicon glyphicon-user" aria-hidden="true"></span> My Settings', "disabled"); ?>
			<li><a href="#" onclick="$.cookie('<?=$cfg_cookie_name?>', null, { path: '/' }); location.reload(true);"><span class="glyphicon glyphicon-log-out" aria-hidden="true"></span> Logout</a></li>
		    </ul>
		</li>
	    </ul>
	</div>
    </div>
<!-- Navigation -->

<!-- Alert Settings -->
    <div id="alert-settings" class="alert alert-dismissable alert-warning hide" style="z-index:42;">
	<button type="button" class="close" data-dismiss="alert">&times;</button>
	<h4>Warning!</h4>
	<p>I had problems accessing your custom settings. You can ignore this and continue your business, but it is highly recommended to resolve this by <a href="">reloading the page</a>.</p>
    </div>
<!-- Alert Settings -->
