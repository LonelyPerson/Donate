<?php if ($menu['route'] != 'sep') : ?>
	<li>
		<a href="<?=route($menu['route']);?>">
			<i class="fa <?=$menu['icon'];?>"></i>
			<div class="title"><?=$menu['title'];?></div>
		</a>
		<?php if (isset($menu['sub']) && ! Admin::isHome()) : ?>
			<ul class="submenu">
				<?php foreach ($menu['sub'] as $submenu) : ?>
					<li>
						<a href="<?=route($submenu['route']);?>">
							<i class="fa <?=$submenu['icon'];?>"></i>
							<div class="title"><?=$submenu['title'];?></div>
						</a>
					</li>
				<?php endforeach; ?>
			</ul>
		<?php endif; ?>
	</li>
<?php else: ?>
	</ul>

	<div class="clearfix"></div>
	<hr />

	<ul class="main-nav">
<?php endif; ?>