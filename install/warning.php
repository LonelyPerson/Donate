<?php if ( ! defined('STARTED')) exit('error'); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="author" content="Justas Ašmanavičius" />

        <title><?=Language::_('Pranešimas');?></title>

        <link href='http://fonts.googleapis.com/css?family=Roboto:400,300,700&subset=latin,latin-ext,cyrillic-ext' rel='stylesheet' type='text/css'>

        <link rel="stylesheet" href="<?=Settings::get('app.css');?>/bootstrap.min.css" />
        <link rel="stylesheet" href="<?=Settings::get('app.css');?>/font-awesome.min.css" />
        <link rel="stylesheet" href="<?=Settings::get('app.css');?>/style.css" />

        <script src="<?=Settings::get('app.js');?>/libs/jquery-1.10.2.js"></script>
        <script src="<?=Settings::get('app.js');?>/libs/bootstrap.min.js"></script>
        <script src="<?=Settings::get('app.js');?>/libs/blockui.js"></script>

        <script src="<?=Settings::get('app.js');?>/libs/functions.js"></script>
        <script src="<?=Settings::get('app.js');?>/libs/log.js"></script>

        <script src="<?=Settings::get('app.js');?>/install.js"></script>
    </head>
    <body>
        <div id="jas-content" class="install">
            <?php include VIEWS_PATH . '/languages.inc.php'; ?>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <?=Language::_('Pranešimas');?>
                </div>
                <div class="panel-body">
                    <?=Language::_('Prašome ištrinti %s aplanką, kad galėtumėte naudotis sistema.', ['<strong>install</strong>']);?>
                </div>
            </div>
        </div>
    </body>
</html>
