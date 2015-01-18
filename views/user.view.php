<?php 
    include ROOT_PATH . '/views/user_menu.inc.php'; 
    include ROOT_PATH . '/views/languages.inc.php';
?>

<div class="panel panel-default left-side">
    <div class="panel-heading"><?=Language::_('Veikėjai');?></div>
    <div class="panel-body">
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
                                    <td><a href="javascript: void(0)" onclick="selectCharacter('<?php echo $row->obj_Id; ?>'); return false;"><?php echo $row->char_name; ?></a></td>
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