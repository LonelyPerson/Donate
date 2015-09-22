<div class="block log">
	<div class="panel panel-default">
		<div class="panel-body">
			<?php if ($items) : ?>
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th>Author</th>
							<th>Action</th>
							<th>Date</th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($items as $item) : ?>
							<tr>
								<td><?=$item->author;?></td>
								<td><?=$item->action;?></td>
								<td><?=$item->created_at;?></td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>

				<div style="text-align: center;">
					<?=$pagination;?>
				</div>
			<?php else: ?>
				<div class="alert alert-info" style="margin: 0">No logs found</div>
			<?php endif; ?>
		</div>
	</div>
</div>