<?php
    include ROOT_PATH . '/views/user_menu.inc.php';

    if ( ! Settings::get('app.history.enabled'))
        exit('History disabled');
?>

<div class="panel panel-default left-side">
    <div class="panel-heading"><?=Language::_('Veiksmų istorija');?></div>
    <div class="panel-body">
        
        <?php if ($history) { ?>
            <div class="alert alert-info"><?=Language::_('Istorijoje rodomi paskutinieji %s veiksmų (-ai)', [Settings::get('app.history.limit')]);?></div>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><?=Language::_('Skyrius');?></th>
                        <th><?=Language::_('Veiksmas');?></th>
                        <th><?=Language::_('Data');?></th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($history as $row) { ?>
                        <tr>
                            <td><?php echo $row->action_key; ?></td>
                            <td><span data-toggle="tooltip" data-placement="right" title="<?=$row->action_value;?>"><?php echo String::truncate($row->action_value); ?></span></td>
                            <td style="width: 135px"><?php echo $row->action_date; ?></td>
                        </tr>
                    <?php } ?>
                </tbody>
            </table>
        <?php } else { ?>
            <div class="alert alert-info"><?=Language::_('Istorija tuščia');?></div>
        <?php } ?>
    </div>
</div>
