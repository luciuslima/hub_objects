<?php
$view->extend('MauticCoreBundle:Default:content.html.php');

/** @var \MauticPlugin\HubObjectsBundle\Entity\ObjectDefinition $definition */
$definition = $view['definition'];
$item = $view['item'];

$header = $item->getId()
    ? $item->getProperties()['name'] ?? $definition->getName() . ' #' . $item->getId()
    : $view['translator']->trans('mautic.hubobjects.instance.new_text', ['%object%' => $definition->getName()]);

$view['slots']->set('headerTitle', $header);

echo $view['form']->form($form);
?>
