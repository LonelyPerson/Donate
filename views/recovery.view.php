<?php
    include ROOT_PATH . '/views/menu.inc.php';

    if ( ! Settings::get('app.recovery.enabled'))
        exit('Recovery disabled');
?>

<div class="panel panel-default left-side">
    <div class="panel-heading"><?=Language::_('Slaptažodžio atstatymas');?></div>
    <div class="panel-body">

        <div id="response"></div>

        <?php if (Session::has('recovery-verified')) { ?>
            <div class="alert alert-info"><?=Session::pull('recovery-verified');?></div>
        <?php } ?>

        <?php if (Session::has('recovery-not-verified')) { ?>
            <div class="alert alert-danger"><?=Session::pull('recovery-not-verified');?></div>
        <?php } ?>

        <form id="recovery-form" class="col-xs-8" style="float: none !important; margin: 0 auto;">
            <input type="hidden" name="recovery" value="ok" />

            <div class="input">
                <input type="text" class="form-control" name="recovery_input" placeholder="<?=Language::_('El. pašto adresas arba slapyvardis');?>" />
            </div>

            <?php if ($servers) { ?>
                <div class="input">
                    <select name="server" class="form-control">
                        <?php foreach ($servers as $key => $server) { ?>
                            <option value="<?php echo $key; ?>"><?php echo reset($server); ?></option>
                        <?php } ?>
                    </select>
                </div>
            <?php } ?>

            <?php
            if (Settings::get('app.captcha.recovery'))
                include ROOT_PATH . '/views/captcha.inc.php';
            ?>

            <div class="input">
                <input type="button" name="save" class="btn btn-primary pull-right" value="<?=Language::_('Gauti naują slaptažodį');?>" />
            </div>

            <div class="clearfix"></div>
        </form>
    </div>
</div>

