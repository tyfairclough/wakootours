<?php

defined('_JEXEC') or die('Restricted access');

$this->addStyleSheet("{$this->baseurl}/media/jui/css/bootstrap.min.css");
$this->addStyleSheet("{$this->baseurl}/media/jui/css/bootstrap-extended.css");
$this->addStyleSheet("{$this->baseurl}/media/jui/css/bootstrap-responsive.css");

?>
<!DOCTYPE HTML>
<html lang="<?= $this->language ?>" dir="<?= $this->direction ?>">
<head>
    <meta charset="<?= $this->getCharset() ?>">
	<jdoc:include type="head" />
</head>
<body class="contentpane">
	<jdoc:include type="message" />
	<jdoc:include type="component" />
</body>
</html>
