<?php include VIEWS_PATH . '/user_menu.inc.php'; ?>

<div class="panel panel-default left-side">
    <div class="panel-heading"><?=Language::_('Veikėjai');?></div>
    <div class="panel-body">
        <div id="response"></div>

        <?php if ($characters) { ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><?=Language::_('Vardas');?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                        foreach ($characters as $row) {
                            ?>
                                <tr>
                                    <td>
                                        <?php if ((Session::has('character_obj_id') && Session::get('character_obj_id') == $row->$SqlObjId) || Player::isOnline($row->$SqlObjId)) { ?>
                                            <a href="javascript: void(0)" class="select-char selected"><?php echo $row->$SqlCharName; ?></a>
                                            <div class="pull-right"><i class="fa fa-lock"></i></div>
                                        <?php } else { ?>
                                            <a href="javascript: void(0)" class="select-char"><?php echo $row->$SqlCharName; ?></a>
                                            <div class="pull-right"><i class="fa fa-unlock"></i></div>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php
                        }
                    ?>
                </tbody>
            </table>
        <?php } else { ?>
            <div class="alert alert-info"><?=Language::_('Veikėjų nėra');?></div>
        <?php } ?>
    </div>
</div>
