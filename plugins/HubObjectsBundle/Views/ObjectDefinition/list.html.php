<?php
$view->extend('MauticCoreBundle:Default:content.html.php');

$view['slots']->set('headerTitle', 'mautic.hubobjects.definition.plural');

// This needs to be updated to use the new route
$view['slots']->set(
    'actions',
    $view->render(
        'MauticCoreBundle:Helper:actions.html.php',
        [
            'newUrl' => $view['router']->generate('mautic_hubobjects_definition_action', ['objectAction' => 'new']),
            'tooltip' => 'mautic.hubobjects.definition.new',
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
                    <th><?php echo $view['translator']->trans('mautic.hubobjects.definition.name'); ?></th>
                    <th><?php echo $view['translator']->trans('mautic.hubobjects.definition.slug'); ?></th>
                    <th><?php echo $view['translator']->trans('mautic.core.actions'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item) : ?>
                    <tr>
                        <td><?php echo $item->getId(); ?></td>
                        <td>
                            <a href="<?php echo $view['router']->generate('mautic_hubobjects_definition_action', ['objectAction' => 'edit', 'objectId' => $item->getId()]); ?>">
                                <?php echo htmlspecialchars((string) $item->getName(), ENT_QUOTES, 'UTF-8'); ?>
                            </a>
                        </td>
                        <td><?php echo $item->getSlug(); ?></td>
                        <td>
                            <?php echo $view->render(
                                'MauticCoreBundle:Helper:table_actions.html.php',
                                [
                                    'item'   => $item,
                                    'editRoute' => ['mautic_hubobjects_definition_action', ['objectAction' => 'edit', 'objectId' => $item->getId()]],
                                    'deleteRoute' => ['mautic_hubobjects_definition_action', ['objectAction' => 'delete', 'objectId' => $item->getId()]],
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
