<?php include ROOT_PATH . '/views/user_menu.inc.php'; ?>

<div class="panel panel-default left-side">
    <div class="panel-heading"><?=Language::_('Nustatymai');?></div>
    <div class="panel-body">
        <div id="response"></div>

        <?php if (Settings::get('app.settings.enabled')) { ?>
            <form id="settings-form">
                <input type="hidden" name="settings_save" value="ok" />
                
                <div class="form-group">
                    <input type="text" name="email" class="form-control" placeholder="<?=Language::_('El. pašto adresas');?>" value="<?=Auth::user()->email;?>" />
                </div>

                <div class="form-group">
                    <input type="password" name="old_password" class="form-control" placeholder="<?=Language::_('Dabartinis slaptažodis');?>" />
                </div>

                <div class="form-group">
                    <input type="password" name="new_password" class="form-control" placeholder="<?=Language::_('Naujas slaptažodis');?>" />
                </div>

                <div class="input">
                    <input type="button" name="save" class="btn btn-primary" value="<?=Language::_('Išsaugoti');?>" />
                </div>
            </form>

        <?php } ?>
    </div>
</div>