<div id="captcha" data-key="<?php echo Settings::get('app.captcha.key'); ?>" style="display:none">
    <div id="recaptcha_image"></div>
    
    <div class="input-group input">
        <input type="text" id="recaptcha_response_field" name="recaptcha_response_field" class="form-control" placeholder="<?=_('Apsaugos kodas');?>" />
        <div class="input-group-addon"><a href="javascript: Recaptcha.reload();"><span class="glyphicon glyphicon-repeat" aria-hidden="true"></></span></div>
    </div>
</div>