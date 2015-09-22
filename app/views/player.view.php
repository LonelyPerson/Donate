<?php include VIEWS_PATH . '/user_menu.inc.php'; ?>


<div class="panel panel-default pull-left left-side balance player">
    <div class="panel-heading"><?=__('Player');?></div>

    <div class="panel-body">
        <?php if (Player::isSelected()) : ?>

            <ul class="nav nav-tabs">
                <?php if (config('app.player.change_name.enabled')): ?>
                    <li class="active"><a href="#name-tab" data-toggle="tab"><?=__('Change username');?></a></li>
                <?php endif; ?>

                <?php if (config('app.player.unstuck.enabled')): ?>
                    <li><a href="#stuck-tab" data-toggle="tab"><?=__('Unstuck');?></a></li>
                <?php endif; ?>

                <?php if (config('app.player.level.enabled')): ?>
                    <li><a href="#level-tab" data-toggle="tab"><?=__('Change level');?></a></li>
                <?php endif; ?>
            </ul>

            <div class="tab-content">
                <div id="response" style="margin-top: 15px;"></div>

                <div class="tab-pane active" id="name-tab">
                    <?php if (config('app.player.change_name.enabled')): ?>
                        <div class="payment-title"><?=__('Change username');?></div>

                        <div class="payment-info">
                            <p><strong><?=__('Price');?>:</strong> <span><?=Currency::format(config('app.player.change_name.price'), 'price');?></span></p>
                        </div>

                        <form id="jas-name-change-form" class="form-inline">
                            <input type='text' name='new_name' class="form-control" autocomplete='off' placeholder="<?=__('New username');?>" />
                            <input type='button' name='jas_change_name_submit' class="btn btn-primary" value="<?=__('Confirm');?>" />
                        </form>
                    <?php endif; ?>
                </div>

                <div class="tab-pane" id="stuck-tab">
                    <?php if (config('app.player.unstuck.enabled')): ?>
                        <div class="payment-title"><?=__('Unstuck');?></div>

                        <div class="payment-info">
                            <p><strong><?=__('Price');?>:</strong> <span><?=Currency::format(config('app.player.unstuck.price'), 'price');?></span></p>
                        </div>

                        <form id="jas-unstuck-form" class="form-inline">
                            <input type='button' name='jas_unstuck_submit' class="btn btn-primary" value="<?=__('Confirm');?>" />
                        </form>
                    <?php endif; ?>
                </div>

                <div class="tab-pane" id="level-tab">
                    <?php if (config('app.player.level.enabled')): ?>
                        <div class="payment-title"><?=__('Change level');?></div>

                        <div class="payment-info">
                            <p><strong><?=__('Price for one level if leveling up');?>:</strong> <span><?=Currency::format(config('app.player.level.price'), 'price');?></span></p>
                            <p><strong><?=__('Price for one level if leveling down');?>:</strong> <span><?=Currency::format(config('app.player.level.delevel_price'), 'price');?></span></p>

                            <p><strong><?=__('Max. level');?>:</strong> <span><?=config('app.player.level.max_level');?></span></p>
                            <p><strong><?=__('Min. level');?>:</strong> <span><?=config('app.player.level.min_level');?></span></p>
                            <p><strong><?=__('Current level');?>:</strong> <span><?=$current_level;?></span></p>
                        </div>

                        <form id="jas-level-form" class="form-inline">
                            <input type='text' name='level' class="form-control" autocomplete='off' placeholder="<?=__('New level');?>" />
                            <input type='button' name='jas_level_submit' class="btn btn-primary" value="<?=__('Confirm');?>" />
                        </form>
                    <?php endif; ?>
                </div>

            <?php else: ?>
                <div class="alert alert-info">Character is not selected</div>
            <?php endif; ?>
        </div>
    </div>
</div>
