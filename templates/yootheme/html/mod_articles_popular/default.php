<?php

defined('_JEXEC') or die;

?>

<ul class="mostread">
    <?php foreach ($list as $item) : ?>
    <li><a href="<?= $item->link ?>"><?= $item->title ?></a></li>
    <?php endforeach ?>
</ul>
