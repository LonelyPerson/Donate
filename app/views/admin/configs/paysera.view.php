<div class="block config">
	<div class="panel panel-default">
		<div class="panel-heading">Paysera</div>
		<div class="panel-body">
			<form action="<?=route('admin/config/' . $segment . '/save');?>" method="post">
				<?=Output::formResponse();?>

				<?php foreach ($configs as $row) : ?>
					<?php $key = str_replace('.', '|', $row->param_key); ?>

					<label><?=$row->title;?></label>
					<?php if ($row->input_type == 'text') : ?>
						<input type="text" name="<?=$key;?>" class="form-control" value="<?=$row->param_value;?>" />
					<?php elseif ($row->input_type == 'textarea'): ?>
						<textarea name="<?=$key;?>" class="form-control"><?=$row->param_value;?></textarea>
					<?php else: ?>
						<select name="<?=$key;?>" class="form-control">
							<?php
								$selectList = explode(',', $row->input_select_list);
								foreach ($selectList as $option) {
									$option = explode(':', $option);
									if ($option[1] == $row->param_value)
										$selected = 'selected="selected"';
									else
										$selected = '';
									?>	
										<option value="<?=$option[1];?>" <?=$selected;?>><?=$option[0];?></option>
									<?php
								}
							?>
						</select>
					<?php endif ?>
					<p class="description"><?=$row->description;?></p>

				<?php endforeach; ?>

				<input type="submit" name="submit" class="btn btn-primary" value="Save" />
			</form>
		</div>
	</div>
</div>