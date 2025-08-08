<?php

return [
    'name'        => 'Hub Objects',
    'description' => 'Gerencia objetos personalizados como Produtos, Contratos e Oportunidades, e suas relações.',
    'author'      => 'Jules',
    'version'     => '1.0.0',

    'routes' => [
        'main' => [
            // Product Routes
            'mautic_hubobjects_product_index' => [
                'path'       => '/hubobjects/products/{page}',
                'controller' => 'HubObjectsBundle:Product:index',
            ],
            'mautic_hubobjects_product_new' => [
                'path'       => '/hubobjects/products/new',
                'controller' => 'HubObjectsBundle:Product:new',
            ],
            'mautic_hubobjects_product_edit' => [
                'path'       => '/hubobjects/products/edit/{objectId}',
                'controller' => 'HubObjectsBundle:Product:edit',
            ],
            'mautic_hubobjects_product_view' => [
                'path'       => '/hubobjects/products/view/{objectId}',
                'controller' => 'HubObjectsBundle:Product:view',
            ],
            'mautic_hubobjects_product_delete' => [
                'path'       => '/hubobjects/products/delete/{objectId}',
                'controller' => 'HubObjectsBundle:Product:delete',
            ],

            // Contract Routes
            'mautic_hubobjects_contract_index' => [
                'path'       => '/hubobjects/contracts/{page}',
                'controller' => 'HubObjectsBundle:Contract:index',
            ],
            'mautic_hubobjects_contract_new' => [
                'path'       => '/hubobjects/contracts/new',
                'controller' => 'HubObjectsBundle:Contract:new',
            ],
            'mautic_hubobjects_contract_edit' => [
                'path'       => '/hubobjects/contracts/edit/{objectId}',
                'controller' => 'HubObjectsBundle:Contract:edit',
            ],
            'mautic_hubobjects_contract_view' => [
                'path'       => '/hubobjects/contracts/view/{objectId}',
                'controller' => 'HubObjectsBundle:Contract:view',
            ],
            'mautic_hubobjects_contract_delete' => [
                'path'       => '/hubobjects/contracts/delete/{objectId}',
                'controller' => 'HubObjectsBundle:Contract:delete',
            ],

            // Opportunity Routes
            'mautic_hubobjects_opportunity_index' => [
                'path'       => '/hubobjects/opportunities/{page}',
                'controller' => 'HubObjectsBundle:Opportunity:index',
            ],
            'mautic_hubobjects_opportunity_new' => [
                'path'       => '/hubobjects/opportunities/new',
                'controller' => 'HubObjectsBundle:Opportunity:new',
            ],
            'mautic_hubobjects_opportunity_edit' => [
                'path'       => '/hubobjects/opportunities/edit/{objectId}',
                'controller' => 'HubObjectsBundle:Opportunity:edit',
            ],
            'mautic_hubobjects_opportunity_view' => [
                'path'       => '/hubobjects/opportunities/view/{objectId}',
                'controller' => 'HubObjectsBundle:Opportunity:view',
            ],
            'mautic_hubobjects_opportunity_delete' => [
                'path'       => '/hubobjects/opportunities/delete/{objectId}',
                'controller' => 'HubObjectsBundle:Opportunity:delete',
            ],
        ],
        'api' => [
            // Product API
            'mautic_api_hubobjects_get_products' => [
                'path'       => '/hubobjects/products',
                'controller' => 'HubObjectsBundle:Api\\ProductApi:getEntities',
            ],
            'mautic_api_hubobjects_get_product' => [
                'path'       => '/hubobjects/products/{id}',
                'controller' => 'HubObjectsBundle:Api\\ProductApi:getEntity',
            ],
            'mautic_api_hubobjects_new_product' => [
                'path'       => '/hubobjects/products/new',
                'controller' => 'HubObjectsBundle:Api\\ProductApi:newEntity',
                'method'     => 'POST',
            ],
            'mautic_api_hubobjects_edit_product_patch' => [
                'path'       => '/hubobjects/products/{id}/edit',
                'controller' => 'HubObjectsBundle:Api\\ProductApi:editEntity',
                'method'     => 'PATCH',
            ],
            'mautic_api_hubobjects_edit_product_put' => [
                'path'       => '/hubobjects/products/{id}/edit',
                'controller' => 'HubObjectsBundle:Api\\ProductApi:putEntity',
                'method'     => 'PUT',
            ],
            'mautic_api_hubobjects_delete_product' => [
                'path'       => '/hubobjects/products/{id}/delete',
                'controller' => 'HubObjectsBundle:Api\\ProductApi:deleteEntity',
                'method'     => 'DELETE',
            ],

            // Contract API
            'mautic_api_hubobjects_get_contracts' => [
                'path'       => '/hubobjects/contracts',
                'controller' => 'HubObjectsBundle:Api\\ContractApi:getEntities',
            ],
            'mautic_api_hubobjects_get_contract' => [
                'path'       => '/hubobjects/contracts/{id}',
                'controller' => 'HubObjectsBundle:Api\\ContractApi:getEntity',
            ],
            'mautic_api_hubobjects_new_contract' => [
                'path'       => '/hubobjects/contracts/new',
                'controller' => 'HubObjectsBundle:Api\\ContractApi:newEntity',
                'method'     => 'POST',
            ],
            'mautic_api_hubobjects_edit_contract_patch' => [
                'path'       => '/hubobjects/contracts/{id}/edit',
                'controller' => 'HubObjectsBundle:Api\\ContractApi:editEntity',
                'method'     => 'PATCH',
            ],
             'mautic_api_hubobjects_edit_contract_put' => [
                'path'       => '/hubobjects/contracts/{id}/edit',
                'controller' => 'HubObjectsBundle:Api\\ContractApi:putEntity',
                'method'     => 'PUT',
            ],
            'mautic_api_hubobjects_delete_contract' => [
                'path'       => '/hubobjects/contracts/{id}/delete',
                'controller' => 'HubObjectsBundle:Api\\ContractApi:deleteEntity',
                'method'     => 'DELETE',
            ],

            // Opportunity API
            'mautic_api_hubobjects_get_opportunities' => [
                'path'       => '/hubobjects/opportunities',
                'controller' => 'HubObjectsBundle:Api\\OpportunityApi:getEntities',
            ],
            'mautic_api_hubobjects_get_opportunity' => [
                'path'       => '/hubobjects/opportunities/{id}',
                'controller' => 'HubObjectsBundle:Api\\OpportunityApi:getEntity',
            ],
            'mautic_api_hubobjects_new_opportunity' => [
                'path'       => '/hubobjects/opportunities/new',
                'controller' => 'HubObjectsBundle:Api\\OpportunityApi:newEntity',
                'method'     => 'POST',
            ],
            'mautic_api_hubobjects_edit_opportunity_patch' => [
                'path'       => '/hubobjects/opportunities/{id}/edit',
                'controller' => 'HubObjectsBundle:Api\\OpportunityApi:editEntity',
                'method'     => 'PATCH',
            ],
            'mautic_api_hubobjects_edit_opportunity_put' => [
                'path'       => '/hubobjects/opportunities/{id}/edit',
                'controller' => 'HubObjectsBundle:Api\\OpportunityApi:putEntity',
                'method'     => 'PUT',
            ],
            'mautic_api_hubobjects_delete_opportunity' => [
                'path'       => '/hubobjects/opportunities/{id}/delete',
                'controller' => 'HubObjectsBundle:Api\\OpportunityApi:deleteEntity',
                'method'     => 'DELETE',
            ],
        ],
    ],

    'menu' => [
        'main' => [
            'mautic.hubobjects.root' => [
                'id'        => 'mautic_hubobjects_root',
                'iconClass' => 'fa-cubes',
                'priority'  => 30,
                'children'  => [
                    'mautic.hubobjects.product.menu' => [
                        'route' => 'mautic_hubobjects_product_index',
                        'parent' => 'mautic.hubobjects.root',
                    ],
                    'mautic.hubobjects.contract.menu' => [
                        'route' => 'mautic_hubobjects_contract_index',
                        'parent' => 'mautic.hubobjects.root',
                    ],
                    'mautic.hubobjects.opportunity.menu' => [
                        'route' => 'mautic_hubobjects_opportunity_index',
                        'parent' => 'mautic.hubobjects.root',
                    ],
                ],
            ],
        ],
    ],

    'services' => [
        'events' => [
            'mautic.hubobjects.subscriber.segment' => [
                'class'     => \MauticPlugin\HubObjectsBundle\EventListener\SegmentSubscriber::class,
                'arguments' => ['doctrine.orm.entity_manager'],
            ],
            'mautic.hubobjects.subscriber.campaign' => [
                'class'     => \MauticPlugin\HubObjectsBundle\EventListener\CampaignSubscriber::class,
                'arguments' => [
                    'mautic.hubobjects.model.opportunity',
                ],
            ],
            'mautic.hubobjects.subscriber.email' => [
                'class'     => \MauticPlugin\HubObjectsBundle\EventListener\EmailSubscriber::class,
                'arguments' => [
                    'mautic.hubobjects.model.opportunity',
                    'router',
                ],
            ],
        ],
        'models' => [
            'mautic.hubobjects.model.product' => [
                'class'     => \MauticPlugin\HubObjectsBundle\Model\ProductModel::class,
                'arguments' => ['doctrine.orm.entity_manager', 'mautic.core.security', 'event_dispatcher', 'router', 'request_stack'],
            ],
            'mautic.hubobjects.model.contract' => [
                'class'     => \MauticPlugin\HubObjectsBundle\Model\ContractModel::class,
                'arguments' => ['doctrine.orm.entity_manager', 'mautic.core.security', 'event_dispatcher', 'router', 'request_stack'],
            ],
            'mautic.hubobjects.model.opportunity' => [
                'class'     => \MauticPlugin\HubObjectsBundle\Model\OpportunityModel::class,
                'arguments' => ['doctrine.orm.entity_manager', 'mautic.core.security', 'event_dispatcher', 'router', 'request_stack'],
            ],
        ],
        'forms' => [
            'mautic.hubobjects.form.type.product' => [
                'class' => \MauticPlugin\HubObjectsBundle\Form\Type\ProductType::class,
                'alias' => 'hubobjects_product',
            ],
            'mautic.hubobjects.form.type.contract' => [
                'class' => \MauticPlugin\HubObjectsBundle\Form\Type\ContractType::class,
                'alias' => 'hubobjects_contract',
            ],
            'mautic.hubobjects.form.type.opportunity' => [
                'class' => \MauticPlugin\HubObjectsBundle\Form\Type\OpportunityType::class,
                'alias' => 'hubobjects_opportunity',
            ],
        ],
    ],
];
