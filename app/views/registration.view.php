<?php include VIEWS_PATH . '/menu.inc.php'; ?>

<div class="panel panel-default left-side">
    <div class="panel-heading"><?=__('Registration');?></div>
    <div class="panel-body">
        <div id="response"></div>

        <form id="registration-form" class="col-xs-8" style="float: none !important; margin: 0 auto;">
            <input type="hidden" name="registration" value="ok" />
            <input type="hidden" name="token" value="<?=Form::token('registration');?>" />

            <div class="input">
                <input type="text" class="form-control" name="username" placeholder="<?=__('Username');?>" />
            </div>

            <div class="input">
                <input type="password" class="form-control" name="password" placeholder="<?=__('Password');?>" />
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
                if (config('app.captcha.registration'))
                    include VIEWS_PATH . '/captcha.inc.php';
            ?>

            <div class="input">
                <input type="button" name="register" class="btn btn-primary pull-right" value="<?=__('Register');?>" />
            </div>
            <div class="clearfix"></div>
        </form>
    </div>
</div>
