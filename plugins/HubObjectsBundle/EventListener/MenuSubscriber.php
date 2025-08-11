<?php

declare(strict_types=1);

namespace MauticPlugin\HubObjectsBundle\EventListener;

use Mautic\CoreBundle\Event\MenuBuilderEvent;
use Mautic\CoreBundle\EventListener\CommonSubscriber;
use Mautic\CoreBundle\Menu\MenuEvents;
use MauticPlugin\HubObjectsBundle\Model\ObjectDefinitionModel;

class MenuSubscriber extends CommonSubscriber
{
    private ObjectDefinitionModel $definitionModel;

    public function __construct(ObjectDefinitionModel $definitionModel)
    {
        $this->definitionModel = $definitionModel;
    }

    public static function getSubscribedEvents(): array
    {
        return [
            MenuEvents::MAIN_MENU_ON_BUILD => ['onMainMenuBuild', 0],
        ];
    }

    public function onMainMenuBuild(MenuBuilderEvent $event): void
    {
        $definitions = $this->definitionModel->getRepository()->findAll();

        if (count($definitions)) {
            $event->addMenuItem(
                'hubobjects.main',
                [
                    'label'     => 'mautic.hubobjects.objects', // New translation key
                    'route'     => 'mautic_hubobjects_definition_index', // Fallback route
                    'iconClass' => 'fa-database',
                    'priority'  => 40, // Adjust as needed
                ]
            );

            foreach ($definitions as $definition) {
                $event->addMenuItem(
                    'hubobjects.instance.'.$definition->getSlug(),
                    [
                        'label'  => $definition->getPluralName(),
                        'route'  => 'mautic_hubobjects_instance_index',
                        'routeParameters' => ['objectSlug' => $definition->getSlug()],
                        'parent' => 'hubobjects.main',
                    ]
                );
            }
        }
    }
}
