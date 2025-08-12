<?php

declare(strict_types=1);

namespace MauticPlugin\HubObjectsBundle\Controller\Api;

use Mautic\ApiBundle\Controller\CommonApiController;
use MauticPlugin\HubObjectsBundle\Entity\ObjectDefinition;
use MauticPlugin\HubObjectsBundle\Form\Type\ObjectInstanceType;
use MauticPlugin\HubObjectsBundle\Model\ObjectDefinitionModel;
use MauticPlugin\HubObjectsBundle\Model\ObjectInstanceModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class ObjectInstanceApiController extends CommonApiController
{
    /**
     * @var ObjectInstanceModel
     */
    protected $model;

    /**
     * @var ObjectDefinitionModel
     */
    private $definitionModel;

    public function initialize(FilterControllerEvent $event): void
    {
        $this->model           = $this->factory->getModel('hubobjects.instance');
        $this->definitionModel = $this->factory->getModel('hubobjects.definition');
        parent::initialize($event);
    }

    private function getDefinition(string $slug): ObjectDefinition
    {
        $definition = $this->definitionModel->getRepository()->findOneBy(['slug' => $slug]);
        if (null === $definition) {
            throw new NotFoundHttpException("Object '{$slug}' not found");
        }
        return $definition;
    }

    public function getEntitiesAction(Request $request, string $objectSlug): Response
    {

        if (!$this->security->isGranted($this->model->getPermissionBase().':view')) {
            return $this->accessDenied();
        }

        $definition = $this->getDefinition($objectSlug);

        $args = $this->getStandardRequestParameters($request);
        $args['where'][] = [
            'col'  => $this->model->getRepository()->getTableAlias().'.objectDefinition',
            'expr' => 'eq',
            'val'  => $definition->getId(),
        ];

        $entities = $this->model->getEntities($args);
        $count    = count($entities);

        $view = $this->view(
            ['instances' => $entities, 'total' => $count],
            200,
            [],

            ['serializerGroups' => ['instanceList']]
        );

        return $this->handleView($view);
    }

    public function getEntityAction(Request $request, string $objectSlug, int $id): Response
    {
        if (!$this->security->isGranted($this->model->getPermissionBase().':view')) {
            return $this->accessDenied();
        }

        $definition = $this->getDefinition($objectSlug);
        $entity = $this->model->getEntity($id);

        if (null === $entity || $entity->getObjectDefinition()->getId() !== $definition->getId()) {
            return $this->notFound();
        }

        $view = $this->view(['instance' => $entity], 200, [], ['serializerGroups' => ['instanceDetails']]);
        return $this->handleView($view);
    }

    public function newEntityAction(Request $request, string $objectSlug): Response
    {

        if (!$this->security->isGranted($this->model->getPermissionBase().':create')) {
            return $this->accessDenied();
        }


        $definition = $this->getDefinition($objectSlug);
        $entity     = $this->model->getEntity();
        $entity->setObjectDefinition($definition);

        $form = $this->get('form.factory')->create(ObjectInstanceType::class, $entity, ['definition' => $definition]);
        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {
            $this->model->saveEntity($entity);
            $view = $this->view(['instance' => $entity], 201, [], ['serializerGroups' => ['instanceDetails']]);
        } else {
            return $this->returnError($this->getFormErrorMessages($form), 400);
        }

        return $this->handleView($view);
    }

    public function editEntityAction(Request $request, string $objectSlug, int $id, bool $createIfNotExists = false): Response
    {

        if (!$this->security->isGranted($this->model->getPermissionBase().':edit')) {
            return $this->accessDenied();
        }

        $definition = $this->getDefinition($objectSlug);
        $entity     = $this->model->getEntity($id);

        if (null === $entity) {
            if ($createIfNotExists) {
                $entity = $this->model->getEntity();
                $entity->setObjectDefinition($definition);
            } else {
                return $this->notFound();
            }
        } elseif ($entity->getObjectDefinition()->getId() !== $definition->getId()) {
            return $this->notFound(); // Mismatch between slug and instance's definition
        }

        $form = $this->get('form.factory')->create(ObjectInstanceType::class, $entity, ['definition' => $definition]);
        $form->submit($request->request->all(), 'PATCH' !== $request->getMethod());

        if ($form->isSubmitted() && $form->isValid()) {
            $this->model->saveEntity($entity);
            $statusCode = $entity->isNew() ? 201 : 200;
            $view       = $this->view(['instance' => $entity], $statusCode, [], ['serializerGroups' => ['instanceDetails']]);
        } else {
            return $this->returnError($this->getFormErrorMessages($form), 400);
        }

        return $this->handleView($view);
    }

    public function putEntityAction(Request $request, string $objectSlug, int $id): Response
    {
        return $this->editEntityAction($request, $objectSlug, $id, true);
    }

    public function deleteEntityAction(Request $request, string $objectSlug, int $id): Response
    {

        if (!$this->security->isGranted($this->model->getPermissionBase().':delete')) {
            return $this->accessDenied();
        }

        $definition = $this->getDefinition($objectSlug);
        $entity     = $this->model->getEntity($id);

        if (null === $entity || $entity->getObjectDefinition()->getId() !== $definition->getId()) {
            return $this->notFound();
        }

        $this->model->deleteEntity($entity);
        $view = $this->view(['instance' => $entity]);
        return $this->handleView($view);
    }
}
