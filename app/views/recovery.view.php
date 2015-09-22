<?php
    include VIEWS_PATH . '/menu.inc.php';

    if ( ! config('app.recovery.enabled'))
        exit('Recovery disabled');
?>

<div class="panel panel-default left-side">
    <div class="panel-heading"><?=__('Password recovery');?></div>
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
            <input type="hidden" name="token" value="<?=Form::token('recovery');?>" />

            <div class="input">
                <input type="text" class="form-control" name="recovery_input" placeholder="<?=__('Your e-mail or username');?>" />
            </div>

            <?php if ($servers) { ?>
                <?php if (count($servers) > 1) { ?>
                    <div class="input">
                        <select name="server" class="form-control">
                            <?php foreach ($servers as $key => $server) { ?>
                                <option value="<?php echo $key; ?>"><?php echo reset($server); ?></option>
                            <?php } ?>
                        </select>
                    </div>
                <?php } else { ?>
                    <input type="hidden" name="server" value="1" />
                <?php } ?>
            <?php } ?>

            <?php
            if (config('app.captcha.recovery'))
                include VIEWS_PATH . '/captcha.inc.php';
            ?>

            <div class="input">
                <input type="button" name="save" class="btn btn-primary pull-right" value="<?=__('Request new password');?>" />
            </div>

            <div class="clearfix"></div>
        </form>
    </div>
</div>
