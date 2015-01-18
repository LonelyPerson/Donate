<?php include ROOT_PATH . '/views/user_menu.inc.php'; ?>

<div class="panel panel-default left-side">
    <div class="panel-heading"><?=Language::_('Balanso pildymas');?></div>
    <div class="panel-body">
        <div id="response"></div>
        
        <fieldset class="box">
            <legend><?=Language::_('Paypal');?></legend>
            
            <form id="jas-paypal-form" class="form-inline">
                <input type="hidden" name="paypal" value="true" />
                <input type="hidden" name="cmd" value="_donations" /> 
                <input type="hidden" name="rm" value="2" /> 
                <input type="hidden" name="no_note" value="1" />
                <input type="hidden" name="bn" value="PP-BuyNowBF:btn_buynow_LG.gif:NonHostedGuest" />
                <input type="hidden" name="item_number" value="<?php echo $itemNumber; ?>" />
                
                <div class="input-group col-sm-9">
                    <span class="input-group-addon"><?php echo 1 . ' = ' . Settings::get('app.paypal.price') . ' &euro;';  ?></span>
                    <input type='text' name='sum' class="form-control" autocomplete='off' />
                </div>
                
                <input type='button' name='jas_paypal_submit' class="btn btn-primary" value="<?=Language::_('Patvirtinti');?>" />
            </form>
        </fieldset>

        <fieldset class="box">
            <legend><?=Language::_('Mokejimai / Paysera');?></legend>
        
            <form action='https://www.mokejimai.lt/pay/' method='post' id='jas-mokejimai-form' class="form-inline">
                <?php foreach ($hiddenInputs as $name => $value) { ?>
                    <input type="hidden" name="<?php echo $name; ?>" value="<?php echo htmlspecialchars($value); ?>" />
                <?php } ?>

                <input type="hidden" name="mokejimai" value="mokejimai" />
                <input type="hidden" name="amount" value="" />
                <input type="hidden" name="order" value="<?php echo $itemNumber; ?>" />

                <div class="input-group col-sm-9">
                    <span class="input-group-addon"><?php echo 1 . ' = ' . Settings::get('app.mokejimai.price') . ' &euro;';  ?></span>
                    <input type='text' name="sum" class="form-control" id="temp_amount" autocomplete='off' />
                </div>
                
                <input type='button' name="jas_mokejimai_submit" class="btn btn-primary" value='<?=Language::_('Patvirtinti');?>' />
            </form>
        </fieldset>
    </div>
</div>