<div class="panel panel-default pull-right right-side">
    <div class="bg"><img src="<?=Settings::get('app.img');?>/human.png" /></div>

    <div class="panel-heading"><?=Language::_('Meniu');?></div>
    <div class="panel-body">
        <?php include ROOT_PATH . '/views/languages.inc.php'; ?>

        <ul class="nav nav-pills nav-stacked">
            <li id="login"><a href="javacript: void(0);" onclick="loadView('login'); return false;"><?=Language::_('Prisijungimas');?> <i class="fa fa-sign-in pull-right"></i></a></li>
            <?php if (Settings::get('app.registration.enabled')) { ?>
                <li id="registration"><a href="javacript: void(0);" onclick="loadView('registration'); return false;"><?=Language::_('Registracija');?> <i class="fa fa-user-plus pull-right"></i></a></li>
            <?php } ?>
            <?php if (Settings::get('app.recovery.in_menu') && Settings::get('app.recovery.enabled')) { ?>
                <li id="recovery"><a href="javacript: void(0);" onclick="loadView('recovery'); return false;"><?=Language::_('Slaptažodžio atstatymas');?> <i class="fa fa-pencil-square-o pull-right"></i></a></li>
            <?php } ?>
        </ul>
    </div>
</div>
