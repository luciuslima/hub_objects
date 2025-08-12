<?php
$view->extend('MauticCoreBundle:Default:content.html.php');

$view['slots']->set('headerTitle', $item->getName());

$view['slots']->set(
    'actions',
    $view->render(
        'MauticCoreBundle:Helper:actions.html.php',
        [
            'editUrl' => $view['router']->generate('mautic_hubobjects_opportunity_edit', ['objectId' => $item->getId()]),
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
                    <dt><?php echo $view['translator']->trans('mautic.hubobjects.opportunity.name'); ?></dt>
                    <dd><?php echo htmlspecialchars((string) $item->getName(), ENT_QUOTES, 'UTF-8'); ?></dd>

                    <dt><?php echo $view['translator']->trans('mautic.hubobjects.opportunity.contact'); ?></dt>
                    <dd>
                        <?php if ($item->getContact()) : ?>
                            <a href="<?php echo $view['router']->generate('mautic_contact_action', ['objectAction' => 'view', 'objectId' => $item->getContact()->getId()]); ?>">
                                <?php echo htmlspecialchars((string) $item->getContact()->getEmail(), ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                        <?php endif; ?>
                    </dd>

                    <dt><?php echo $view['translator']->trans('mautic.hubobjects.opportunity.amount'); ?></dt>
                    <dd><?php echo $item->getAmount(); ?></dd>

                    <dt><?php echo $view['translator']->trans('mautic.hubobjects.opportunity.stage'); ?></dt>
                    <dd><?php echo $item->getStage(); ?></dd>

                    <dt><?php echo $view['translator']->trans('mautic.hubobjects.opportunity.close_date'); ?></dt>
                    <dd><?php echo $item->getCloseDate() ? $item->getCloseDate()->format('Y-m-d H:i:s') : ''; ?></dd>
                </dl>
            </div>
        </div>
    </div>
</div>
