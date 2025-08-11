<?php
$view->extend('MauticCoreBundle:Default:content.html.php');

$view['slots']->set('headerTitle', $item->getName());

$view['slots']->set(
    'actions',
    $view->render(
        'MauticCoreBundle:Helper:actions.html.php',
        [
            'editUrl' => $view['router']->generate('mautic_hubobjects_product_edit', ['objectId' => $item->getId()]),
            'tooltip' => 'mautic.core.edit',
        ]
    )
);
?>

<div class="panel">
    <div class="panel-body">
        <div class="row">
            <div class="col-md-6">
                <h4><?php echo $view['translator']->trans('mautic.core.details'); ?></h4>
                <dl>
                    <dt><?php echo $view['translator']->trans('mautic.hubobjects.product.name'); ?></dt>
                    <dd><?php echo htmlspecialchars((string) $item->getName(), ENT_QUOTES, 'UTF-8'); ?></dd>

                    <dt><?php echo $view['translator']->trans('mautic.hubobjects.product.description'); ?></dt>
                    <dd><?php echo nl2br(htmlspecialchars((string) $item->getDescription(), ENT_QUOTES, 'UTF-8')); ?></dd>

                    <dt><?php echo $view['translator']->trans('mautic.hubobjects.product.price'); ?></dt>
                    <dd><?php echo $item->getPrice(); ?></dd>
                </dl>
            </div>
        </div>
    </div>
</div>
