<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="author" content="Justas Ašmanavičius" />

        <title><?=__('DS');?></title>

        <link href='http://fonts.googleapis.com/css?family=Roboto:400,300,700&subset=latin,latin-ext,cyrillic-ext' rel='stylesheet' type='text/css'>

        <link rel="stylesheet" href="<?=config('app.css');?>/bootstrap.min.css" />
        <link rel="stylesheet" href="<?=config('app.css');?>/magnific-popup.css" />
        <link rel="stylesheet" href="<?=config('app.css');?>/font-awesome.min.css" />
        <link rel="stylesheet" href="<?=config('app.css');?>/style.css" />

        <?php if (config('app.captcha.registration') || config('app.captcha.login') || config('app.captcha.recovery')) : ?>
            <script type="text/javascript" src="http://www.google.com/recaptcha/api/js/recaptcha_ajax.js"></script>
        <?php endif; ?>

        <script>
            var gVar = [];
            gVar['base-url'] = "<?=config('app.base_url');?>";
            <?php if (Auth::isLoggedIn()) { ?>
                gVar['support-email'] = "<?=config('app.email');?>";
            <?php } ?>
            gVar['shop-pagination'] = "<?=config('app.shop.per_page');?>";
            gVar['inventory-pagination'] = "<?=config('app.inventory.per_page');?>";
            gVar['buy-confirm'] = "<?=config('app.shop.buy_confirmation');?>";
        </script>
        <?php if (config('app.mokejimai.verify_code')) : ?>
            <?=config('app.mokejimai.verify_code');?>
        <?php endif; ?>
    </head>
    <body>
        <div id="jas-content"></div>

        <div class="clearfix"></div>

        <script src="<?=config('app.js');?>/libs/jquery-1.10.2.js"></script>
        <script src="<?=config('app.js');?>/libs/bootstrap.min.js"></script>
        <script src="<?=config('app.js');?>/libs/jquery.magnific-popup.min.js"></script>
        <script src="<?=config('app.js');?>/libs/blockui.js"></script>
        <script src="<?=config('app.js');?>/libs/jquery.confirm.js"></script>

        <script src="<?=config('app.js');?>/libs/functions.js"></script>
        <script src="<?=config('app.js');?>/libs/log.js"></script>

        <script src="<?=config('app.js');?>/app.js"></script>
    </body>
</html>
