<?php
defined( '_JEXEC' ) or die;
$background = Helper::getData($params, 'image');
$title = Helper::getData($params, 'title');
$description = Helper::getData($params, 'description');
?>
<div class="cat-bg align-<?php echo $params->get('align'); ?>" style="background-image: url(<?php echo JURI::base() . $background; ?>">
	<div class="container">
		<?php if ($title): ?>
			<h1 class="category-title"><?php echo $title; ?></h1>
		<?php endif; ?>
		<?php if ($description): ?>
			<div class="category-title-desc"><?php echo $description; ?></div>
		<?php endif; ?>
	</div>
</div>
