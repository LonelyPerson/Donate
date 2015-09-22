<?php if ( ! defined('STARTED')) exit('error'); ?>
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8" />
        <meta name="author" content="Justas Ašmanavičius" />

        <title>DS install</title>

        <link href='http://fonts.googleapis.com/css?family=Roboto:400,300,700&subset=latin,latin-ext,cyrillic-ext' rel='stylesheet' type='text/css'>

        <link rel="stylesheet" href="<?=config('app.css');?>/bootstrap.min.css" />
        <link rel="stylesheet" href="<?=config('app.css');?>/font-awesome.min.css" />
        <link rel="stylesheet" href="<?=config('app.css');?>/style.css" />

        <script src="<?=config('app.js');?>/libs/jquery-1.10.2.js"></script>
        <script src="<?=config('app.js');?>/libs/bootstrap.min.js"></script>
        <script src="<?=config('app.js');?>/libs/blockui.js"></script>

        <script src="<?=config('app.js');?>/libs/functions.js"></script>
        <script src="<?=config('app.js');?>/libs/log.js"></script>

        <script src="<?=config('app.js');?>/install.js"></script>

        <script>
            var gVar = [];
            gVar['base-url'] = "<?=config('app.base_url');?>";
        </script>
    </head>
    <body>
        <div id="jas-content" class="install">
            <div class="panel panel-default">
                <div class="panel-heading">
                    System install
                </div>
                <div class="panel-body">
                    <p class="description">
                        Please fill database configs and grant access permissions to certain folders, then start system check.
                        <br />
                        These folders needs 755 access permissions (chmod)
                        1. app/storage<br />
                        2. app/languages

                        <br />

                        <h4><?=Language::_('Nustatymai');?></h4>
                        <strong>app/config/database.php</strong> - database configs<br />
                        <strong>app/config/server.php</strong> - server configs<br />
                        <strong>app/config/sql.php</strong> - sql configs<br />
                    </p>

                    <h4>System check</h4>
                    <div class="check">
                        <div class="db-status" style="display: none">0</div>
                        <div class="chmod-status" style="display: none">0</div>

                        <p class="req db-connection">
                            <strong>Database connection:</strong>
                            <span class="loader">Checking <i class="fa fa-cog fa-spin"></i></span>
                            <span class="yes" style="display: none">success <i class="fa fa-check"></i></span>
                            <span class="no" style="display: none">error</span>
                        </p>
                        <p class="req storage-chmod">
                            <strong>Folder "app/storage" write rights (chmod):</strong>
                            <span class="loader">checking <i class="fa fa-cog fa-spin"></i></span>
                            <span class="yes" style="display: none;">success <i class="fa fa-check"></i></span>
                            <span class="no" style="display: none;">error</span>
                        </p>
                    </div>
                    <a href="javascript: void(0)" id="check" class="btn btn-primary btn-sm">Start system check</a>

                    <div class="install-progress" style="display: none">
                        <h4>Install</h4>
                        <p class="wait">Please wait while system installing <i class="fa fa-cog fa-spin"></i></p>
                        <p class="success" style="display: none">System successfully installed <i class="fa fa-check"></i></p>
                    </div>

                    <div class="nav-buttons" style="display: none">
                        <a href="javascript: void(0)" class="btn btn-primary" id="install">Install</a>
                        <a href="<?=config('app.base_url');?>" class="btn btn-default" id="end" style="display: none">Finish</a>
                    </div>

                    <div class="clearfix"></div>
                </div>
            </div>

            <div class="clearfix"></div>
        </div>
    </body>
</html>
