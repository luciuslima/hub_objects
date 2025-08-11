<?php

declare(strict_types=1);

namespace MauticPlugin\HubObjectsBundle\Controller;

use Mautic\CoreBundle\Controller\FormController;
use MauticPlugin\HubObjectsBundle\Entity\ObjectDefinition;
use MauticPlugin\HubObjectsBundle\Entity\ObjectInstance;
use MauticPlugin\HubObjectsBundle\Form\Type\ObjectInstanceType;
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

        if (!$this->security->isGranted($model->getPermissionBase().':view')) {
            return $this->accessDenied();
        }

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
        if ($totalItems && $this->page > 0) {
            $remaining = $totalItems - (($this->page - 1) * $this->limit);
            if ($remaining < 0) {
                $this->page = 1;
            }
        }

        return $this->delegateView([
            'viewParameters' => [
                'items'       => $items,
                'definition'  => $definition,
                'page'        => $this->page,
                'limit'       => $this->limit,
                'totalItems'  => $totalItems,
                'searchValue' => $this->search,
                'tmpl'        => $request->isXmlHttpRequest() ? $request->get('tmpl', 'index') : 'index',
            ],
            'contentTemplate' => 'HubObjectsBundle:ObjectInstance:list.html.php',
            'passthroughVars' => [
                'route' => $this->generateUrl('mautic_hubobjects_instance_index', ['objectSlug' => $objectSlug, 'page' => $this->page]),
            ],
        ]);
    }

    public function executeAction(Request $request, string $objectSlug, string $objectAction, int $objectId = 0): Response
    {
        $definition = $this->getObjectDefinition($objectSlug);
        /** @var ObjectInstanceModel $model */
        $model    = $this->getModel('hubobjects.instance');
        $permBase = $model->getPermissionBase();

        if ($objectAction === 'new') {
            if (!$this->security->isGranted($permBase.':create')) {
                return $this->accessDenied();
            }
            $entity = new ObjectInstance();
            $entity->setObjectDefinition($definition);
        } else {
            $entity = $model->getEntity($objectId);
            if (null === $entity || $entity->getObjectDefinition()->getId() !== $definition->getId()) {
                return $this->postActionRedirect($this->getNotFoundRedirect($objectSlug, $objectId));
            }
            $permType = ('delete' === $objectAction) ? 'delete' : 'edit';
            if (!$this->security->isGranted($permBase.':'.$permType)) {
                return $this->accessDenied();
            }
        }

        if ($objectAction === 'delete') {
            $model->deleteEntity($entity);
            $this->addFlashMessage('mautic.core.notice.deleted', ['%name%' => '#'.$entity->getId()]);
            return $this->postActionRedirect([
                'returnUrl' => $this->generateUrl('mautic_hubobjects_instance_index', ['objectSlug' => $objectSlug]),
            ]);
        }

        $form = $this->get('form.factory')->create(ObjectInstanceType::class, $entity, [
            'definition' => $definition,
            'action' => $this->generateUrl('mautic_hubobjects_instance_action', ['objectSlug' => $objectSlug, 'objectAction' => 'edit', 'objectId' => $entity->getId() ?: 0]),
        ]);

        if ('POST' === $request->getMethod() && $this->isFormValid($form)) {
            $model->saveEntity($entity);
            $this->addFlashMessage('mautic.core.notice.saved', ['%name%' => '#'.$entity->getId()]);
            if ($this->getFormButton($form, ['buttons', 'save'])->isClicked()) {
                return $this->postActionRedirect([
                    'returnUrl' => $this->generateUrl('mautic_hubobjects_instance_index', ['objectSlug' => $objectSlug]),
                ]);
            }
            return $this->redirect($this->generateUrl('mautic_hubobjects_instance_action', ['objectSlug' => $objectSlug, 'objectAction' => 'edit', 'objectId' => $entity->getId()]));
        }

        return $this->delegateView([
            'viewParameters' => [
                'form'       => $form->createView(),
                'item'       => $entity,
                'definition' => $definition,
            ],
            'contentTemplate' => 'HubObjectsBundle:ObjectInstance:form.html.php',
        ]);
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

    private function getNotFoundRedirect(string $objectSlug, int $objectId): array
    {
        return [
            'returnUrl' => $this->generateUrl('mautic_hubobjects_instance_index', ['objectSlug' => $objectSlug]),
            'flashes'   => [['type' => 'error', 'msg' => 'mautic.core.error.notfound', 'msgVars' => ['%id%' => $objectId]]],
        ];
    }
}
