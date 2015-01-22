<?php
    include ROOT_PATH . '/views/user_menu.inc.php';

    if ( ! Settings::get('app.shop.enabled'))
        exit('Shop disabled');
?>

<div class="panel panel-default pull-left left-side">
    <div class="panel-heading"><?=Language::_('Parduotuvė');?></div>
    <div class="panel-body">
        <div id="response"></div>

        <div>
            <?php foreach ($items as $key => $row) { ?>
                <?php if ($key == 'item') { ?>
                    <div class="item-box pull-left" data-toggle="tooltip" data-placement="top" title="<?php echo $row->title; ?>">
                        <div class="img">
                            <span class="label label-warning price"><?=Language::_('Kaina:');?> <?php echo $row->price; ?></span>
                            <?php echo File::getItemIcon($row->img); ?>
                        </div>
                        <div class="jas-buy-box">
                            <a href="javascript: void(0);" class="jas-buy btn btn-primary btn-sm" data-item='<?php echo json_encode($row); ?>'><?=Language::_('Pirkti');?></a>
                        </div>
                    </div>
                <?php } else { 
                    ?>
                        <div class="item-box pull-left" data-toggle="tooltip" data-placement="top" title="<?=Language::_('Grupėje esantys daiktai:');?><br />
                             <?php
                                foreach ($row as $group_key => $group_value) {
                                    echo $group_value->title . "<br />";
                                }
                             ?>">
                            <div class="img">
                                <span class="label label-warning group-price"><?=Language::_('Kaina:');?> <?php echo $row['price']; ?></span>
                                <span class="label label-danger group"><?=Language::_('Grupė');?></span>
                                <?php echo File::getItemIcon($row['img']); ?>
                            </div>
                            <div class="jas-buy-box">
                                <a href="javascript: void(0);" class="jas-buy btn btn-primary btn-sm" data-item='<?php echo json_encode($row); ?>'><?=Language::_('Pirkti');?></a>
                            </div>
                        </div>
                    <?php
                } ?>
            <?php } ?>
            
            <div class="clearfix"></div>
        </div>
    </div>
</div>