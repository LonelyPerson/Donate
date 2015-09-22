<?php
    include VIEWS_PATH . '/user_menu.inc.php';

    if ( ! config('app.inventory.enabled'))
        exit('Inventory disabled');
?>
<div class="panel panel-default pull-left left-side inventory">
    <div class="panel-heading"><?=__('Inventory');?></div>
    <div class="panel-body">
        <div id="response"></div>
        
        <?php if ($items) : ?>
            <?php $c=0; foreach ($items as $row) : ?>
                <div class="jas-buy item-box pull-left p" <?=(config('app.inventory.per_page') && $c >= config('app.inventory.per_page')) ? 'style="display: none;"' : '';?> id="<?=$c;?>">
                    <div class="img pull-left">
                        <?=Item::getIconFromDB($row->$sql_itemId);?>
                    </div>
                    <div class="jas-title">
                        <div class="jas-title-in">
                            <?=Item::getTitleFromDB($row->$sql_itemId);?>
                        </div>
                    </div>

                    <div class="clearfix"></div>

                    <div class="jas-buttons">
                        <a href="javascript: void(0)" class="btn btn-default btn-sm" data-toggle="modal" data-target="#item-<?=$c;?>"><?=__('View');?></a>
                    </div>
                </div>

                <div class="item-modal modal fade" id="item-<?=$c;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                    <div class="modal-dialog" role="document">
                        <div class="modal-content">
                            <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel">
                                    <?=Item::getTitleFromDB($row->$sql_itemId);?>
                                    <?php if ($row->$sql_enchantLevel) : ?>
                                        +<?=$row->$sql_enchantLevel;?></li>
                                    <?php endif; ?>
                                </h4>
                            </div>
                            <div class="modal-body">
                                <div class="response-c" id="response-<?=$c;?>"></div>

                                <div class="img pull-left">
                                    <?=Item::getIconFromDB($row->$sql_itemId);?>
                                </div>
                                <div class="info pull-left">
                                    <ul>
                                        <li><strong><?=__('Quantity');?>:</strong> <?=Currency::format($row->$sql_count, 'simple');?></li>
                                        <li><strong><?=__('Location');?>:</strong> <?=Item::getLoc($row->$sql_loc);?></li>
                                        <?php if ($row->$sql_enchantLevel) : ?>
                                            <li><strong><?=__('Enchant');?>:</strong> +<?=$row->$sql_enchantLevel;?></li>
                                        <?php endif; ?>
                                    </ul>
                                </div>

                                <div class="clearfix"></div>
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal"><?=__('Close');?></button>

                                <?php if (config('app.inventory.allow_delete') && ! Item::inMarket($row->$sql_objectId)) : ?>
                                    <?php if ($row->$sql_count > 1) : ?>
                                        <div class="col-lg-6 pull-right">
                                        <div class="input-group">
                                            <input type="text" name="count-<?=$row->$sql_objectId;?>" class="form-control" placeholder="<?=__('Quantity');?>" style="width: 150px;" />
                                            <span class="input-group-btn">
                                    <?php endif; ?>

                                    <?php if (config('app.inventory.delete_confirm')) : ?>
                                        <button type="button" class="btn btn-primary jas-delete" data-object-id="<?=$row->$sql_objectId;?>" data-toggle="confirmation" data-confirm-title="Patvirtinimas" data-confirm-content="<?=__('Are you really want to delete this item?');?>" data-confirm-yesBtn="<?=__('Yes');?>" data-confirm-noBtn="<?=__('No');?>"><?=__('Delete');?></button>
                                    <?php else: ?>
                                        <a href="javascript: void(0)" class="btn btn-primary jas-delete" data-object-id="<?=$row->$sql_objectId;?>"><?=__('Delete');?></a>
                                    <?php endif; ?>

                                    <?php if ($row->$sql_count > 1) : ?>
                                            </span>
                                        </div>
                                        </div>
                                    <?php endif; ?>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
            <?php $c++; endforeach; ?>

            <div class="clearfix"></div>

            <?php if ($pagination): ?>
                <div class="pagination-wrapper">
                    <?=$pagination;?>
                </div>
            <?php endif; ?>
        <?php else: ?>
            <?php if ( ! Player::isSelected()) : ?>
                <div class="alert alert-info"><?=__('Character is not selected');?></div>
            <?php else: ?>
                <div class="alert alert-info"><?=__("You don't have any item");?></div>
            <?php endif; ?>
        <?php endif; ?>

        <div class="clearfix"></div>


    </div>
</div>
