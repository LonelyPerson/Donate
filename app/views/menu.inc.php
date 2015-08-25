<div class="panel panel-default pull-right right-side">
    <div class="bg"><img src="<?=Settings::get('app.img');?>/human.png" /></div>

    <div class="panel-heading"><?=Language::_('Meniu');?></div>
    <div class="panel-body">
        <?php include VIEWS_PATH . '/languages.inc.php'; ?>

        <ul class="nav nav-pills nav-stacked">
            <?=Menu::render();?>
        </ul>
    </div>
</div>
