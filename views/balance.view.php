<?php include ROOT_PATH . '/views/user_menu.inc.php'; ?>

<div class="panel panel-default left-side">
    <div class="panel-heading"><?=Language::_('Balanso pildymas');?></div>
    <div class="panel-body">
        <?php if ( ! Settings::get('app.sms.paysera') && ! Settings::get('app.paypal.enabled') && ! Settings::get('app.mokejimai.enabled')) { ?>
            <div class="alert alert-info"><?=Language::_('Nėra įjungtų balanso pildymo būdų');?></div>
        <?php } ?>

        <?php if (Settings::get('app.sms.paysera')) { ?>
            <fieldset class="box">
                <legend><?=Language::_('SMS');?> </legend>

                <ul class="sms-flags">
                    <?php foreach ($payseraSms as $country => $sms) { ?>
                        <li data-code="<?=$country;?>" data-toggle="tooltip" data-placement="top" title="<?=strtoupper($country);?>"><?=File::getFlagIcon($country);?></li>
                    <?php } ?>
                </ul>

                <div class="clearfix"></div>

                <span id="sms"></span>
            </fieldset>
        <?php } ?>

        <div id="response" style="margin-top: 15px;"></div>

        <?php if (Settings::get('app.paypal.enabled')) { ?>
            <fieldset class="box">
                <legend><?=Language::_('Paypal');?> <span><?php echo 1 . ' = ' . Settings::get('app.paypal.price') . ' ' . ucfirst(Settings::get('app.paypal.currency'));  ?></span></legend>

                <div class="alert alert-info"><?=Language::_('Minimali taškų suma: %s Maksimali taškų suma: %s', [Settings::get('app.paypal.min'), Settings::get('app.paypal.max')]);?></div>

                <form id="jas-paypal-form" class="form-inline">
                    <input type="hidden" name="paypal" value="true" />
                    <input type="hidden" name="item_number" value="<?php echo $itemNumber; ?>" />

                    <div class="input-group col-sm-9">
                        <span class="input-group-addon paypal-price" data-price="<?=Settings::get('app.paypal.price');?>" data-max="<?=Settings::get('app.paypal.max');?>" data-min="<?=Settings::get('app.paypal.min');?>"><span>0.00</span> <?php echo ucfirst(Settings::get('app.paypal.currency')); ?></span>
                        <input type='text' name='sum' class="form-control" autocomplete='off' />
                    </div>

                    <input type='button' name='jas_paypal_submit' class="btn btn-primary" value="<?=Language::_('Patvirtinti');?>" />
                </form>
            </fieldset>
        <?php } ?>

        <?php if (Settings::get('app.mokejimai.enabled')) { ?>
            <fieldset class="box">
                <legend><?=Language::_('Mokejimai / Paysera');?> <span><?php echo 1 . ' = ' . Settings::get('app.mokejimai.price') . ' ' . ucfirst(Settings::get('app.mokejimai.currency'));  ?></span></legend>

                <div class="alert alert-info"><?=Language::_('Minimali taškų suma: %s Maksimali taškų suma: %s', [Settings::get('app.mokejimai.min'), Settings::get('app.mokejimai.max')]);?></div>

                <form action='https://www.mokejimai.lt/pay/' method='post' id='jas-mokejimai-form' class="form-inline">
                    <?php foreach ($hiddenInputs as $name => $value) { ?>
                        <input type="hidden" name="<?php echo $name; ?>" value="<?php echo htmlspecialchars($value); ?>" />
                    <?php } ?>

                    <input type="hidden" name="mokejimai" value="mokejimai" />
                    <input type="hidden" name="amount" value="" />
                    <input type="hidden" name="order" value="<?php echo $itemNumber; ?>" />

                    <div class="input-group col-sm-9">
                        <span class="input-group-addon mokejimai-price" data-price="<?=Settings::get('app.mokejimai.price');?>" data-max="<?=Settings::get('app.mokejimai.max');?>" data-min="<?=Settings::get('app.mokejimai.min');?>"><span>0.00</span> <?php echo ucfirst(Settings::get('app.mokejimai.currency')); ?></span>
                        <input type='text' name="sum" class="form-control" id="temp_amount" autocomplete='off' />
                    </div>

                    <input type='button' name="jas_mokejimai_submit" class="btn btn-primary" value='<?=Language::_('Patvirtinti');?>' />
                </form>
            </fieldset>
        <?php } ?>
    </div>
</div>