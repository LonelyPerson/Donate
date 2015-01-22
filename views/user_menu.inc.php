<div class="pull-right right-side">
<div class="panel panel-default">
    <div class="panel-heading"><?=Language::_('Pasirinktas veikėjas');?></div>
    <div class="panel-body">
        <div id="selected-char"><?=(Session::has('character_name')) ? Session::get('character_name') : Language::_('Veikėjas nepasirinktas'); ?></div>
    </div>
</div>

<div class="panel panel-default">
    <div class="panel-heading"><?=Language::_('Meniu');?></div>
    <div class="panel-body">
        <?php include ROOT_PATH . '/views/languages.inc.php'; ?>

        <ul class="nav nav-pills nav-stacked">
            <li id="user"><a href="javacript: void(0);" onclick="loadView('user'); return false;"><?=Language::_('Veikėjo pasirinkimas');?></a></li>
            <?php if (Settings::get('app.shop.enabled')) { ?><li id="shop"><a href="javacript: void(0);" onclick="loadView('shop'); return false;"><?=Language::_('Parduotuvė');?></a></li><?php } ?>
            <li id="balance"><a href="javacript: void(0);" onclick="loadView('balance'); return false;"><?=Language::_('Balansas');?> (<span><?=Auth::user()->balance?></span>)</a></li>
            <?php if (Settings::get('app.history.enabled')) { ?><li id="history"><a href="javacript: void(0);" onclick="loadView('history'); return false;"><?=Language::_('Istorija');?></a></li><?php } ?>
            <?php if (Settings::get('app.settings.enabled')) { ?><li id="settings"><a href="javacript: void(0);" onclick="loadView('settings'); return false;"><?=Language::_('Nustatymai');?></a></li><?php } ?>
            <li id="logout"><a href="javacript: void(0);" onclick="logout(); return false;"><?=Language::_('Atsijungti');?></a></li>
        </ul>
    </div>
</div>
</div>