<?php include VIEWS_PATH . '/user_menu.inc.php'; ?>

<div class="panel panel-default pull-left left-side balance">
    <div class="panel-heading"><?=Language::_('Balanso pildymas');?></div>
    <div class="panel-body">
        <?php if ( ! Settings::get('app.sms.paysera') && ! Settings::get('app.paypal.enabled') && ! Settings::get('app.mokejimai.enabled')) { ?>
            <div class="alert alert-info"><?=Language::_('Nėra įjungtų balanso pildymo būdų');?></div>
        <?php } ?>

        <ul class="nav nav-tabs">
            <?php if (Settings::get('app.sms.paysera')): ?>
                <li ><a href="#sms-tab" data-toggle="tab"><?=Language::_('SMS');?></a></li>
            <?php endif; ?>

            <?php if (Settings::get('app.paypal.enabled')): ?>
                <li class="active"><a href="#paypal" data-toggle="tab"><?=Language::_('Paypal');?></a></li>
            <?php endif; ?>

            <?php if (Settings::get('app.mokejimai.enabled')): ?>
                <li><a href="#paysera" data-toggle="tab"><?=Language::_('Paysera');?></a></li>
            <?php endif; ?>

            <?php if (Settings::get('app.paygol.enabled')): ?>
                <li><a href="#paygol" data-toggle="tab"><?=Language::_('Paygol');?></a></li>
            <?php endif; ?>
        </ul>

        <div class="tab-content">
            <div id="response" style="margin-top: 15px;"></div>

            <div class="tab-pane" id="sms-tab">
                <?php if (Settings::get('app.sms.paysera')): ?>
                    <div class="payment-title"><?=Language::_('Apmokėjimas per');?> <strong>SMS</strong></div>
                    <div class="payment-description"><?=Language::_('Pasirinkite šalį')?></div>

                    <ul class="sms-flags">
                        <?php foreach ($payseraSms as $country => $sms) { ?>
                            <li data-code="<?=$country;?>" data-toggle="tooltip" data-placement="top" title="<?=strtoupper($country);?>"><?=File::getFlagIcon($country);?></li>
                        <?php } ?>
                    </ul>

                    <div class="clearfix"></div>

                    <span id="sms"></span>
               <?php endif; ?>
            </div>

            <div class="tab-pane" id="paypal">
               <?php if (Settings::get('app.paypal.enabled')) { ?>
                    <div class="payment-title"><?=Language::_('Apmokėjimas per');?> <strong>Paypal</strong></div>
                    <div class="payment-description"><a href="https://www.paypal.com" target="_blank">https://www.paypal.com</a></div>

                    <div class="payment-info">
                        <p><strong><?=Language::_('Kursas');?>:</strong> <span><?php echo 1 . ' = ' . Settings::get('app.paypal.price') . ' ' . ucfirst(Settings::get('app.paypal.currency'));  ?></span></p>
                        <p><strong><?=Language::_('Minimali taškų suma');?>:</strong> <?=Settings::get('app.paypal.min');?></p>
                        <p><strong><?=Language::_('Maksimali taškų suma')?>:</strong> <?=Settings::get('app.paypal.max');?></p>
                        <p class="paypal-price"  data-price="<?=Settings::get('app.paypal.price');?>" data-max="<?=Settings::get('app.paypal.max');?>" data-min="<?=Settings::get('app.paypal.min');?>">
                            <strong><?=Language::_('Reikės mokėti')?>:</strong> <span>0.00</span>  <?php echo ucfirst(Settings::get('app.paypal.currency')); ?>
                        </p>
                    </div>

                    <form id="jas-paypal-form" class="form-inline">
                        <input type="hidden" name="paypal" value="true" />
                        <input type="hidden" name="item_number" value="<?php echo $itemNumber; ?>" />

                        <input type='text' name='sum' class="form-control" autocomplete='off' placeholder="<?=Language::_('Norima taškų suma');?>" />
                        <input type='button' name='jas_paypal_submit' class="btn btn-primary" value="<?=Language::_('Patvirtinti');?>" />
                    </form>
               <?php } ?>
            </div>

            <div class="tab-pane active" id="paysera">
               <?php if (Settings::get('app.mokejimai.enabled')) { ?>
                   <div class="payment-title"><?=Language::_('Apmokėjimas per');?> <strong>Paysera</strong></div>
                   <div class="payment-description"><a href="https://www.paysera.com" target="_blank">https://www.paysera.com</a></div>

                   <div class="payment-info">
                       <p><strong><?=Language::_('Kursas');?>:</strong> <span><?php echo 1 . ' = ' . Settings::get('app.mokejimai.price') . ' ' . ucfirst(Settings::get('app.mokejimai.currency'));  ?></span></p>
                       <p><strong><?=Language::_('Minimali taškų suma');?>:</strong> <?=Settings::get('app.mokejimai.min');?></p>
                       <p><strong><?=Language::_('Maksimali taškų suma')?>:</strong> <?=Settings::get('app.mokejimai.max');?></p>
                       <p class="mokejimai-price" data-price="<?=Settings::get('app.mokejimai.price');?>" data-max="<?=Settings::get('app.mokejimai.max');?>" data-min="<?=Settings::get('app.mokejimai.min');?>">
                           <strong><?=Language::_('Reikės mokėti')?>:</strong> <span>0.00</span>  <?php echo ucfirst(Settings::get('app.mokejimai.currency')); ?>
                       </p>
                   </div>

                   <form action='https://www.mokejimai.lt/pay/' method='post' id='jas-mokejimai-form' class="form-inline">
                       <?php foreach ($hiddenInputs as $name => $value) { ?>
                           <input type="hidden" name="<?php echo $name; ?>" value="<?php echo htmlspecialchars($value); ?>" />
                       <?php } ?>

                       <input type="hidden" name="mokejimai" value="mokejimai" />
                       <input type="hidden" name="amount" value="" />
                       <input type="hidden" name="order" value="<?php echo $itemNumber; ?>" />

                       <input type='text' name="sum" class="form-control" id="temp_amount" autocomplete='off' placeholder="<?=Language::_('Norima taškų suma');?>" />

                       <input type='button' name="jas_mokejimai_submit" class="btn btn-primary" value='<?=Language::_('Patvirtinti');?>' />
                   </form>
               <?php } ?>
            </div>

            <div class="tab-pane" id="paygol">
               <?php if (Settings::get('app.paygol.enabled')) { ?>
                    <div class="payment-title"><?=Language::_('Apmokėjimas per');?> <strong>Paygol</strong></div>
                    <div class="payment-description"><a href="http://www.paygol.com" target="_blank">http://www.paygol.com</a></div>

                    <div class="payment-info">
                        <p><strong><?=Language::_('Kursas');?>:</strong> <span><?php echo 1 . ' = ' . Settings::get('app.paygol.price') . ' ' . ucfirst(Settings::get('app.paygol.currency'));  ?></span></p>
                        <p><strong><?=Language::_('Minimali taškų suma');?>:</strong> <?=Settings::get('app.paygol.min');?></p>
                        <p><strong><?=Language::_('Maksimali taškų suma')?>:</strong> <?=Settings::get('app.paygol.max');?></p>
                        <p class="paygol-price" data-price="<?=Settings::get('app.paygol.price');?>" data-max="<?=Settings::get('app.paygol.max');?>" data-min="<?=Settings::get('app.paygol.min');?>">
                            <strong><?=Language::_('Reikės mokėti')?>:</strong> <span>0.00</span>  <?php echo ucfirst(Settings::get('app.paygol.currency')); ?>
                        </p>
                    </div>

                    <form name="pg_frm" method="post" action="https://www.paygol.com/pay" id='jas-paygol-form' class="form-inline">
                       <input type="hidden" name="pg_serviceid" value="<?=Settings::get('app.paygol.id');?>">
                       <input type="hidden" name="pg_currency" value="<?=strtoupper(Settings::get('app.paygol.currency'));?>">
                       <input type="hidden" name="pg_name" value="<?=Settings::get('app.paygol.text');?>">
                       <input type="hidden" name="pg_custom" value="<?php echo $pgItemNumber; ?>">
                       <input type="hidden" name="pg_return_url" value="<?=Settings::get('app.base_url') . '/index.php/paygol/verified';?>">
                       <input type="hidden" name="pg_cancel_url" value="<?=Settings::get('app.base_url') . '/index.php/paygol/cancel';?>">

                       <input type="hidden" name="paygol" value="paygol" />
                       <input type="hidden" name="order" value="<?php echo $pgItemNumber; ?>" />

                       <input type='text' name="pg_price" class="form-control" id="temp_amount" autocomplete='off' placeholder="<?=Language::_('Norima taškų suma');?>" />

                       <input type='button' name="jas_paygol_submit" class="btn btn-primary" value='<?=Language::_('Patvirtinti');?>' />
                    </form>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
