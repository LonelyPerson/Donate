<?php if ( ! Auth::isLoggedIn() || ! Admin::hasAccess()) exit('error'); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="author" content="Justas Ašmanavičius" />

        <title>DS administration</title>

        <link href='http://fonts.googleapis.com/css?family=Roboto:400,300,700&subset=latin,latin-ext,cyrillic-ext' rel='stylesheet' type='text/css'>

        <link rel="stylesheet" href="<?=config('app.css');?>/bootstrap.min.css" />
        <link rel="stylesheet" href="<?=config('app.css');?>/magnific-popup.css" />
        <link rel="stylesheet" href="<?=config('app.css');?>/font-awesome.min.css" />
        <link rel="stylesheet" href="<?=config('app.css');?>/admin_style.css" />

        <script>
            var gVar = [];
            gVar['base-url'] = "<?=config('app.base_url');?>";
            gVar['base-admin-url'] = "<?=config('app.base_url') . '/admin';?>";
        </script>
    </head>
    <body>
        <div id="jas-content-admin">
            <div class="container">
                <div class="<?=(Admin::isHome()) ? 'col-md-12' : 'col-md-9';?>">
                    <?=$content;?>

                    <div style="margin-top: -10px; font-size: 10px; text-transform: uppercase;">Author: <a href="http://justas.asmanavicius.lt" target="_blank">Justas Ašmanavičius</a></div>
                </div>

                <?php include 'side_menu.inc.php'; ?>

                <div class="clearfix"></div>
            </div>
        </div>

        <div class="clearfix"></div>

        <script src="<?=config('app.js');?>/libs/jquery-1.10.2.js"></script>
        <script src="<?=config('app.js');?>/libs/bootstrap.min.js"></script>
        <script src="<?=config('app.js');?>/libs/functions.js"></script>
        <script src="<?=config('app.js');?>/admin.js"></script>
    </body>
</html>
