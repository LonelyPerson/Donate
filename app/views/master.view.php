<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="author" content="Justas Ašmanavičius" />

        <title><?=Language::_('Donate sistema');?></title>

        <link href='http://fonts.googleapis.com/css?family=Roboto:400,300,700&subset=latin,latin-ext,cyrillic-ext' rel='stylesheet' type='text/css'>

        <link rel="stylesheet" href="<?=Settings::get('app.css');?>/bootstrap.min.css" />
        <link rel="stylesheet" href="<?=Settings::get('app.css');?>/magnific-popup.css" />
        <link rel="stylesheet" href="<?=Settings::get('app.css');?>/font-awesome.min.css" />
        <link rel="stylesheet" href="<?=Settings::get('app.css');?>/style.css" />

        <?php if (Settings::get('app.captcha.registration')) : ?>
            <script type="text/javascript" src="http://www.google.com/recaptcha/api/js/recaptcha_ajax.js"></script>
        <?php endif; ?>

        <script>
            var gVar = [];
            gVar['base-url'] = "<?=Settings::get('app.base_url');?>";
            <?php if (Auth::isLoggedIn()) { ?>
                gVar['support-email'] = "<?=Settings::get('app.email');?>";
            <?php } ?>
            gVar['shop-pagination'] = "<?=Settings::get('app.shop.per_page');?>";
            gVar['buy-confirm'] = "<?=Settings::get('app.shop.buy_confirmation');?>";
        </script>
        <?php if (Settings::get('app.mokejimai.verify_code')) : ?>
            <?=Settings::get('app.mokejimai.verify_code');?>
        <?php endif; ?>
    </head>
    <body>
        <div id="jas-content"></div>

        <div class="clearfix"></div>

        <script src="<?=Settings::get('app.js');?>/libs/jquery-1.10.2.js"></script>
        <script src="<?=Settings::get('app.js');?>/libs/bootstrap.min.js"></script>
        <script src="<?=Settings::get('app.js');?>/libs/jquery.magnific-popup.min.js"></script>
        <script src="<?=Settings::get('app.js');?>/libs/blockui.js"></script>
        <script src="<?=Settings::get('app.js');?>/libs/jquery.confirm.js"></script>

        <script src="<?=Settings::get('app.js');?>/libs/functions.js"></script>
        <script src="<?=Settings::get('app.js');?>/libs/log.js"></script>

        <script src="<?=Settings::get('app.js');?>/app.js"></script>
    </body>
</html>
