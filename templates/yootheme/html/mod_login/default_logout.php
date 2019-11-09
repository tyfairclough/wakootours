<?php

use Joomla\CMS\HTML\HTMLHelper;
use Joomla\CMS\Language\Text;
use Joomla\CMS\Router\Route;

defined('_JEXEC') or die;

HTMLHelper::_('behavior.keepalive');

?>

<?php if ($type == 'logout') : ?>
<form action="<?= Route::_('index.php', true, $params->get('usesecure')) ?>" method="post">

    <?php if ($params->get('greeting')) : ?>
    <div class="uk-margin">
        <?php if ($params->get('name') == 0) :
            echo Text::sprintf('MOD_LOGIN_HINAME', htmlspecialchars($user->get('name')));
         else :
            echo Text::sprintf('MOD_LOGIN_HINAME', htmlspecialchars($user->get('username')));
         endif ?>
    </div>
    <?php endif ?>

    <div class="uk-margin">
        <button class="uk-button uk-button-primary" value="<?= Text::_('JLOGOUT') ?>" name="Submit" type="submit"><?= Text::_('JLOGOUT') ?></button>
    </div>

    <input type="hidden" name="option" value="com_users">
    <input type="hidden" name="task" value="user.logout">
    <input type="hidden" name="return" value="<?= $return ?>">
    <?= HTMLHelper::_('form.token') ?>

</form>
<?php endif ?>
