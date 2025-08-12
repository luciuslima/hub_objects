<?php

declare(strict_types=1);

namespace MauticPlugin\HubObjectsBundle\Controller\Api;

use Mautic\ApiBundle\Controller\CommonApiController;
use MauticPlugin\HubObjectsBundle\Entity\Product;
use MauticPlugin\HubObjectsBundle\Form\Type\ProductType;
use MauticPlugin\HubObjectsBundle\Model\ProductModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;

class ProductApiController extends CommonApiController
{
    /**
     * @var ProductModel
     */
    protected $model;

    public function initialize(FilterControllerEvent $event): void
    {
        $this->model = $this->factory->getModel('hubobjects.product');
        parent::initialize($event);
    }

    public function getEntitiesAction(Request $request): Response
    {
        if (!$this->security->isGranted($this->model->getPermissionBase().':view')) {
            return $this->accessDenied();
        }

        $args     = $this->getStandardRequestParameters($request);
        $entities = $this->model->getEntities($args);
        $count    = count($entities);

        $view = $this->view(
            ['products' => $entities, 'total' => $count],
            200,
            [],
            ['serializerGroups' => ['productList']]
        );

        return $this->handleView($view);
    }

    public function getEntityAction(Request $request, $id): Response
    {
        if (!$this->security->isGranted($this->model->getPermissionBase().':view')) {
            return $this->accessDenied();
        }

        $entity = $this->model->getEntity($id);
        if (null === $entity) {
            return $this->notFound();
        }

        $view = $this->view(['product' => $entity], 200, [], ['serializerGroups' => ['productDetails']]);

        return $this->handleView($view);
    }

    public function newEntityAction(Request $request): Response
    {
        if (!$this->security->isGranted($this->model->getPermissionBase().':create')) {
            return $this->accessDenied();
        }

        $entity = $this->model->getEntity();
        $form   = $this->get('form.factory')->create(ProductType::class, $entity);

        $form->submit($request->request->all());

        if ($form->isSubmitted() && $form->isValid()) {
            $this->model->saveEntity($entity);
            $view = $this->view(['product' => $entity], 201, [], ['serializerGroups' => ['productDetails']]);
        } else {
            return $this->returnError($this->getFormErrorMessages($form), 400);
        }

        return $this->handleView($view);
    }

    public function editEntityAction(Request $request, $id, $createIfNotExists = false): Response
    {
        if (!$this->security->isGranted($this->model->getPermissionBase().':edit')) {
            return $this->accessDenied();
        }

        $entity = $this->model->getEntity($id);
        if (null === $entity) {
            if ($createIfNotExists) {
                $entity = $this->model->getEntity();
            } else {
                return $this->notFound();
            }
        }

        $form = $this->get('form.factory')->create(ProductType::class, $entity);
        $form->submit($request->request->all(), 'PATCH' !== $request->getMethod());

        if ($form->isSubmitted() && $form->isValid()) {
            $this->model->saveEntity($entity);
            $statusCode = $entity->isNew() ? 201 : 200;
            $view       = $this->view(['product' => $entity], $statusCode, [], ['serializerGroups' => ['productDetails']]);
        } else {
            return $this->returnError($this->getFormErrorMessages($form), 400);
        }

        return $this->handleView($view);
    }

    public function putEntityAction(Request $request, $id): Response
    {
        return $this->editEntityAction($request, $id, true);
    }

    public function deleteEntityAction(Request $request, $id): Response
    {
        if (!$this->security->isGranted($this->model->getPermissionBase().':delete')) {
            return $this->accessDenied();
        }

        $entity = $this->model->getEntity($id);
        if (null === $entity) {
            return $this->notFound();
        }

        $this->model->deleteEntity($entity);

        $view = $this->view(['product' => $entity]);

        return $this->handleView($view);
    }
}
