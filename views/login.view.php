<?php include ROOT_PATH . '/views/menu.inc.php'; ?>

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
                <div class="input">
                    <select name="server" class="form-control">
                        <?php foreach ($servers as $key => $server) { ?>
                            <option value="<?php echo $key; ?>"><?php echo reset($server); ?></option>
                        <?php } ?>
                    </select>
                 </div>
            <?php } ?>
            
            <?php 
                if (Settings::get('app.captcha.login'))
                    include ROOT_PATH . '/views/captcha.inc.php'; 
            ?>

            <div class="input">
                <input type="button" name="auth" class="btn btn-primary pull-right" value="<?=Language::_('Prisijungti');?>" />
            </div>
            
            <div class="clearfix"></div>
        </form>
    </div>
</div>

