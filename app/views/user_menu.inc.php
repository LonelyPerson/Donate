<div class="pull-right right-side">
    <div class="bg"><img src="<?=Settings::get('app.img');?>/human.png" /></div>

    <div class="panel panel-default">
        <div class="panel-heading"><?=Language::_('Pasirinktas veikėjas');?></div>
        <div class="panel-body">
            <div id="selected-char"><?=(Session::has('character_name')) ? Session::get('character_name') : Language::_('Veikėjas nepasirinktas'); ?></div>
        </div>
    </div>

    <div class="panel panel-default">
        <div class="panel-heading"><?=Language::_('Meniu');?></div>
        <div class="panel-body">
            <?php include VIEWS_PATH . '/views/languages.inc.php'; ?>

            <ul class="nav nav-pills nav-stacked">
                <li id="user"><a href="javacript: void(0);" onclick="loadView('user'); return false;"><?=Language::_('Veikėjo pasirinkimas');?> <i class="fa fa-user pull-right"></i></a></li>
                <?php if (Settings::get('app.inventory.enabled')) { ?>
                    <li id="inventory">
                        <a href="javacript: void(0);" onclick="loadView('inventory'); return false;"><?=Language::_('Inventorius');?> <i class="fa fa-suitcase pull-right"></i></a>
                    </li>
                <?php } ?>
                <?php if (Settings::get('app.shop.enabled')) { ?>
                    <li id="shop">
                        <a href="javacript: void(0);" onclick="loadView('shop'); return false;"><?=Language::_('Parduotuvė');?> <i class="fa fa-shopping-cart pull-right"></i></a>
                    </li>
                <?php } ?>
                <li id="balance"><a href="javacript: void(0);" onclick="loadView('balance'); return false;"><?=Language::_('Balansas');?> (<span><?=Currency::format(Auth::user()->balance, 'balance');?></span>) <i class="fa fa-money pull-right"></i></a></li>
                <?php if (Settings::get('app.history.enabled')) { ?><li id="history"><a href="javacript: void(0);" onclick="loadView('history'); return false;"><?=Language::_('Istorija');?> <i class="fa fa-list pull-right"></i></a></li><?php } ?>
                <?php if (Settings::get('app.settings.enabled')) { ?><li id="settings"><a href="javacript: void(0);" onclick="loadView('settings'); return false;"><?=Language::_('Nustatymai');?> <i class="fa fa-cogs pull-right"></i></a></li><?php } ?>
                <li id="logout"><a href="javacript: void(0);" onclick="logout(); return false;"><?=Language::_('Atsijungti');?> <i class="fa fa-sign-out pull-right"></i></a></li>
            </ul>
        </div>
    </div>
</div>
