<?php if ( ! defined('STARTED')) exit('error'); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="author" content="Justas Ašmanavičius" />

        <title><?=Language::_('Sistemos diegimas');?></title>

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

        <script>
            var gVar = [];
            gVar['base-url'] = "<?=Settings::get('app.base_url');?>";
        </script>
    </head>
    <body>
        <div id="jas-content" class="install">
            <?php include VIEWS_PATH . '/languages.inc.php'; ?>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <?=Language::_('Sistemos diegimas');?>
                </div>
                <div class="panel-body">
                    <p class="description">
                        <?=Language::_('Prieš įdiegiant sistemą reikia atlikti keletą patikrinimų, kad įdiegimas būtų sėkmingas. Patikrinimą pradėkite tik tada kai užpildysite duomenų bazės nustatymus.
                        Nepamirškite ištrinti <strong>install</strong> aplanko po sėkmingo sistemos įdiegimo.', ['<strong>install</strong>'])?>
                        <br />

                        <h4><?=Language::_('Nustatymai');?></h4>
                        <strong>app/config/app.php</strong> - <?=Language::_('visi pagrindiniai nustatymai');?><br />
                        <strong>app/config/database.php</strong> - <?=Language::_('duomenų bazės nustatymai');?><br />
                        <strong>app/config/server.php</strong> - <?=Language::_('serverių nustatymai');?><br />
                        <strong>app/config/sql.php</strong> - <?=Language::_('serverių duom. bazių stulpelių pavadinimai');?><br />
                        <br />
                        <strong>app/config/xml/languages</strong> - <?=Language::_('kalbų failai');?><br />
                        <strong>app/config/xml/paysera-sms.xml</strong> - <?=Language::_('paysera.com sms raktažodžiai');?><br />
                        <strong>app/config/xml/shop.xml</strong> - <?=Language::_('parduotuvės daiktai');?>
                    </p>

                    <h4><?=Language::_('Patikrinimai');?></h4>
                    <div class="check">
                        <div class="db-status" style="display: none">0</div>
                        <div class="chmod-status" style="display: none">0</div>

                        <p class="req db-connection">
                            <strong><?=Language::_('Prisijungimas prie MySQL');?>:</strong>
                            <span class="loader"><?=Language::_('tikrinama');?> <i class="fa fa-cog fa-spin"></i></span>
                            <span class="yes" style="display: none"><?=Language::_('pavyko');?> <i class="fa fa-check"></i></span>
                            <span class="no" style="display: none"><?=Language::_('nepavyko');?></span>
                        </p>
                        <p class="req storage-chmod">
                            <strong><?=Language::_('Įrašymo teisės %s aplankui', ['"app/storage"']);?>:</strong>
                            <span class="loader"><?=Language::_('tikrinama');?> <i class="fa fa-cog fa-spin"></i></span>
                            <span class="yes" style="display: none;"><?=Language::_('suteiktos');?> <i class="fa fa-check"></i></span>
                            <span class="no" style="display: none;"><?=Language::_('nesuteiktos');?></span>
                        </p>
                    </div>
                    <a href="javascript: void(0)" id="check" class="btn btn-primary"><?=Language::_('Pradėti patikrinimą');?></a>

                    <div class="install-progress" style="display: none">
                        <h4><?=Language::_('Diegimas');?></h4>
                        <p class="wait"><?=Language::_('Prašome palaukti, sistema diegiama');?> <i class="fa fa-cog fa-spin"></i></p>
                        <p class="success" style="display: none"><?=Language::_('Sistema sėkmingai įdiegta');?> <i class="fa fa-check"></i></p>
                    </div>

                    <div class="nav-buttons" style="display: none">
                        <a href="javascript: void(0)" class="btn btn-primary" id="install"><?=Language::_('Įdiegti');?></a>
                        <a href="<?=Settings::get('app.base_url');?>" class="btn btn-default" id="end" style="display: none"><?=Language::_('Baigti');?></a>
                    </div>

                    <div class="clearfix"></div>
                </div>
            </div>

            <div class="clearfix"></div>
        </div>
    </body>
</html>
