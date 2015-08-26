<?php include VIEWS_PATH . '/menu.inc.php'; ?>

<div class="panel panel-default left-side">
    <div class="panel-heading"><?=Language::_('Registracija');?></div>
    <div class="panel-body">
        <div id="response"></div>

        <form id="registration-form" class="col-xs-8" style="float: none !important; margin: 0 auto;">
            <input type="hidden" name="registration" value="ok" />

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
                if (Settings::get('app.captcha.registration'))
                    include VIEWS_PATH . '/captcha.inc.php';
            ?>

            <div class="input">
                <input type="button" name="register" class="btn btn-primary pull-right" value="<?=Language::_('Registruotis');?>" />
            </div>
            <div class="clearfix"></div>
        </form>
    </div>
</div>