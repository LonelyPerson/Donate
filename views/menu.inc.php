<div class="panel panel-default pull-right right-side">
    <div class="panel-heading"><?=Language::_('Meniu');?></div>
    <div class="panel-body">
        <?php include ROOT_PATH . '/views/languages.inc.php'; ?>

        <ul class="nav nav-pills nav-stacked">
            <li id="login"><a href="javacript: void(0);" onclick="loadView('login'); return false;"><?=Language::_('Prisijungimas');?></a></li>
            <?php if (Settings::get('app.registration.enabled')) { ?>
                <li id="registration"><a href="javacript: void(0);" onclick="loadView('registration'); return false;"><?=Language::_('Registracija');?></a></li>
            <?php } ?>
        </ul>
    </div>
</div>
