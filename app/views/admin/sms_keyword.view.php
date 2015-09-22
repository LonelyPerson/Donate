<div class="block shop">
	<div class="panel panel-default">
		<div class="panel-body">
			<?=Output::formResponse();?>

			<form action="<?=route('admin/sms-keywords/keyword');?>" method="post">
				<input type="hidden" name="id" value="<?=$id;?>" />

				<label>Keyword</label>
				<input type="text" name="keyword" class="form-control" value="<?=$item->keyword;?>" />
				<p></p>

				<label>Number</label>
				<input type="text" name="number" class="form-control" value="<?=$item->phone;?>" />
				<p></p>

				<label>Country code</label>
				<input type="text" name="country" class="form-control" value="<?=$item->country;?>" />
				<p></p>

				<label>Currency code</label>
				<input type="text" name="currency" class="form-control" value="<?=$item->currency;?>" />
				<p></p>

				<label>Price</label>
				<input type="text" name="price" class="form-control" value="<?=$item->price;?>" />
				<p></p>

				<label>Credits</label>
				<input type="text" name="points" class="form-control" value="<?=$item->points;?>" />
				<p></p>

				<label>Response text</label>
				<input type="text" name="response" class="form-control" value="<?=$item->response;?>" />
				<p></p>

				<label>Payment system</label>
				<select name="type" class="form-control">
					<?=$types;?>
				</select>
				<p></p>

				<input type="submit" name="submit" class="btn btn-primary" style="margin-top: 10px;" value="Confirm" />
			</form>
		</div>
	</div>
</div>