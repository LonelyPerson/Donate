<?php
    include VIEWS_PATH . '/user_menu.inc.php';

    if ( ! config('app.shop.enabled'))
        exit('Shop disabled');
?>

<div class="panel panel-default pull-left left-side shop">
    <div class="panel-heading"><?=__('Shop');?></div>
    <div class="panel-body">
        <?php if (Player::isSelected()) : ?>
            <div>
                <?php $c = 0; foreach ($items as $key => $row) { ?>
                    <div class="p" <?=(config('app.shop.per_page') && $c >= config('app.shop.per_page')) ? 'style="display: none;"' : '';?> id="<?=$c;?>">
                        
                        <div class="jas-buy item-box pull-left">
                            <span class="label label-warning price"><?=Currency::format(Item::totalPrice($row->group_id), 'price');?></span>

                            <div class="img pull-left">
                                <?php if ($row->is_group) : ?>
                                    <i class="fa fa-cubes" style="font-size: 20px;"></i>
                                <?php else: ?>
                                    <?=Item::getIcon($row->icon);?>
                                <?php endif; ?>
                                
                            </div>
                            <div class="jas-title">
                                <?php if ($row->is_group) : ?>
                                    <?=__('Items group');?>
                                <?php else: ?>
                                    <?=Item::getTitle($row->title, $row->item_name);?>
                                <?php endif; ?>
                            </div>

                            <div class="clearfix"></div>

                            <div class="jas-buttons">
                                <a href="javascript: void(0)" class="btn btn-default btn-sm" data-toggle="modal" data-target="#item-<?=$c;?>"><?=__('View');?></a>
                            </div>
                        </div>

                        <!-- Modal -->
                        <div class="item-modal modal fade" id="item-<?=$c;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                            <div class="modal-dialog" role="document">
                                <div class="modal-content">
                                    <div class="modal-header">
                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                        <h4 class="modal-title" id="myModalLabel">
                                            <?php if ($row->is_group) : ?>
                                                <?=__('Items group');?>
                                            <?php else: ?>
                                                <?=Item::getTitle($row->title, $row->item_name);?>
                                            <?php endif; ?>
                                        </h4>
                                    </div>
                                    <div class="modal-body">
                                        <div class="response-c" id="response-<?=$c;?>"></div>

                                        <?php if ($row->is_group) : ?>

                                            <?php foreach (Item::getGroupItems($row->group_id) as $row) : ?>

                                                <div class="items-group">
                                                    <div class="img pull-left">
                                                        <?=Item::getIcon($row->icon);?>
                                                    </div>
                                                    <div class="info pull-left">
                                                        <ul>
                                                        	<li><?=Item::getTitle($row->title, $row->item_name);?></li>
                                                            <li><strong><?=__('Quantity');?>:</strong> <?=$row->quantity;?></li>
                                                        </ul>
                                                    </div>

                                                    <div class="clearfix"></div>
                                                </div>

                                            <?php endforeach; ?>

                                        <?php else: ?>
                                            <div class="img pull-left">
                                                <?=Item::getIcon($row->icon);?>
                                            </div>
                                            <div class="info pull-left">
                                                <ul>
                                                    <li><strong><?=__('Price');?>:</strong> <?=Currency::format($row->price, 'price');?></li>
                                                    <li><strong><?=__('Quantity');?>:</strong> <?=$row->quantity;?></li>
                                                </ul>
                                            </div>
                                        <?php endif; ?>

                                        <div class="clearfix"></div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-default" data-dismiss="modal"><?=__('Close');?></button>

                                        <?php if (config('app.shop.buy_confirmation')) : ?>
                                            <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#buy-confirm-modal-<?=$c;?>"><?=__('Buy');?></button>
                                        <?php else: ?>
                                            <button type="button" class="btn btn-primary jas-buy" data-id="<?=$c;?>" data-group-id="<?=$row->group_id;?>" data-is-group="<?=$row->is_group;?>" data-price='<?=$row->price;?>' data-item-id="<?=$row->item_id;?>" data-quantity="<?=$row->quantity;?>" data-stackable="<?=$row->stackable;?>" data-title="<?=$row->item_name;?>"><?=__('Buy');?></button>
                                        <?php endif; ?>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                    </div>

                    <?php if (config('app.shop.buy_confirmation')) : ?>
                        <div class="item-modal modal fade" id="buy-confirm-modal-<?=$c;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel"><?=__('Buy confirmation');?></h4>
                              </div>
                              <div class="modal-body">
                                  <?=__('Are you really want to buy this item?');?>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal"><?=__('No');?></button>
                                <button type="button" class="btn btn-primary jas-buy" data-id="<?=$c;?>" data-group-id="<?=$row->group_id;?>" data-is-group="<?=$row->is_group;?>" data-price='<?=$row->price;?>' data-item-id="<?=$row->item_id;?>" data-quantity="<?=$row->quantity;?>" data-stackable="<?=$row->stackable;?>" data-title="<?=$row->item_name;?>"><?=__('Yes');?></button>
                              </div>
                            </div>
                          </div>
                        </div>
                    <?php endif; ?>

                <?php $c++; } ?>

                <div class="clearfix"></div>

                <?php if ($pagination): ?>
                    <div class="pagination-wrapper">
                        <?=$pagination;?>
                    </div>
                <?php endif; ?>

            <?php else: ?>
                <div class="alert alert-info"><?=__('Character is not selected');?></div>
            <?php endif; ?>
        </div>
    </div>
</div>
