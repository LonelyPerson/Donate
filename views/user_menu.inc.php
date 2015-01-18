<div class="panel panel-default pull-right right-side">
    <div class="panel-heading"><?=Language::_('Meniu');?></div>
    <div class="panel-body">
        <ul class="nav nav-pills nav-stacked">
            <li id="user"><a href="javacript: void(0);" onclick="loadView('user'); return false;"><?=Language::_('Žaidėjo pasirinkimas');?></a></li>
            <li id="balance"><a href="javacript: void(0);" onclick="loadView('balance'); return false;"><?=Language::_('Balansas');?> (<span><?=Auth::user()->balance?></span>)</a></li>
            <li id="history"><a href="javacript: void(0);" onclick="loadView('history'); return false;"><?=Language::_('Istorija');?></a></li>
            <li id="logout"><a href="javacript: void(0);" onclick="logout(); return false;"><?=Language::_('Atsijungti');?></a></li>
        </ul>
    </div>
</div>