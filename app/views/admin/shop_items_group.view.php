<div class="block shop">
	<div class="panel panel-default">
		<div class="panel-body">
			<?=Output::formResponse();?>

			<div class="items-group-base" style="display: none">
				<div class="pull-left">
					<label>Title (not required)</label>
					<input type="text" name="title[]" class="form-control" value="<?=$item->title;?>" />
				</div>

				<div class="pull-left" style="margin-left: 10px;">
					<label>Item ID</label>
					<input type="text" name="item_id[]" class="form-control" value="<?=$item->item_id;?>" />
				</div>

				<div class="pull-left" style="margin-left: 10px;">
					<label>Price</label>
					<input type="text" name="price[]" class="form-control" value="<?=$item->price;?>" />
				</div>

				<div class="pull-left" style="margin-left: 10px;">
					<label>Quantity</label>
					<input type="text" name="quantity[]" class="form-control" value="<?=$item->quantity;?>" />
				</div>

				<div class="pull-right minus" style="margin-left: 10px;">
					<a href="#"><i class="fa fa-minus-square"></i></a>
				</div>

				<div class="clearfix"></div>
			</div>

			<form action="<?=route('admin/shop/item-group');?>" method="post">
				<input type="hidden" name="id" value="<?=$id;?>" />

				<?php if ($items) : ?>
					<?php $counter = 1; foreach ($items as $item) : ?>

						<div class="items-group">
							<div class="pull-left">
								<label>Title (not required)</label>
								<input type="text" name="title[]" class="form-control" value="<?=$item->title;?>" />
							</div>

							<div class="pull-left" style="margin-left: 10px;">
								<label>Item ID</label>
								<input type="text" name="item_id[]" class="form-control" value="<?=$item->item_id;?>" />
							</div>

							<div class="pull-left" style="margin-left: 10px;">
								<label>Price</label>
								<input type="text" name="price[]" class="form-control" value="<?=$item->price;?>" />
							</div>

							<div class="pull-left" style="margin-left: 10px;">
								<label>Quantity</label>
								<input type="text" name="quantity[]" class="form-control" value="<?=$item->quantity;?>" />
							</div>

							<?php if ($counter > 1) : ?>

								<div class="pull-right minus" style="margin-left: 10px;">
									<a href="#"><i class="fa fa-minus-square"></i></a>
								</div>

							<?php endif; ?>

							<div class="clearfix"></div>
						</div>

					<?php ++$counter; endforeach; ?>
				<?php else: ?>

					<div class="items-group">
						<div class="pull-left">
							<label>Title (not required)</label>
							<input type="text" name="title[]" class="form-control" value="<?=$item->title;?>" />
						</div>

						<div class="pull-left" style="margin-left: 10px;">
							<label>Item ID</label>
							<input type="text" name="item_id[]" class="form-control" value="<?=$item->item_id;?>" />
						</div>

						<div class="pull-left" style="margin-left: 10px;">
							<label>Price</label>
							<input type="text" name="price[]" class="form-control" value="<?=$item->price;?>" />
						</div>

						<div class="pull-left" style="margin-left: 10px;">
							<label>Quantity</label>
							<input type="text" name="quantity[]" class="form-control" value="<?=$item->quantity;?>" />
						</div>

						<div class="clearfix"></div>
					</div>

				<?php endif; ?>

				<input type="submit" name="submit" class="btn btn-primary" style="margin-top: 10px;" value="Confirm" />
				<a href="#" class="btn btn-primary add" style="margin-top: 10px;">Add</a>
			</form>
		</div>
	</div>
</div>