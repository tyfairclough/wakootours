<?php

use Joomla\CMS\Language\Text;

defined('JPATH_BASE') or die;

$alert = [
    'message' => 'uk-alert-success',
    'warning' => 'uk-alert-warning',
    'error' => 'uk-alert-danger',
    'notice' => '',
    'info' => '',
];

$msgList = $displayData['msgList'];
$msgAttr = json_encode($msgList);

?>
<div id="system-message-container" data-messages="<?= htmlspecialchars($msgAttr) ?>">
<?php if (is_array($msgList) && !empty($msgList)) : ?>
    <?php foreach ($msgList as $type => $msgs) : ?>
    <div class="uk-alert <?= $alert[$type] ?>" uk-alert>

        <a href="#" class="uk-alert-close uk-close" uk-close></a>

        <?php if (!empty($msgs)) : ?>

            <h3><?= Text::_($type) ?></h3>

            <?php foreach ($msgs as $msg) : ?>
            <p><?= $msg ?></p>
            <?php endforeach ?>

        <?php endif ?>

    </div>
    <?php endforeach ?>
<?php endif ?>
</div>
