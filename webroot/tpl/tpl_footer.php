<?php if (!defined('INTEL')) die('go away'); ?>

    <script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script>
    <!-- script src="js/jquery.cookie.js"></script -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jquery-cookie/1.4.1/jquery.cookie.js"></script>
    <script src="//netdna.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js"></script>

<?php
    foreach ($pScripts as $file) {
	echo "  <script src='" . $file . "'></script>\n";
    }
?>

    <script type="text/javascript">
    jQuery(document).ready(function() {
        // Enable all tooltips
        jQuery('[data-toggle="tooltip"]').tooltip();
    });
    </script>

  </body>

</html>
