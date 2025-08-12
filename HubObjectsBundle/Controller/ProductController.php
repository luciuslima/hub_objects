<?php

declare(strict_types=1);

namespace MauticPlugin\HubObjectsBundle\Controller;

use Mautic\CoreBundle\Controller\FormController;
use Mautic\CoreBundle\Factory\MauticFactory;
use MauticPlugin\HubObjectsBundle\Entity\Product;
use MauticPlugin\HubObjectsBundle\Model\ProductModel;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class ProductController extends FormController
{
    /**
     * @param int $page
     */
    public function indexAction(Request $request, $page = 1): Response
    {
        /** @var ProductModel $model */
        $model = $this->getModel('hubobjects.product');
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
            'contentTemplate' => 'HubObjectsBundle:Product:list.html.php',
            'passthroughVars' => [
                'activeLink'    => '#mautic_hubobjects_product_index',
                'mauticContent' => 'hubobjectsProduct',
                'route'         => $this->generateUrl('mautic_hubobjects_product_index', ['page' => $this->page]),
            ],
        ]);
    }

    public function newAction(Request $request): Response
    {
        return $this->processForm($request, new Product());
    }

    public function editAction(Request $request, $objectId): Response
    {
        /** @var ProductModel $model */
        $model  = $this->getModel('hubobjects.product');
        $entity = $model->getEntity($objectId);
        if (null === $entity) {
            return $this->postActionRedirect($this->getNotFoundRedirect($objectId));
        }
        return $this->processForm($request, $entity);
    }

    public function viewAction(Request $request, $objectId): Response
    {
        /** @var ProductModel $model */
        $model  = $this->getModel('hubobjects.product');
        $entity = $model->getEntity($objectId);
        if (null === $entity) {
            return $this->postActionRedirect($this->getNotFoundRedirect($objectId));
        }
        return $this->delegateView([
            'viewParameters'  => ['item' => $entity],
            'contentTemplate' => 'HubObjectsBundle:Product:details.html.php',
            'passthroughVars' => [
                'activeLink'    => '#mautic_hubobjects_product_index',
                'mauticContent' => 'hubobjectsProduct',
            ],
        ]);
    }

    public function deleteAction(Request $request, $objectId): Response
    {
        /** @var ProductModel $model */
        $model  = $this->getModel('hubobjects.product');
        $entity = $model->getEntity($objectId);
        if (null === $entity) {
            return $this->postActionRedirect($this->getNotFoundRedirect($objectId));
        }
        $model->deleteEntity($entity);
        $this->addFlashMessage('mautic.core.notice.deleted', [
            '%name%' => $entity->getName(),
            '%id%'   => $entity->getId(),
        ]);
        return $this->postActionRedirect([
            'returnUrl'      => $this->generateUrl('mautic_hubobjects_product_index'),
            'contentTemplate' => 'HubObjectsBundle:Product:index',
            'passthroughVars' => ['activeLink' => 'mautic_hubobjects_product_index']
        ]);
    }

    protected function processForm(Request $request, Product $entity): Response
    {
        /** @var ProductModel $model */
        $model = $this->getModel('hubobjects.product');
        $form  = $model->createForm($entity, $this->get('form.factory'), null, [
            'action' => $this->generateUrl('mautic_hubobjects_product_edit', ['objectId' => $entity->getId() ?: 0]),
        ]);

        if ('POST' === $request->getMethod() && $this->isFormValid($form)) {
            $model->saveEntity($entity);
            $this->addFlashMessage('mautic.core.notice.saved', [
                '%name%'      => $entity->getName(),
                '%menu_link%' => 'mautic_hubobjects_product_index',
                '%url%'       => $this->generateUrl('mautic_hubobjects_product_edit', ['objectId' => $entity->getId()]),
            ]);
            if ($this->getFormButton($form, ['buttons', 'save'])->isClicked()) {
                return $this->postActionRedirect([
                    'returnUrl'      => $this->generateUrl('mautic_hubobjects_product_index'),
                    'contentTemplate' => 'HubObjectsBundle:Product:index',
                    'passthroughVars' => ['activeLink' => 'mautic_hubobjects_product_index']
                ]);
            }
             return $this->editAction($request, $entity->getId());
        }

        return $this->delegateView([
            'viewParameters' => [
                'form' => $form->createView(),
                'item' => $entity,
            ],
            'contentTemplate' => 'HubObjectsBundle:Product:form.html.php',
            'passthroughVars' => [
                'activeLink'    => '#mautic_hubobjects_product_index',
                'mauticContent' => 'hubobjectsProduct',
            ],
        ]);
    }

    private function getNotFoundRedirect($objectId): array
    {
        return [
            'returnUrl'      => $this->generateUrl('mautic_hubobjects_product_index'),
            'flashes'        => [['type' => 'error', 'msg' => 'mautic.core.error.notfound', 'msgVars' => ['%id%' => $objectId]]],
        ];
    }
}
