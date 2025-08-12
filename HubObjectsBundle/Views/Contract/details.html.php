<?php
$view->extend('MauticCoreBundle:Default:content.html.php');

$view['slots']->set('headerTitle', $item->getName());

$view['slots']->set(
    'actions',
    $view->render(
        'MauticCoreBundle:Helper:actions.html.php',
        [
            'editUrl' => $view['router']->generate('mautic_hubobjects_contract_edit', ['objectId' => $item->getId()]),
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
                    <dt><?php echo $view['translator']->trans('mautic.hubobjects.contract.name'); ?></dt>
                    <dd><?php echo htmlspecialchars((string) $item->getName(), ENT_QUOTES, 'UTF-8'); ?></dd>

                    <dt><?php echo $view['translator']->trans('mautic.hubobjects.contract.contact'); ?></dt>
                    <dd>
                        <?php if ($item->getContact()) : ?>
                            <a href="<?php echo $view['router']->generate('mautic_contact_action', ['objectAction' => 'view', 'objectId' => $item->getContact()->getId()]); ?>">
                                <?php echo htmlspecialchars((string) $item->getContact()->getEmail(), ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                        <?php endif; ?>
                    </dd>

                    <dt><?php echo $view['translator']->trans('mautic.hubobjects.contract.value'); ?></dt>
                    <dd><?php echo $item->getValue(); ?></dd>

                    <dt><?php echo $view['translator']->trans('mautic.hubobjects.contract.start_date'); ?></dt>
                    <dd><?php echo $item->getStartDate() ? $item->getStartDate()->format('Y-m-d H:i:s') : ''; ?></dd>

                    <dt><?php echo $view['translator']->trans('mautic.hubobjects.contract.end_date'); ?></dt>
                    <dd><?php echo $item->getEndDate() ? $item->getEndDate()->format('Y-m-d H:i:s') : ''; ?></dd>
                </dl>
            </div>
            <div class="col-md-6">
                <h4><?php echo $view['translator']->trans('mautic.hubobjects.contract.products'); ?></h4>
                <?php if (count($item->getProducts())) : ?>
                    <ul>
                        <?php foreach ($item->getProducts() as $product) : ?>
                            <li>
                                <a href="<?php echo $view['router']->generate('mautic_hubobjects_product_view', ['objectId' => $product->getId()]); ?>">
                                    <?php echo htmlspecialchars((string) $product->getName(), ENT_QUOTES, 'UTF-8'); ?>
                                </a>
                            </li>
                        <?php endforeach; ?>
                    </ul>
                <?php else: ?>
                    <p><?php echo $view['translator']->trans('mautic.hubobjects.contract.no_products'); ?></p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>
