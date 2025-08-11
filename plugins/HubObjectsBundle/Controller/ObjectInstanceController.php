<?php

declare(strict_types=1);

namespace MauticPlugin\HubObjectsBundle\Controller;

use Mautic\CoreBundle\Controller\FormController;
use MauticPlugin\HubObjectsBundle\Entity\ObjectDefinition;
use MauticPlugin\HubObjectsBundle\Entity\ObjectInstance;
use MauticPlugin\HubObjectsBundle\Model\ObjectDefinitionModel;
use MauticPlugin\HubObjectsBundle\Model\ObjectInstanceModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ObjectInstanceController extends FormController
{
    /**
     * @param int $page
     */
    public function indexAction(Request $request, string $objectSlug, $page = 1): Response
    {
        $definition = $this->getObjectDefinition($objectSlug);

        /** @var ObjectInstanceModel $model */
        $model = $this->getModel('hubobjects.instance');
        $this->setListStandardParameters($page);

        $items = $model->getEntities([
            'start'      => $this->page,
            'limit'      => $this->limit,
            'filter'     => $this->search,
            'orderBy'    => $this->orderBy,
            'orderByDir' => $this->orderByDir,
            'where'      => [['col' => 'i.objectDefinition', 'expr' => 'eq', 'val' => $definition->getId()]],
        ]);

        $totalItems = count($items);
        // ... pagination logic ...

        return $this->delegateView([
            'viewParameters' => [
                'items'       => $items,
                'definition'  => $definition,
                // ... other view params ...
            ],
            'contentTemplate' => 'HubObjectsBundle:ObjectInstance:list.html.php',
            'passthroughVars' => [
                'route' => $this->generateUrl('mautic_hubobjects_instance_index', ['objectSlug' => $objectSlug, 'page' => $this->page]),
            ],
        ]);
    }

    public function newAction(Request $request, string $objectSlug): Response
    {
        $definition = $this->getObjectDefinition($objectSlug);
        $entity     = new ObjectInstance();
        $entity->setObjectDefinition($definition);

        // ... form processing logic ...
        return new Response("New action for {$definition->getName()}");
    }

    private function getObjectDefinition(string $slug): ObjectDefinition
    {
        /** @var ObjectDefinitionModel $definitionModel */
        $definitionModel = $this->getModel('hubobjects.definition');
        $definition      = $definitionModel->getRepository()->findOneBy(['slug' => $slug]);

        if (null === $definition) {
            throw new NotFoundHttpException("Object definition with slug '{$slug}' not found.");
        }

        return $definition;
    }
}
