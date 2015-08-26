<?php
    include VIEWS_PATH . '/user_menu.inc.php';

    if ( ! Settings::get('app.shop.enabled'))
        exit('Shop disabled');
?>

<div class="panel panel-default pull-left left-side shop">
    <div class="panel-heading"><?=Language::_('Parduotuvė');?></div>
    <div class="panel-body">
        <?php if (Player::isSelected()) : ?>
            <div>
                <?php $c = 0; foreach ($items as $key => $row) { ?>
                    <div class="p" <?=(Settings::get('app.shop.per_page') && $c >= Settings::get('app.shop.per_page')) ? 'style="display: none;"' : '';?> id="<?=$c;?>">
                        <?php if ($key == 'item') { ?>
                            <div class="jas-buy item-box pull-left" data-item='<?php echo json_encode($row); ?>'>
                                <span class="label label-warning price"><?=Currency::format($row->price, 'price');?></span>

                                <div class="img pull-left">
                                    <?php echo File::getItemIcon($row->img, $row->id); ?>
                                </div>
                                <div class="jas-title">
                                    <?=Item::getTitle($row->id, $row->title);?>
                                </div>

                                <div class="clearfix"></div>

                                <div class="jas-buttons">
                                    <a href="javascript: void(0)" class="btn btn-default btn-sm" data-toggle="modal" data-target="#item-<?=$c;?>">Peržiūrėti</a>
                                </div>
                            </div>

                            <!-- Modal -->
                            <div class="item-modal modal fade" id="item-<?=$c;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                <div class="modal-dialog" role="document">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                            <h4 class="modal-title" id="myModalLabel"><?=Item::getTitle($row->id, $row->title);?></h4>
                                        </div>
                                        <div class="modal-body">
                                            <div class="response-c" id="response-<?=$c;?>"></div>

                                            <div class="img pull-left">
                                                <?php echo File::getItemIcon($row->img, $row->id); ?>
                                            </div>
                                            <div class="info pull-left">
                                                <ul>
                                                    <li><strong>Kaina:</strong> <?=Currency::format($row->price, 'price');?></li>
                                                    <li><strong>Kiekis:</strong> <?=$row->count;?></li>
                                                </ul>
                                            </div>

                                            <div class="clearfix"></div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default" data-dismiss="modal">Uždaryti</button>

                                            <?php if (Settings::get('app.shop.buy_confirmation')) : ?>
                                                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#buy-confirm-modal-<?=$c;?>">Pirkti</button>
                                            <?php else: ?>
                                                <button type="button" class="btn btn-primary jas-buy" data-id="<?=$c;?>" data-item='<?php echo json_encode($row); ?>'>Pirkti</button>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                </div>
                            </div>

                        <?php } else {
                            ?>
                                <?php
                                    $group_content = '';
                                    $group_count = 0;
                                    foreach ($row as $group_key => $group_value) {
                                        $group_content .= $group_value->title . ' (' . $group_value->count . ')' . "<br />";
                                        $group_count++;
                                    }
                                ?>
                                <div class="jas-buy item-box pull-left" data-item='<?php echo json_encode($row); ?>'>
                                    <span class="label label-warning price"><?=Currency::format($row['price'], 'price');?></span>

                                    <div class="img pull-left">
                                        <?php echo File::getItemIcon($row['img']); ?>
                                    </div>
                                    <div class="jas-title">
                                        <?=String::truncate($row['title'], 25);?>
                                    </div>

                                    <div class="clearfix"></div>

                                    <div class="jas-buttons">
                                        <a href="javascript: void(0)" class="btn btn-default btn-sm" data-toggle="modal" data-target="#item-<?=$c;?>">Peržiūrėti</a>
                                    </div>
                                </div>

                                <!-- Modal -->
                                <div class="item-modal modal fade" id="item-<?=$c;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                                    <div class="modal-dialog" role="document">
                                        <div class="modal-content">
                                            <div class="modal-header">
                                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                                <h4 class="modal-title" id="myModalLabel"><?=$row['title'];?></h4>
                                            </div>
                                            <div class="modal-body">
                                                <div class="response-c" id="response-<?=$c;?>"></div>

                                                <div class="img pull-left">
                                                    <?php echo File::getItemIcon($row->img, $row->id); ?>
                                                </div>
                                                <div class="info pull-left">
                                                    <ul>
                                                        <li><strong>Prekių grupės kaina:</strong> <?=Currency::format($row['price'], 'price');?></li>
                                                    </ul>

                                                    <div style="margin-top: 15px;">
                                                        <strong>Grupėje esančių prekių informacija</strong>
                                                    </div>

                                                    <ul>
                                                        <?php
                                                            foreach ($row as $group_key => $group_value) {
                                                                ?>
                                                                    <li><?=Item::getTitle($group_value->id, $group_value->title);?> (kiekis: <?=$group_value->count;?>)</li>
                                                                <?php
                                                            }
                                                        ?>
                                                    </ul>
                                                </div>

                                                <div class="clearfix"></div>
                                            </div>
                                            <div class="modal-footer">
                                                <button type="button" class="btn btn-default" data-dismiss="modal">Uždaryti</button>

                                                <?php if (Settings::get('app.shop.buy_confirmation')) : ?>
                                                    <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#buy-confirm-modal-<?=$c;?>">Pirkti</button>
                                                <?php else: ?>
                                                    <button type="button" class="btn btn-primary jas-buy" data-id="<?=$c;?>" data-item='<?php echo json_encode($row); ?>'>Pirkti</button>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </div>
                                </div>

                            <?php

                        } ?>
                    </div>

                    <?php if (Settings::get('app.shop.buy_confirmation')) : ?>
                        <div class="item-modal modal fade" id="buy-confirm-modal-<?=$c;?>" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
                          <div class="modal-dialog" role="document">
                            <div class="modal-content">
                              <div class="modal-header">
                                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                                <h4 class="modal-title" id="myModalLabel"><?=Language::_('Pirkimo patvirtinimas');?></h4>
                              </div>
                              <div class="modal-body">
                                  <?=Language::_('Ar tikrai norite pirkti šią prekę?');?>
                              </div>
                              <div class="modal-footer">
                                <button type="button" class="btn btn-default" data-dismiss="modal"><?=Language::_('Ne');?></button>
                                <button type="button" class="btn btn-primary jas-buy" data-id="<?=$c;?>" data-item='<?php echo json_encode($row); ?>'><?=Language::_('Taip');?></button>
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
                <div class="alert alert-info">Nepasirinktas veikėjas</div>
            <?php endif; ?>
        </div>
    </div>
</div>
