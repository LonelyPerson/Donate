<?php
    include VIEWS_PATH . '/user_menu.inc.php';

    if ( ! Settings::get('app.inventory.enabled'))
        exit('Inventory disabled');
?>
<div class="panel panel-default pull-left left-side inventory">
    <div class="panel-heading"><?=Language::_('Inventorius');?></div>
    <div class="panel-body">
        <div id="response"></div>

        <div class="">
            <?php if ($items) : ?>
                <?php $c=0; foreach ($items as $row) : ?>
                    <div class="jas-buy item-box pull-left">
                        <div class="img pull-left">
                            <?php echo File::getItemIcon('', $row->$sql_itemId); ?>
                        </div>
                        <div class="jas-title">
                            <div class="jas-title-in">
                                <?=Item::getTitle($row->$sql_itemId, '');?>
                            </div>
                        </div>

                        <div class="clearfix"></div>

                        <div class="jas-buttons">
                            <a href="javascript: void(0)" class="btn btn-default btn-sm" data-toggle="modal" data-target="#item-<?=$c;?>">Peržiūrėti</a>
                        </div>
                    </div>

                    <div class="item-modal modal fade" id="item-<?=$c;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                        <div class="modal-dialog" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                    <h4 class="modal-title" id="myModalLabel">
                                        <?=Item::getTitle($row->$sql_itemId, '');?>
                                        <?php if ($row->$sql_enchantLevel) : ?>
                                            +<?=$row->$sql_enchantLevel;?></li>
                                        <?php endif; ?>
                                    </h4>
                                </div>
                                <div class="modal-body">
                                    <div class="response-c" id="response-<?=$c;?>"></div>

                                    <div class="img pull-left">
                                        <?php echo File::getItemIcon('', $row->$sql_itemId); ?>
                                    </div>
                                    <div class="info pull-left">
                                        <ul>
                                            <li><strong>Kiekis:</strong> <?=Currency::format($row->$sql_count, 'simple');?></li>
                                            <li><strong>Vieta:</strong> <?=Item::getLoc($row->$sql_loc);?></li>
                                            <?php if ($row->$sql_enchantLevel) : ?>
                                                <li><strong>Pliusai:</strong> <?=$row->$sql_enchantLevel;?></li>
                                            <?php endif; ?>
                                        </ul>
                                    </div>

                                    <div class="clearfix"></div>
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Uždaryti</button>

                                    <?php if (Settings::get('app.inventory.allow_delete') && ! Item::inMarket($row->$sql_objectId)) : ?>
                                        <?php if ($row->$sql_count > 1) : ?>
                                            <div class="col-lg-6 pull-right">
                                            <div class="input-group">
                                                <input type="text" name="count-<?=$row->$sql_objectId;?>" class="form-control" placeholder="Kiek ištrinti (min. 1)" style="width: 150px;" />
                                                <span class="input-group-btn">
                                        <?php endif; ?>

                                        <?php if (Settings::get('app.inventory.delete_confirm')) : ?>
                                            <button type="button" class="btn btn-primary jas-delete" data-object-id="<?=$row->$sql_objectId;?>" data-toggle="confirmation" data-confirm-title="Patvirtinimas" data-confirm-content="Ar tikrai norite ištrinti šį daiktą?" data-confirm-yesBtn="Taip" data-confirm-noBtn="Ne">Ištrinti</button>
                                        <?php else: ?>
                                            <a href="javascript: void(0)" class="btn btn-primary jas-delete" data-object-id="<?=$row->$sql_objectId;?>">Ištrinti</a>
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
            <?php else: ?>
                <?php if ( ! Player::isSelected()) : ?>
                    <div class="alert alert-info">Nepasirinktas veikėjas</div>
                <?php else: ?>
                    <div class="alert alert-info">Neturite daiktų</div>
                <?php endif; ?>
            <?php endif; ?>

            <div class="clearfix"></div>
        </div>
    </div>
</div>
