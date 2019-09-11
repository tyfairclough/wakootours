<?php
defined( '_JEXEC' ) or die;
$colClass = '';
$layout = new JLayoutFile('tour', JPATH_ROOT .'/components/com_modern_tours/layouts');
$asset->paramz = $params;
echo $layout->render($asset);
?>