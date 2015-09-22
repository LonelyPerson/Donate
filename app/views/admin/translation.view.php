<div class="block translation">
	<div class="panel panel-default">
		<div class="panel-body">
			<?=Output::formResponse();?>

			<label>Select language</label>
			<select name="language" class="form-control">
				<option value="0">Select language</option>
				<?=$options;?>
			</select>

			<a href="#" class="btn btn-primary select-translation" style="margin-top: 10px;">Edit translations</a>
			<a href="#" class="btn btn-primary delete-language" style="margin-top: 10px;">Delete language</a>
		</div>
	</div>
</div>