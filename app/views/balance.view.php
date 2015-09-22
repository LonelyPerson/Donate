<?php include VIEWS_PATH . '/user_menu.inc.php'; ?>

<div class="panel panel-default pull-left left-side balance">
    <div class="panel-heading"><?=__('Balance');?></div>
    <div class="panel-body">
        <?php if ( ! config('app.mokejimai.sms') && ! config('app.paypal.enabled') && ! config('app.mokejimai.enabled')) { ?>
            <div class="alert alert-info"><?=__('There are no payment methods enabled');?></div>
        <?php } ?>

        <ul class="nav nav-tabs">
            <?php if (config('app.mokejimai.sms')): ?>
                <li ><a href="#sms-tab" data-toggle="tab"><?=__('SMS');?></a></li>
            <?php endif; ?>

            <?php if (config('app.paypal.enabled')): ?>
                <li class="active"><a href="#paypal" data-toggle="tab"><?=__('Paypal');?></a></li>
            <?php endif; ?>

            <?php if (config('app.mokejimai.enabled')): ?>
                <li><a href="#paysera" data-toggle="tab"><?=__('Paysera');?></a></li>
            <?php endif; ?>

            <?php if (config('app.paygol.enabled')): ?>
                <li><a href="#paygol" data-toggle="tab"><?=__('Paygol');?></a></li>
            <?php endif; ?>
        </ul>

        <div class="tab-content">
            <div id="response" style="margin-top: 15px;"></div>

            <div class="tab-pane" id="sms-tab">
                <?php if (config('app.mokejimai.sms')): ?>
                    <div class="payment-title"><?=__('Pay through');?> <strong>SMS</strong></div>
                    <div class="payment-description"><?=__('Select country')?></div>

                    <?php if ($payseraSms) : ?>
                      <ul class="sms-flags">
                          <?php foreach ($payseraSms as $country => $sms) { ?>
                              <li data-code="<?=$country;?>" data-toggle="tooltip" data-placement="top" title="<?=strtoupper($country);?>"><?=File::getFlagIcon($country);?></li>
                          <?php } ?>
                      </ul>
                    <?php else: ?>
                      <div class="alert alert-info" style="margin-top: 15px;"><?=__('No SMS');?></div>
                    <?php endif; ?>

                    <div class="clearfix"></div>

                    <span id="sms"></span>
               <?php endif; ?>
            </div>

            <div class="tab-pane active" id="paypal">
               <?php if (config('app.paypal.enabled')) { ?>
                    <div class="payment-title"><?=__('Pay through');?> <strong>Paypal</strong></div>
                    <div class="payment-description"><a href="https://www.paypal.com" target="_blank">https://www.paypal.com</a></div>

                    <div class="payment-info">
                        <p><strong><?=__('Rate');?>:</strong> <span><?php echo 1 . ' DC = ' . config('app.paypal.price') . ' ' . ucfirst(config('app.paypal.currency'));  ?></span></p>
                        <p><strong><?=__('Min. DC quantity');?>:</strong> <?=config('app.paypal.min');?></p>
                        <p><strong><?=__('Max. DC quantity')?>:</strong> <?=config('app.paypal.max');?></p>
                        <p class="paypal-price"  data-price="<?=config('app.paypal.price');?>" data-max="<?=config('app.paypal.max');?>" data-min="<?=config('app.paypal.min');?>">
                            <strong><?=__('You will be charged')?>:</strong> <span>0.00</span>  <?php echo ucfirst(config('app.paypal.currency')); ?>
                        </p>
                    </div>

                    <form id="jas-paypal-form" class="form-inline">
                        <input type="hidden" name="paypal" value="true" />
                        <input type="hidden" name="item_number" value="<?php echo $itemNumber; ?>" />

                        <input type='text' name='sum' class="form-control" autocomplete='off' placeholder="<?=__('DC quantity');?>" />
                        <input type='button' name='jas_paypal_submit' class="btn btn-primary" value="<?=__('Confirm');?>" />
                    </form>
               <?php } ?>
            </div>

            <div class="tab-pane" id="paysera">
               <?php if (config('app.mokejimai.enabled')) { ?>
                   <div class="payment-title"><?=__('Pay through');?> <strong>Paysera</strong></div>
                   <div class="payment-description"><a href="https://www.paysera.com" target="_blank">https://www.paysera.com</a></div>

                   <div class="payment-info">
                       <p><strong><?=__('Rate');?>:</strong> <span><?php echo 1 . ' DC = ' . config('app.mokejimai.price') . ' ' . ucfirst(config('app.mokejimai.currency'));  ?></span></p>
                       <p><strong><?=__('Min. DC quantity');?>:</strong> <?=config('app.mokejimai.min');?></p>
                       <p><strong><?=__('Max. DC quantity')?>:</strong> <?=config('app.mokejimai.max');?></p>
                       <p class="mokejimai-price" data-price="<?=config('app.mokejimai.price');?>" data-max="<?=config('app.mokejimai.max');?>" data-min="<?=config('app.mokejimai.min');?>">
                           <strong><?=__('You will be charged')?>:</strong> <span>0.00</span>  <?php echo ucfirst(config('app.mokejimai.currency')); ?>
                       </p>
                   </div>

                   <form action='https://www.mokejimai.lt/pay/' method='post' id='jas-mokejimai-form' class="form-inline">
                       <?php foreach ($hiddenInputs as $name => $value) { ?>
                           <input type="hidden" name="<?php echo $name; ?>" value="<?php echo htmlspecialchars($value); ?>" />
                       <?php } ?>

                       <input type="hidden" name="mokejimai" value="mokejimai" />
                       <input type="hidden" name="amount" value="" />
                       <input type="hidden" name="order" value="<?php echo $itemNumber; ?>" />

                       <input type='text' name="sum" class="form-control" id="temp_amount" autocomplete='off' placeholder="<?=__('DC quantity');?>" />

                       <input type='button' name="jas_mokejimai_submit" class="btn btn-primary" value='<?=__('Confirm');?>' />
                   </form>
               <?php } ?>
            </div>

            <div class="tab-pane" id="paygol">
               <?php if (config('app.paygol.enabled')) { ?>
                    <div class="payment-title"><?=__('Pay through');?> <strong>Paygol</strong></div>
                    <div class="payment-description"><a href="http://www.paygol.com" target="_blank">http://www.paygol.com</a></div>

                    <div class="payment-info">
                        <p><strong><?=__('Rate');?>:</strong> <span><?php echo 1 . ' DC = ' .config('app.paygol.price') . ' ' . ucfirst(config('app.paygol.currency'));  ?></span></p>
                        <p><strong><?=__('Min. DC quantity');?>:</strong> <?=config('app.paygol.min');?></p>
                        <p><strong><?=__('Max. DC quantity')?>:</strong> <?=config('app.paygol.max');?></p>
                        <p class="paygol-price" data-price="<?=config('app.paygol.price');?>" data-max="<?=config('app.paygol.max');?>" data-min="<?=config('app.paygol.min');?>">
                            <strong><?=__('You will be charged')?>:</strong> <span>0.00</span>  <?php echo ucfirst(config('app.paygol.currency')); ?>
                        </p>
                    </div>

                    <form name="pg_frm" method="post" action="https://www.paygol.com/pay" id='jas-paygol-form' class="form-inline">
                       <input type="hidden" name="pg_serviceid" value="<?=config('app.paygol.id');?>">
                       <input type="hidden" name="pg_currency" value="<?=strtoupper(config('app.paygol.currency'));?>">
                       <input type="hidden" name="pg_name" value="<?=config('app.paygol.text');?>">
                       <input type="hidden" name="pg_custom" value="<?php echo $pgItemNumber; ?>">
                       <input type="hidden" name="pg_return_url" value="<?=config('app.base_url') . '/payment/paygol/verified';?>">
                       <input type="hidden" name="pg_cancel_url" value="<?=config('app.base_url') . '/payment/paygol/cancel';?>">

                       <input type="hidden" name="paygol" value="paygol" />
                       <input type="hidden" name="order" value="<?php echo $pgItemNumber; ?>" />

                       <input type='text' name="pg_price" class="form-control" id="temp_amount" autocomplete='off' placeholder="<?=__('DC quantity');?>" />

                       <input type='button' name="jas_paygol_submit" class="btn btn-primary" value='<?=__('Confirm');?>' />
                    </form>
                <?php } ?>
            </div>
        </div>
    </div>
</div>
