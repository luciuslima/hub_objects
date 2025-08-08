<?php
$view->extend('MauticCoreBundle:Default:content.html.php');

$view['slots']->set('headerTitle', 'mautic.hubobjects.contract.plural');

$view['slots']->set(
    'actions',
    $view->render(
        'MauticCoreBundle:Helper:actions.html.php',
        [
            'newUrl' => $view['router']->generate('mautic_hubobjects_contract_new'),
            'tooltip' => 'mautic.hubobjects.contract.new',
        ]
    )
);

if (count($items)) {
?>
    <div class="table-responsive">
        <table class="table table-hover table-striped table-bordered">
            <thead>
                <tr>
                    <th><?php echo $view['translator']->trans('mautic.core.id'); ?></th>
                    <th><?php echo $view['translator']->trans('mautic.hubobjects.contract.name'); ?></th>
                    <th><?php echo $view['translator']->trans('mautic.hubobjects.contract.contact'); ?></th>
                    <th><?php echo $view['translator']->trans('mautic.hubobjects.contract.value'); ?></th>
                    <th><?php echo $view['translator']->trans('mautic.core.actions'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item) : ?>
                    <tr>
                        <td><?php echo $item->getId(); ?></td>
                        <td>
                            <a href="<?php echo $view['router']->generate('mautic_hubobjects_contract_view', ['objectId' => $item->getId()]); ?>">
                                <?php echo htmlspecialchars((string) $item->getName(), ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                        </td>
                        <td>
                            <?php if ($item->getContact()) : ?>
                                <a href="<?php echo $view['router']->generate('mautic_contact_action', ['objectAction' => 'view', 'objectId' => $item->getContact()->getId()]); ?>">
                                    <?php echo htmlspecialchars((string) $item->getContact()->getEmail(), ENT_QUOTES, 'UTF-8'); ?>
                                </a>
                            <?php endif; ?>
                        </td>
                        <td><?php echo $item->getValue(); ?></td>
                        <td>
                            <?php echo $view->render(
                                'MauticCoreBundle:Helper:table_actions.html.php',
                                [
                                    'item'   => $item,
                                    'viewRoute' => ['mautic_hubobjects_contract_view', ['objectId' => $item->getId()]],
                                    'editRoute' => ['mautic_hubobjects_contract_edit', ['objectId' => $item->getId()]],
                                    'deleteRoute' => ['mautic_hubobjects_contract_delete', ['objectId' => $item->getId()]],
                                ]
                            ); ?>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </tbody>
        </table>
    </div>
<?php
} else {
    echo $view->render('MauticCoreBundle:Helper:noresults.html.php');
}

if (!empty($pagination)) {
    echo $view->render('MauticCoreBundle:Helper:pagination.html.php', ['pagination' => $pagination]);
}
?>
