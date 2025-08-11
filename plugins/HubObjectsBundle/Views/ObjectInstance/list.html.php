<?php
$view->extend('MauticCoreBundle:Default:content.html.php');

/** @var \MauticPlugin\HubObjectsBundle\Entity\ObjectDefinition $definition */
$definition = $view['definition'];

$view['slots']->set('headerTitle', $definition->getPluralName());

$view['slots']->set(
    'actions',
    $view->render(
        'MauticCoreBundle:Helper:actions.html.php',
        [
            // This route will need to be created
            'newUrl' => $view['router']->generate('mautic_hubobjects_instance_new', ['objectSlug' => $definition->getSlug()]),
            'tooltip' => 'mautic.hubobjects.instance.new',
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
                    <?php foreach ($definition->getFields() as $field) : ?>
                        <th><?php echo htmlspecialchars($field->getName(), ENT_QUOTES, 'UTF-8'); ?></th>
                    <?php endforeach; ?>
                    <th><?php echo $view['translator']->trans('mautic.core.actions'); ?></th>
                </tr>
            </thead>
            <tbody>
                <?php foreach ($items as $item) :
                    $properties = $item->getProperties();
                ?>
                    <tr>
                        <td><?php echo $item->getId(); ?></td>
                        <?php foreach ($definition->getFields() as $field) : ?>
                            <td><?php echo htmlspecialchars((string) ($properties[$field->getName()] ?? ''), ENT_QUOTES, 'UTF-8'); ?></td>
                        <?php endforeach; ?>
                        <td>
                            <?php /* Action buttons here */ ?>
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
