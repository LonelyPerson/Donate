<?php if (Auth::isLoggedIn()) : ?>
	<?php include VIEWS_PATH . '/user_menu.inc.php'; ?>
<?php else: ?>
	<?php include VIEWS_PATH . '/menu.inc.php'; ?>
<?php endif; ?>


<div class="panel panel-default left-side">
    <div class="panel-heading"><?=__('Information');?></div>
    <div class="panel-body">
        <?=$message;?>
    </div>
</div>
