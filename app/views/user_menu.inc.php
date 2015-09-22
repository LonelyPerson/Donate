<div class="pull-right right-side">
    <div class="bg"><img src="<?=config('app.img');?>/human.png" /></div>

    <div class="panel panel-default">
        <div class="panel-heading"><?=__('Selected character');?></div>
        <div class="panel-body">
            <div id="selected-char"><?=(Session::has('character_name')) ? Session::get('character_name') : __('Not selected'); ?></div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading"><?=__('Menu');?></div>
        <div class="panel-body">
            <?php include VIEWS_PATH . '/languages.inc.php'; ?>

            <ul class="nav nav-pills nav-stacked">
                <li id="user"><a href="javacript: void(0);" onclick="loadView('user'); return false;"><?=__('Character selection');?> <i class="fa fa-user pull-right"></i></a></li>
                <?php if (config('app.player.enabled')) { ?>
                    <li id="player">
                        <a href="javacript: void(0);" onclick="loadView('player'); return false;"><?=__('Player services');?> <i class="fa fa-shopping-cart pull-right"></i></a>
                    </li>
                <?php } ?>
                <?php if (config('app.inventory.enabled')) { ?>
                    <li id="inventory">
                        <a href="javacript: void(0);" onclick="loadView('inventory'); return false;"><?=__('Inventory');?> <i class="fa fa-suitcase pull-right"></i></a>
                    </li>
                <?php } ?>
                <?php if (config('app.shop.enabled')) { ?>
                    <li id="shop">
                        <a href="javacript: void(0);" onclick="loadView('shop'); return false;"><?=__('Shop');?> <i class="fa fa-shopping-cart pull-right"></i></a>
                    </li>
                <?php } ?>
                <li id="balance">
                    <a href="javacript: void(0);" onclick="loadView('balance'); return false;"><?=__('Balance');?> (<span><?=Currency::format(Auth::user()->balance, 'balance');?></span>) <i class="fa fa-money pull-right"></i></a>
                </li>
                <?php if (config('app.history.enabled')) { ?>
                    <li id="history"><a href="javacript: void(0);" onclick="loadView('history'); return false;"><?=__('History');?> <i class="fa fa-list pull-right"></i></a></li>
                <?php } ?>
                <?php if (config('app.settings.enabled')) { ?>
                    <li id="settings"><a href="javacript: void(0);" onclick="loadView('settings'); return false;"><?=__('Settings');?> <i class="fa fa-cogs pull-right"></i></a></li>
                <?php } ?>

                <?php if (Admin::hasAccess()) { ?><li id="god-hand"><a href="<?=route('admin');?>" target="_blank"><?=__('God hand');?> <i class="fa fa-hand-spock-o pull-right"></i></a></li><?php } ?>

                <li id="logout"><a href="javacript: void(0);" onclick="logout(); return false;"><?=__('Logout');?> <i class="fa fa-sign-out pull-right"></i></a></li>
            </ul>
        </div>
    </div>
</div>
