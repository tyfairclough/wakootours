<?php
/*
 * The template for displaying builder articles.
 */

use Joomla\CMS\Router\Route;
use Joomla\CMS\Uri\Uri;

echo $content;

if ($article->params->get('access-edit') && !$app['config']->get('theme.customizer')) {

    $url = Route::_(ContentHelperRoute::getFormRoute($article->id) . '&return=' . base64_encode(Uri::getInstance()));
    echo "<a style=\"position: fixed!important\" class=\"uk-position-medium uk-position-bottom-right uk-button uk-button-primary\" href=\"{$url}\">" . JText::_('JACTION_EDIT') . '</a>';

}

if (@$event) echo $event->afterDisplayContent;
