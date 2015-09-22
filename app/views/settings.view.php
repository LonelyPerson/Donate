<?php include VIEWS_PATH . '/user_menu.inc.php'; ?>

<div class="panel panel-default left-side">
    <div class="panel-heading"><?=__('Settings');?></div>
    <div class="panel-body">
        <div id="response"></div>

        <?php if (config('app.settings.enabled')) { ?>
            <form id="settings-form">
                <input type="hidden" name="settings_save" value="ok" />

                <div class="form-group">
                    <div class="input-group email-group">
                        <input type="text" name="email" class="form-control" placeholder="<?=__('E-mail address');?>" <?=(Auth::user()->email_status == 2) ? 'disabled="disabled"' : '';?> value="<?=Auth::user()->email;?>" />

                        <?php if ( ! Auth::user()->email && Auth::user()->email_status != 2) { ?>
                            <span class="input-group-addon">
                                <i 
                                    class="fa fa-times" 
                                    data-toggle="tooltip"
                                    data-placement="top"
                                    title="<?=__('E-mail address not entered');?>"></i>
                            </span>
                        <?php } else { ?>
                            <span class="input-group-addon <?=(Auth::user()->email_status != 1) ? 'email-not-verified' : 'email-verified';?>">
                                <i 
                                    data-toggle="tooltip"
                                    data-placement="top"
                                    title="<?=(Auth::user()->email_status != 1) ? __('E-mail address waiting for verification') : __('E-mail address verified');?>"
                                    class="fa <?=(Auth::user()->email_status != 1) ? 'fa-clock-o' : 'fa-check';?>"></i>
                            </span>
                        <?php } ?>
                    </div>
                </div>

                <div class="form-group">
                    <input type="password" name="old_password" class="form-control" placeholder="<?=__('Current password');?>" />
                </div>

                <div class="form-group">
                    <input type="password" name="new_password" class="form-control" placeholder="<?=__('New password');?>" />
                </div>

                <div class="input">
                    <input type="button" name="save" class="btn btn-primary" value="<?=__('Save');?>" />
                </div>
            </form>

        <?php } ?>
    </div>
</div>
