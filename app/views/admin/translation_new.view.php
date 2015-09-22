<div class="block translation">
	<div class="panel panel-default">
		<div class="panel-body">
			<?=Output::formResponse();?>

			<form action="<?=route('admin/translation/add');?>" method="post">
				<label>Language code</label>
				<input type="text" name="language" class="form-control" />
				<p class="description">eg. ru</p>

				<input type="submit" name="submit" class="btn btn-primary" value="Create" />
			</form>
		</div>
	</div>
</div>