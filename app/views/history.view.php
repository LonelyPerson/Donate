<?php
    include VIEWS_PATH . '/user_menu.inc.php';

    if ( ! config('app.history.enabled'))
        exit('History disabled');
?>

<div class="panel panel-default left-side">
    <div class="panel-heading"><?=__('History');?></div>
    <div class="panel-body">

        <?php if ($history) { ?>
            <div class="alert alert-info" style="margin-bottom: 15px;"><?=__('Showing % last actions', [config('app.history.limit')]);?></div>

            <table class="table table-striped">
                <thead>
                    <tr>
                        <th><?=__('Section');?></th>
                        <th><?=__('Action');?></th>
                        <th><?=__('Date');?></th>
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
            <div class="alert alert-info"><?=__('History is empty');?></div>
        <?php } ?>
    </div>
</div>
