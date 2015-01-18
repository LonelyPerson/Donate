<?php
    define('ROOT_PATH', dirname(__FILE__));
    include(ROOT_PATH . '/libs/helpers/load.php');
?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="verify-paysera" content="99456500740208784d3505b6bd1ccbc9" />
        <meta name="author" content="Justas Ašmanavičius" />
        
        <title>Test</title>
        
        <link rel="stylesheet" href="<?=Settings::get('app.css');?>/style.css" />
        <link rel="stylesheet" href="<?=Settings::get('app.css');?>/themes/<?=Settings::get('app.css_theme');?>.css" />
        <link rel="stylesheet" href="<?=Settings::get('app.css');?>/magnific-popup.css" />
        
        <script type="text/javascript" src="http://www.google.com/recaptcha/api/js/recaptcha_ajax.js"></script>
        
        <script>
            var gVar = [];
            <?php if (Auth::isLoggedIn()) { ?>
                gVar['support-email'] = "<?=Settings::get('app.support_email');?>";
            <?php } ?>
        </script>
    </head>
    <body>
        <div id="jas-content"></div>
        
        <div class="clearfix"></div>
        
        <script src="<?=Settings::get('app.js');?>/libs/jquery-1.10.2.js"></script>
        <script src="<?=Settings::get('app.js');?>/libs/bootstrap.min.js"></script>
        <script src="<?=Settings::get('app.js');?>/libs/jquery.magnific-popup.min.js"></script>
        <script src="<?=Settings::get('app.js');?>/libs/blockui.js"></script>
        
        <script src="<?=Settings::get('app.js');?>/libs/functions.js"></script>
        <script src="<?=Settings::get('app.js');?>/libs/log.js"></script>
        
        <script src="<?=Settings::get('app.js');?>/app.js"></script>
    </body>
</html>