<?php include VIEWS_PATH . '/user_menu.inc.php'; ?>


<div class="panel panel-default pull-left left-side balance player">
    <div class="panel-heading"><?=Language::_('Veikėjas');?></div>

    <div class="panel-body">
        <?php if (Player::isSelected()) : ?>

            <ul class="nav nav-tabs">
                <?php if (Settings::get('app.player.change_name.enabled')): ?>
                    <li class="active"><a href="#name-tab" data-toggle="tab"><?=Language::_('Slapyvardis');?></a></li>
                <?php endif; ?>

                <?php if (Settings::get('app.player.unstuck.enabled')): ?>
                    <li><a href="#stuck-tab" data-toggle="tab"><?=Language::_('Perkėlimas užstrigus');?></a></li>
                <?php endif; ?>

                <?php if (Settings::get('app.player.level.enabled')): ?>
                    <li><a href="#level-tab" data-toggle="tab"><?=Language::_('Lygis');?></a></li>
                <?php endif; ?>
            </ul>

            <div class="tab-content">
                <div id="response" style="margin-top: 15px;"></div>

                <div class="tab-pane active" id="name-tab">
                    <?php if (Settings::get('app.player.change_name.enabled')): ?>
                        <div class="payment-title"><?=Language::_('Slapyvardžio keitimas');?></div>

                        <div class="payment-info">
                            <p><strong><?=Language::_('Kaina');?>:</strong> <span><?=Currency::format(Settings::get('app.player.change_name.price'), 'price');?></span></p>
                        </div>

                        <form id="jas-name-change-form" class="form-inline">
                            <input type='text' name='new_name' class="form-control" autocomplete='off' placeholder="<?=Language::_('Naujas slapyvardis');?>" />
                            <input type='button' name='jas_change_name_submit' class="btn btn-primary" value="<?=Language::_('Patvirtinti');?>" />
                        </form>
                    <?php endif; ?>
                </div>

                <div class="tab-pane" id="stuck-tab">
                    <?php if (Settings::get('app.player.unstuck.enabled')): ?>
                        <div class="payment-title"><?=Language::_('Perkėlimas užstrigus');?></div>

                        <div class="payment-info">
                            <p><strong><?=Language::_('Kaina');?>:</strong> <span><?=Currency::format(Settings::get('app.player.unstuck.price'), 'price');?></span></p>
                        </div>

                        <form id="jas-unstuck-form" class="form-inline">
                            <input type='button' name='jas_unstuck_submit' class="btn btn-primary" value="<?=Language::_('Perkelti');?>" />
                        </form>
                    <?php endif; ?>
                </div>

                <div class="tab-pane" id="level-tab">
                    <?php if (Settings::get('app.player.level.enabled')): ?>
                        <div class="payment-title"><?=Language::_('Lygio kėlimas');?></div>

                        <div class="payment-info">
                            <p><strong><?=Language::_('Vieno lygio kaina jei lygis aukštesnis');?>:</strong> <span><?=Currency::format(Settings::get('app.player.level.price'), 'price');?></span></p>
                            <p><strong><?=Language::_('Vieno lygio kaina jei lygis žemesnis');?>:</strong> <span><?=Currency::format(Settings::get('app.player.level.delevel_price'), 'price');?></span></p>

                            <p><strong><?=Language::_('Maksimalus lygis');?>:</strong> <span><?=Settings::get('app.player.level.max_level');?></span></p>
                            <p><strong><?=Language::_('Minimalus lygis');?>:</strong> <span><?=Settings::get('app.player.level.min_level');?></span></p>
                            <p><strong><?=Language::_('Dabartinis lygis');?>:</strong> <span><?=$current_level;?></span></p>
                        </div>

                        <form id="jas-level-form" class="form-inline">
                            <input type='text' name='level' class="form-control" autocomplete='off' placeholder="<?=Language::_('Norimas lygis');?>" />
                            <input type='button' name='jas_level_submit' class="btn btn-primary" value="<?=Language::_('Patvirtinti');?>" />
                        </form>
                    <?php endif; ?>
                </div>

            <?php else: ?>
                <div class="alert alert-info">Nepasirinktas veikėjas</div>
            <?php endif; ?>
        </div>
    </div>
</div>
