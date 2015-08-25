<?php include VIEWS_PATH . '/menu.inc.php'; ?>

<div class="panel panel-default left-side">
    <div class="panel-heading"><?=Language::_('Prisijungimas');?></div>
    <div class="panel-body">

    <div id="response"></div>

        <form id="login-form" class="col-xs-8" style="float: none !important; margin: 0 auto;">
            <input type="hidden" name="auth" value="ok" />

            <div class="input">
                <input type="text" class="form-control" name="username" placeholder="<?=Language::_('Slapyvardis');?>" />
            </div>

            <div class="input">
                <input type="password" class="form-control" name="password" placeholder="<?=Language::_('Slaptažodis');?>" />
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
                if (Settings::get('app.captcha.login'))
                    include VIEWS_PATH . '/captcha.inc.php';
            ?>

            <div class="input">
                <input type="button" name="auth" class="btn btn-primary pull-right" value="<?=Language::_('Prisijungti');?>" />
                <?php if (Settings::get('app.recovery.enabled')) { ?>
                    <a href="javacript: void(0);" class="pull-right" style="margin-top: 8px; margin-right: 10px;" onclick="loadView('recovery'); return false;"><?=Language::_('Pamiršai slaptažodį?');?></a>
                <?php } ?>
            </div>

            <div class="clearfix"></div>
        </form>
    </div>
</div>
