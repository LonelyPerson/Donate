<div class="block user">
	<div class="panel panel-default">
		<div class="panel-body">
			<table class="table table-striped table-hover">
				<thead>
					<tr>
						<th>Username</th>
						<th>Balance</th>
						<th>Server</th>
						<th>Level</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php foreach ($users as $user) : ?>
						<?php $characters = Player::getCharacters($user->username); ?>

						<tr>
							<td><?=$user->username;?></td>
							<td><?=Currency::format($user->balance, 'balance');?></td>
							<td><?=$servers[$user->server]['title'];?></td>
							<td><?=($user->access == 0) ? 'Simple' : 'Administrator' ;?></td>
							<td>
								<?php if ($characters) : ?>
									<a href="#" class="show-characters" data-id="<?=$user->username;?>">Show characters</a>
								<?php endif; ?>
							</td>
						</tr>
						
						<?php if ($characters) : ?>
							<tr>
								<table class="table" style="display: none !important;" id="<?=$user->username;?>">
									<tr>
										<td>
											<div class="panel panel-default">
												<div class="panel-heading">Account characters</div>
												<div class="panel-body">
													<?php foreach ($characters as $row) : ?>

														<?=$row['char_name'];?> (level: <?=$row['level'];?>)<br />

													<?php endforeach; ?>
												</div>
											</div>
										</td>
									</tr>
								</table>
							</tr>
						<?php endif; ?>

					<?php endforeach; ?>
				</tbody>
			</table>

			<div style="text-align: center;">
				<?=$pagination;?>
			</div>
		</div>
	</div>
</div>