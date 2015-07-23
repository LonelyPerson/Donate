<?php
    include VIEWS_PATH . '/user_menu.inc.php';

    if ( ! Settings::get('app.shop.enabled'))
        exit('Shop disabled');
?>

<div class="panel panel-default pull-left left-side shop">
    <div class="panel-heading"><?=Language::_('Parduotuvė');?></div>
    <div class="panel-body">
        <div id="response"></div>

        <div>
            <?php $c = 0; foreach ($items as $key => $row) { ?>
                <div class="p" <?=(Settings::get('app.shop.per_page') && $c >= Settings::get('app.shop.per_page')) ? 'style="display: none;"' : '';?> id="<?=$c;?>">
                    <?php if ($key == 'item') { ?>
                        <?php if (Settings::get('app.shop.buy_confirmation')): ?>
                            <div class="item-box pull-left" data-toggle="tooltip" title="<?=$row->title;?>">
                        <?php else: ?>
                            <div class="jas-buy item-box pull-left" data-toggle="tooltip" title="<?=$row->title;?>" data-item='<?php echo json_encode($row); ?>'>
                        <?php endif; ?>
                            <div class="overlay">
                                <div class="title"><?=Language::_('Pirkti');?> <i class="fa fa-shopping-cart"></i></div>
                            </div>
                            <div class="img pull-left">
                                <?php echo File::getItemIcon($row->img); ?>
                            </div>
                            <div class="jas-buy-box pull-left" style="margin-left: 10px;">
                                <ul>
                                    <li><strong><?=Language::_('Pavadinimas');?>:</strong> <?=String::truncate($row->title, 50);?></li>
                                    <li><strong><?=Language::_('Kaina');?>:</strong> <?=$row->price;?></li>
                                    <li><strong><?=Language::_('Kiekis');?>:</strong> <?=$row->count;?></li>
                                </ul>
                            </div>
                            <div class="clearfix"></div>
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
                            <?php if (Settings::get('app.shop.buy_confirmation')): ?>
                                <div class="item-box pull-left" data-toggle="tooltip" title="<?=$group_content;?>">
                            <?php else: ?>
                                <div class="jas-buy item-box pull-left" data-toggle="tooltip" title="<?=$group_content;?>" data-item='<?php echo json_encode($row); ?>'>
                            <?php endif; ?>
                                <div class="overlay">
                                    <div class="title"><?=Language::_('Pirkti');?> <i class="fa fa-shopping-cart"></i></div>
                                </div>

                                <div class="img pull-left">
                                    <?php echo File::getItemIcon($row['img']); ?>
                                </div>
                                <div class="jas-buy-box pull-left" style="margin-left: 10px;">
                                    <ul>
                                        <li><strong><?=Language::_('Pavadinimas');?>:</strong> <?=String::truncate($row['title'], 25);?></li>
                                        <li>

                                            <strong><?=Language::_('Daiktų kiekis');?>:</strong> <?=$group_count;?>

                                        </li>
                                        <li><strong><?=Language::_('Kaina');?>:</strong> <?=$row['price'];?></li>
                                    </ul>
                                </div>
                            </div>
                        <?php

                    } ?>
                </div>

                <?php if (Settings::get('app.shop.buy_confirmation')) : ?>
                    <div class="modal fade" id="buy-confirm-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
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
                            <button type="button" class="btn btn-primary jas-buy" data-item='<?php echo json_encode($row); ?>'><?=Language::_('Taip');?></button>
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
        </div>
    </div>
</div>
