<?php include VIEWS_PATH . '/user_menu.inc.php'; ?>

<div class="panel panel-default left-side">
    <div class="panel-heading"><?=__('Characters');?></div>
    <div class="panel-body">
        <div id="response"></div>

        <?php if ($characters) { ?>
            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><?=__('Char name');?></th>
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
            <div class="alert alert-info"><?=__('No characters found');?></div>
        <?php } ?>
    </div>
</div>
