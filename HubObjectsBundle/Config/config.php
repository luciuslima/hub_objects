<?php

return [
    'name'        => 'Hub Objects',
    'description' => 'Gerencia objetos e propriedades personalizadas, e suas relações com contatos.',
    'author'      => 'Lucius Lima',
    'version'     => '1.1.0',

    'routes' => [
        'main' => [
            // Schema Builder UI Routes
            'mautic_hubobjects_definition_index' => [
                'path'       => '/hubobjects/definitions/{page}',
                'controller' => 'HubObjectsBundle:ObjectDefinition:index',
            ],
            'mautic_hubobjects_definition_action' => [
                'path'       => '/hubobjects/definitions/{objectAction}/{objectId}',
                'controller' => 'HubObjectsBundle:ObjectDefinition:execute',
            ],
            // Dynamic Instance UI Routes
            'mautic_hubobjects_instance_index' => [
                'path'       => '/hubobjects/instances/{objectSlug}/{page}',
                'controller' => 'HubObjectsBundle:ObjectInstance:index',
            ],
            'mautic_hubobjects_instance_action' => [
                'path'       => '/hubobjects/instances/{objectSlug}/{objectAction}/{objectId}',
                'controller' => 'HubObjectsBundle:ObjectInstance:execute',
            ],
        ],
        'api' => [
            // Dynamic Instance API Routes
            'mautic_api_hubobjects_get_instances' => [
                'path'       => '/hubobjects/instances/{objectSlug}',
                'controller' => 'HubObjectsBundle:Api\\ObjectInstanceApi:getEntities',
            ],
            'mautic_api_hubobjects_get_instance' => [
                'path'       => '/hubobjects/instances/{objectSlug}/{id}',
                'controller' => 'HubObjectsBundle:Api\\ObjectInstanceApi:getEntity',
            ],
            'mautic_api_hubobjects_new_instance' => [
                'path'       => '/hubobjects/instances/{objectSlug}/new',
                'controller' => 'HubObjectsBundle:Api\\ObjectInstanceApi:newEntity',
                'method'     => 'POST',
            ],
            'mautic_api_hubobjects_edit_instance_patch' => [
                'path'       => '/hubobjects/instances/{objectSlug}/{id}/edit',
                'controller' => 'HubObjectsBundle:Api\\ObjectInstanceApi:editEntity',
                'method'     => 'PATCH',
            ],
            'mautic_api_hubobjects_edit_instance_put' => [
                'path'       => '/hubobjects/instances/{objectSlug}/{id}/edit',
                'controller' => 'HubObjectsBundle:Api\\ObjectInstanceApi:putEntity',
                'method'     => 'PUT',
            ],
            'mautic_api_hubobjects_delete_instance' => [
                'path'       => '/hubobjects/instances/{objectSlug}/{id}/delete',
                'controller' => 'HubObjectsBundle:Api\\ObjectInstanceApi:deleteEntity',
                'method'     => 'DELETE',
            ],
        ],
    ],

    'menu' => [
        'admin' => [
            'mautic.hubobjects.definitions.menu' => [
                'label'     => 'mautic.hubobjects.builder.menu',
                'route'     => 'mautic_hubobjects_definition_index',
                'parent'    => 'mautic.core.integrations',
                'id'        => 'mautic_hubobjects_definition_index',
                'iconClass' => 'fa-cubes',
            ],
        ],
        // The main menu for instances will be added dynamically via a subscriber
    ],

    'services' => [
        'models' => [
            'mautic.hubobjects.model.definition' => [
                'class'     => \MauticPlugin\HubObjectsBundle\Model\ObjectDefinitionModel::class,
            ],
            'mautic.hubobjects.model.instance' => [
                'class'     => \MauticPlugin\HubObjectsBundle\Model\ObjectInstanceModel::class,
            ],
        ],
        'forms' => [
            'mautic.hubobjects.form.type.objectdefinition' => [
                'class' => \MauticPlugin\HubObjectsBundle\Form\Type\ObjectDefinitionType::class,
                'alias' => 'hubobjects_definition',
            ],
            'mautic.hubobjects.form.type.fielddefinition' => [
                'class' => \MauticPlugin\HubObjectsBundle\Form\Type\FieldDefinitionType::class,
            ],
            'mautic.hubobjects.form.type.objectinstance' => [
                'class' => \MauticPlugin\HubObjectsBundle\Form\Type\ObjectInstanceType::class,
            ],
            'mautic.hubobjects.form.type.campaigncondition' => [
                'class'     => \MauticPlugin\HubObjectsBundle\Form\Type\CampaignConditionType::class,
                'arguments' => ['mautic.hubobjects.model.definition'],
                'alias'     => 'hubobjects_campaign_condition',
            ],
        ],
        'events' => [
            'mautic.hubobjects.subscriber.segment' => [
                'class'     => \MauticPlugin\HubObjectsBundle\EventListener\SegmentSubscriber::class,
                'arguments' => [
                    'mautic.hubobjects.model.definition',
                ],
            ],
            'mautic.hubobjects.subscriber.menu' => [
                'class'     => \MauticPlugin\HubObjectsBundle\EventListener\MenuSubscriber::class,
                'arguments' => [
                    'mautic.hubobjects.model.definition',
                ],
            ],
            'mautic.hubobjects.subscriber.campaign' => [
                'class'     => \MauticPlugin\HubObjectsBundle\EventListener\CampaignSubscriber::class,
                'arguments' => [
                    'mautic.hubobjects.model.definition',
                    'mautic.hubobjects.model.instance',
                ],
            ],
            'mautic.hubobjects.subscriber.email' => [
                'class'     => \MauticPlugin\HubObjectsBundle\EventListener\EmailSubscriber::class,
                'arguments' => [
                    'mautic.hubobjects.model.definition',
                    'mautic.hubobjects.model.instance',
                ],
            ],
        ],
    ],
];
