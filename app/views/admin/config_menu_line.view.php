<?php if ($menu['route'] != 'sep') : ?>
	<li>
		<a href="<?=route($menu['route']);?>">
			<i class="fa <?=$menu['icon'];?>"></i>
			<div class="title"><?=$menu['title'];?></div>
		</a>
	</li>
<?php else: ?>
	</ul>

	<div class="clearfix"></div>
	<hr />

	<ul class="main-nav">
<?php endif; ?>