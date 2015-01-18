<?php include ROOT_PATH . '/views/user_menu.inc.php'; ?>

<div class="panel panel-default left-side">
    <div class="panel-heading"><?=Language::_('Informacija');?></div>
    <div class="panel-body">
        <?php if ($id == 'paypal' && $action == 'verify') { ?>
            <?=Language::_('Jūsų apmokėjimas per paypal sistemą užskaitytas');?>  
        <?php } ?>

        <?php if ($id == 'paypal' && $action == 'cancel') { ?>
            <?=Language::_('Jūs atšaukėte apmokėjimą');?>
        <?php } ?>

        <?php if ($id == 'mokejimai' && $action == 'verify') { ?>
            <?=Language::_('Jūsų apmokėjimas per mokejimai.lt sistemą užskaitytas');?>
        <?php } ?>

        <?php if ($id == 'mokejimai' && $action == 'cancel') { ?>
            <?=Language::_('Jūs atšaukėte apmokėjimą');?>
        <?php } ?>
    </div>
</div>
