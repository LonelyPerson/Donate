<div class="panel panel-default pull-right right-side">
    <div class="bg"><img src="<?=config('app.img');?>/human.png" /></div>

    <div class="panel-heading"><?=__('Menu');?></div>
    <div class="panel-body">
        <?php include VIEWS_PATH . '/languages.inc.php'; ?>

        <ul class="nav nav-pills nav-stacked">
            <?=Menu::render();?>
        </ul>
    </div>
</div>
