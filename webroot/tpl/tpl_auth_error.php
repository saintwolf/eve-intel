<?php if (!defined('INTEL')) die('go away'); ?>

<!-- Auth -->
    <div class="jumbotron">
	<div class="container">
	    <h1>Authentication Error</h1>
	    <br>
            <?php if ( 'eve_sso' == $cfg_auth_type ): ?>
            <p><a href="<?php echo $cfg_url_auth_init; ?>"><img src="https://images.contentful.com/idjq7aai9ylm/4PTzeiAshqiM8osU2giO0Y/5cc4cb60bac52422da2e45db87b6819c/EVE_SSO_Login_Buttons_Large_White.png" alt="EVE SSO"></a></p>
            <?php else: ?>
	    <p><a href="<?php echo $cfg_url_auth_init; ?>" class="btn btn-primary btn-lg" role="button">Authenticate &raquo;</a></p>
            <?php endif ?>
	</div>
    </div>
<!-- Auth -->
