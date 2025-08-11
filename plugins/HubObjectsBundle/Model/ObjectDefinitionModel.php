<?php

declare(strict_types=1);

namespace MauticPlugin\HubObjectsBundle\Model;

use Doctrine\ORM\EntityManager;
use Mautic\CoreBundle\Model\FormModel;
use Mautic\CoreBundle\Security\Permissions\CorePermissions;
use MauticPlugin\HubObjectsBundle\Entity\ObjectDefinition;
use MauticPlugin\HubObjectsBundle\Entity\ObjectDefinitionRepository;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\RequestStack;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

/**
 * @extends FormModel<ObjectDefinition>
 */
class ObjectDefinitionModel extends FormModel
{
    public function __construct(
        EntityManager $em,
        CorePermissions $security,
        EventDispatcherInterface $dispatcher,
        UrlGeneratorInterface $router,
        RequestStack $requestStack
    ) {
        parent::__construct($em, $security, $dispatcher, $router, $requestStack);
    }

    public function getRepository(): ObjectDefinitionRepository
    {
        $repository = $this->em->getRepository(ObjectDefinition::class);
        assert($repository instanceof ObjectDefinitionRepository);

        return $repository;
    }

    public function getPermissionBase(): string
    {
        return 'hubobjects:definitions';
    }
}
