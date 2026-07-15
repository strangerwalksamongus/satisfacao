<?php

include_once("../../../inc/includes.php");

$plugin = new Plugin();
if (!$plugin->isInstalled('satisfacao') || !$plugin->isActivated('satisfacao')) {
   Html::displayNotFoundError();
}

Html::header(__('Satisfaction survey', 'satisfacao'), $_SERVER['PHP_SELF'], "helpdesk");


$object = new PluginSatisfacaoResponsePage();
echo $object->displayPage();
