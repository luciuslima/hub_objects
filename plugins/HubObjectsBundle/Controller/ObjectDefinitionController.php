<?php

declare(strict_types=1);

namespace MauticPlugin\HubObjectsBundle\Controller;

use Mautic\CoreBundle\Controller\FormController;
use MauticPlugin\HubObjectsBundle\Entity\ObjectDefinition;
use MauticPlugin\HubObjectsBundle\Form\Type\ObjectDefinitionType;
use MauticPlugin\HubObjectsBundle\Model\ObjectDefinitionModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ObjectDefinitionController extends FormController
{
    /**
     * @param int $page
     */
    public function indexAction(Request $request, $page = 1): Response
    {
        /** @var ObjectDefinitionModel $model */
        $model = $this->getModel('hubobjects.definition');

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
                'page'        => $this->page,
                'limit'       => $this->limit,
                'totalItems'  => $totalItems,
                'searchValue' => $this->search,
                'tmpl'        => $request->isXmlHttpRequest() ? $request->get('tmpl', 'index') : 'index',
            ],
            'contentTemplate' => 'HubObjectsBundle:ObjectDefinition:list.html.php',
            'passthroughVars' => [
                'activeLink'    => '#mautic_hubobjects_definition_index',
                'mauticContent' => 'hubobjectsDefinition',
                'route'         => $this->generateUrl('mautic_hubobjects_definition_index', ['page' => $this->page]),
            ],
        ]);
    }

    /**
     * @param string $objectAction
     * @param int    $objectId
     */
    public function executeAction(Request $request, $objectAction, $objectId = 0): Response
    {
        /** @var ObjectDefinitionModel $model */
        $model  = $this->getModel('hubobjects.definition');
        $permBase = $model->getPermissionBase();

        if ($objectAction === 'new') {
            if (!$this->security->isGranted($permBase.':create')) {
                return $this->accessDenied();
            }
            $entity = new ObjectDefinition();
        } else {
            $entity = $model->getEntity($objectId);
            if (null === $entity) {
                return $this->postActionRedirect($this->getNotFoundRedirect($objectId));
            }

            $permType = ('delete' === $objectAction) ? 'delete' : 'edit';
            if (!$this->security->isGranted($permBase.':'.$permType, 'MATCH_ONE')) {
                 return $this->accessDenied();
            }
        }

        if ($objectAction === 'delete') {
            $model->deleteEntity($entity);

            $this->addFlashMessage(
                'mautic.core.notice.deleted',
                [
                    '%name%' => $entity->getName(),
                    '%id%'   => $entity->getId(),
                ]
            );

            return $this->postActionRedirect(
                [
                    'returnUrl'       => $this->generateUrl('mautic_hubobjects_definition_index'),
                    'passthroughVars' => ['activeLink' => 'mautic_hubobjects_definition_index'],
                ]
            );
        }

        $form = $this->get('form.factory')->create(ObjectDefinitionType::class, $entity, [
            'action' => $this->generateUrl('mautic_hubobjects_definition_action', ['objectAction' => 'edit', 'objectId' => $entity->getId() ?: 0]),
        ]);

        if ('POST' === $request->getMethod()) {
            if ($this->isFormValid($form)) {
                /** @var ObjectDefinitionModel $model */
                $model = $this->getModel('hubobjects.definition');
                $model->saveEntity($entity);
                $this->addFlashMessage('mautic.core.notice.saved', [
                    '%name%'      => $entity->getName(),
                    '%menu_link%' => 'mautic_hubobjects_definition_index',
                    '%url%'       => $this->generateUrl('mautic_hubobjects_definition_action', ['objectAction' => 'edit', 'objectId' => $entity->getId()]),
                ]);
                if ($this->getFormButton($form, ['buttons', 'save'])->isClicked()) {
                    return $this->postActionRedirect([
                        'returnUrl' => $this->generateUrl('mautic_hubobjects_definition_index'),
                    ]);
                }
                return $this->redirect($this->generateUrl('mautic_hubobjects_definition_action', ['objectAction' => 'edit', 'objectId' => $entity->getId()]));
            }
        }

        return $this->delegateView([
            'viewParameters' => [
                'form' => $form->createView(),
                'item' => $entity,
            ],
            'contentTemplate' => 'HubObjectsBundle:ObjectDefinition:form.html.php',
        ]);
    }

    private function getNotFoundRedirect($objectId): array
    {
        return [
            'returnUrl'      => $this->generateUrl('mautic_hubobjects_definition_index'),
            'flashes'        => [['type' => 'error', 'msg' => 'mautic.core.error.notfound', 'msgVars' => ['%id%' => $objectId]]],
        ];
    }
}
