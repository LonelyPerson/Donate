<?php include ROOT_PATH . '/views/user_menu.inc.php'; ?>

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
                                        <?php if (Session::get('character_obj_id') == $row->$SqlObjId) { ?>
                                            <a href="javascript: void(0)" class="select-char selected"><?php echo $row->$SqlCharName; ?></a>
                                        <?php } else { ?>
                                            <a href="javascript: void(0)" class="select-char"><?php echo $row->$SqlCharName; ?></a>
                                        <?php } ?>
                                    </td>
                                </tr>
                            <?php
                        }
                    ?>
                </tbody>
            </table>
        <?php } else { ?>
            <div class="alert alert-info"><?=Language::_('Nėra nė vieno veikėjo');?></div>
        <?php } ?>
    </div>
</div>