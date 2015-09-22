<li id="login"><a href="javacript: void(0);" onclick="loadView('login'); return false;"><?=__('Prisijungimas');?> <i class="fa fa-sign-in pull-right"></i></a></li>
<?php if (config('app.registration.enabled')) { ?>
    <li id="registration"><a href="javacript: void(0);" onclick="loadView('registration'); return false;"><?=__('Registration');?> <i class="fa fa-user-plus pull-right"></i></a></li>
<?php } ?>
<?php if (config('app.recovery.in_menu') && config('app.recovery.enabled')) { ?>
    <li id="recovery"><a href="javacript: void(0);" onclick="loadView('recovery'); return false;"><?=__('Password recovery');?> <i class="fa fa-pencil-square-o pull-right"></i></a></li>
<?php } ?>
