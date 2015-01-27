<?php include ROOT_PATH . '/views/user_menu.inc.php'; ?>

<div class="panel panel-default left-side">
    <div class="panel-heading"><?=Language::_('Nustatymai');?></div>
    <div class="panel-body">
        <div id="response"></div>

        <?php if (Settings::get('app.settings.enabled')) { ?>
            <form id="settings-form">
                <input type="hidden" name="settings_save" value="ok" />
                
                <div class="form-group">
<<<<<<< HEAD
                    <div class="input-group email-group">
                        <input type="text" name="email" class="form-control" placeholder="<?=Language::_('El. pašto adresas');?>" <?=(Auth::user()->email_status == 2) ? 'disabled="disabled"' : '';?> value="<?=Auth::user()->email;?>" />
                        
                        <?php if ( ! Auth::user()->email && Auth::user()->email_status != 2) { ?>
                            <span 
                                class="input-group-addon">
                                <span 
                                    data-toggle="tooltip" 
                                    data-placement="top" 
                                    title="<?=Language::_('Nenurodytas el. pašto adresas');?>" 
                                    class="glyphicon glyphicon-remove" 
                                    aria-hidden="true"></span>
                            </span>
                        <?php } else { ?>
                            <span 
                                class="input-group-addon <?=(Auth::user()->email_status != 1) ? 'email-not-verified' : 'email-verified';?>">
                                <span 
                                    data-toggle="tooltip" 
                                    data-placement="top" 
                                    title="<?=(Auth::user()->email_status != 1) ? Language::_('El. pašto adresas laukia patvirtinimo') : Language::_('El. pašto adresas patvirtintas');?>" 
                                    class="glyphicon <?=(Auth::user()->email_status != 1) ? 'glyphicon-time' : 'glyphicon-ok';?>" 
                                    aria-hidden="true"></span>
                            </span>
                        <?php } ?>
                    </div>
=======
                    <input type="text" name="email" class="form-control" placeholder="<?=Language::_('El. pašto adresas');?>" value="<?=Auth::user()->email;?>" />
>>>>>>> origin/master
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