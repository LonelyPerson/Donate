<div class="block translation">
	<div class="panel panel-default">
		<div class="panel-body">
			<?=Output::formResponse();?>
		
			<form action="<?=route('admin/translation/save');?>" method="post">
				<input type="hidden" name="language" value="<?=$language;?>" />

				<?php foreach ($translations as $key => $translation) : ?>

					<label><?=$key;?></label>
					<input type="text" class="form-control" name="<?=$key;?>" value="<?=$translation;?>" />
					<p></p>

				<?php endforeach; ?>

				<input type="submit" name="submit" class="btn btn-primary" value="Save" />
			</form>
		</div>
	</div>
</div>