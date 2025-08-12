<?php
$view->extend('MauticCoreBundle:Default:content.html.php');

$view['slots']->set('headerTitle', $item->getName() ?: $view['translator']->trans('mautic.hubobjects.product.new'));

echo $view['form']->form($form);
?>
