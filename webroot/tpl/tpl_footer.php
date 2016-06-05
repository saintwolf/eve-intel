<?php if (!defined('INTEL')) die('go away'); ?>

    <!--script src="https://ajax.googleapis.com/ajax/libs/jquery/2.2.2/jquery.min.js"></script-->
    <script src="https://ajax.googleapis.com/ajax/libs/jquery/1.12.3/jquery.min.js"></script>
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
    function getSelectionText() {
        var text = "";
        if (window.getSelection) {
            text = window.getSelection().toString();
        } else if (document.selection && document.selection.type != "Control") {
            text = document.selection.createRange().text;
        }
        return text;
    }
    var ipwin;
    function keyOpenUrl(fmt) {
        var text = getSelectionText();
        if (text) {
            var ipwin = window.open(fmt.replace(/%s/g, text.trim().replace(/ /g, '+')), 'intel-press');
            ipwin.focus();
        }
    }
    function keyOpenUrlWithCharId(fmt) {
        var text = getSelectionText();
        if (text) {
            // doesn't focus from jquery callback lambda, hack keep a reference and raise it.
            if (ipwin)
                ipwin.focus();
            $.get( "https://api.eveonline.com/eve/CharacterID.xml.aspx", { names: text } )
                .done(function( data ) {
                    $cid = $(data).find('row').attr('characterID');
                    if ($cid) {
                        ipwin = window.open(fmt.replace(/%s/g, $cid), 'intel-press');
                        ipwin.focus();
                    } else {
                        window.focus();
                    }
            });
        }
    }
    $(document).keypress(function(e) {
        if ($(document.activeElement).is(":input,[contenteditable]"))
            return;
        if (e.which === 119) { // w
            keyOpenUrl('http://evewho.com/pilot/%s');
        } else if (e.which === 100) {  // d
            keyOpenUrl('http://evemaps.dotlan.net/search?q=%s');
        } else if (e.which === 122) {  // z
            keyOpenUrlWithCharId('https://zkillboard.com/character/%s');
        }
    });
    </script>

  </body>

</html>
