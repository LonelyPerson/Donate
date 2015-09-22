<div class="block shop">
	<div class="panel panel-default">
		<div class="panel-body">
			<?php if ($items) : ?>
				<table class="table table-striped table-hover">
					<thead>
						<tr>
							<th>#</th>
							<th>Title</th>
							<th>Price</th>
							<th>Quantity</th>
							<th></th>
						</tr>
					</thead>
					<tbody>
						<?php foreach ($items as $row) : ?>
							<tr>
								<td>
									<?php if ($row->is_group) : ?>
										<i class="fa fa-cubes" style="font-size: 20px;"></i>
									<?php else: ?>
										<?=Item::getIcon($row->icon);?>
									<?php endif; ?>
								</td>
								<td>
									<?php if ($row->is_group) : ?>
										Items group
									<?php else: ?>
										<?=Item::getTitle($row->title, $row->item_name);?>
									<?php endif; ?>
									
								</td>
								<td>
									<?php if ($row->is_group) : ?>
										#
									<?php else: ?>
										<?=Currency::format($row->price, 'price');?>
									<?php endif; ?>
								</td>
								<td>
									<?php if ($row->is_group) : ?>
										#
									<?php else: ?>
										<?=Currency::format($row->quantity, 'simple');?>
									<?php endif; ?>
								</td>
								<td>
									<?php if ($row->is_group) : ?>
										<a href="<?=route('admin/shop/item/delete-group/' . $row->group_id);?>">Delete</a>
									<?php else: ?>
										<a href="<?=route('admin/shop/item/delete/' . $row->id);?>">Delete</a>
									<?php endif; ?>
								</td>
								<td>
									<?php if ($row->is_group) : ?>
										<a href="<?=route('admin/shop/item-group/' . $row->group_id);?>">Edit</a>
									<?php else: ?>
										<a href="<?=route('admin/shop/item/' . $row->id);?>">Edit</a>
									<?php endif; ?>
									
								</td>
							</tr>
						<?php endforeach; ?>
					</tbody>
				</table>

				<div style="text-align: center;">
					<?=$pagination;?>
				</div>
			<?php else: ?>
				<div class="alert alert-info" style="margin: 0">No items found</div>
			<?php endif; ?>
		</div>
	</div>
</div>