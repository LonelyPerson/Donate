<div class="panel panel-default">
    <div class="panel-heading">Pagrindinis</div>
    <div class="panel-body">
        <a href="javacript: void(0);" class="btn btn-primary" onclick="loadView('login'); return false;">Prisijungimas</a>
        <?php if (Settings::get('app.registration.enabled')) { ?>
            <a href="javacript: void(0);" class="btn btn-primary" onclick="loadView('registration'); return false;">Registracija</a> 
        <?php } ?>
    </div>
</div>
