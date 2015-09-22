<div class="block shop">
	<div class="panel panel-default">
		<div class="panel-body">
			<?php if ($items) : ?>
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th>Keyword</th>
							<th>Price</th>
							<th>Number</th>
							<th>Payment system</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($items as $row) : ?>
							<tr>
								<td><?=$row->keyword;?></td>
								<td><?=Currency::format($row->price, 'price');?></td>
								<td><?=$row->phone;?></td>
								<td><?=$row->type;?></td>
								<td><a href="<?=route('admin/sms-keywords/keyword/delete/' . $row->id);?>">Delete</a></td>
								<td><a href="<?=route('admin/sms-keywords/keyword/' . $row->id);?>">Edit</a></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>

				<div style="text-align: center;">
					<?=$pagination;?>
				</div>
			<?php else: ?>
				<div class="alert alert-info" style="margin: 0">No keywords found</div>
			<?php endif; ?>
		</div>
	</div>
</div>