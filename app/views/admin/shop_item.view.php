<div class="block shop">
	<div class="panel panel-default">
		<div class="panel-body">
			<?=Output::formResponse();?>

			<form action="<?=route('admin/shop/item');?>" method="post">
				<input type="hidden" name="id" value="<?=$id;?>" />

				<label>Title (not required)</label>
				<input type="text" name="title" class="form-control" value="<?=$item->title;?>" />
				<p></p>

				<label>Item ID</label>
				<input type="text" name="item_id" class="form-control" value="<?=$item->item_id;?>" />
				<p></p>

				<label>Price</label>
				<input type="text" name="price" class="form-control" value="<?=$item->price;?>" />
				<p class="description">Price in DC</p>
				
				<label>Quantity</label>
				<input type="text" name="quantity" class="form-control" value="<?=$item->quantity;?>" />
				<p></p>

				<input type="submit" name="submit" class="btn btn-primary" style="margin-top: 10px;" value="Confirm" />
			</form>
		</div>
	</div>
</div>