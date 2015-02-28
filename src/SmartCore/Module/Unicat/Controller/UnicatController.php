<?php

namespace SmartCore\Module\Unicat\Controller;

use Knp\RadBundle\Controller\Controller;
use SmartCore\Bundle\CMSBundle\Module\NodeTrait;
use SmartCore\Module\Unicat\Model\CategoryModel;
use Symfony\Component\HttpFoundation\Response;

class UnicatController extends Controller
{
    use NodeTrait;

    protected $configuration_id;

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        return $this->categoryAction();
    }

    /**
     * @param string $slug
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function categoryAction($slug = null)
    {
        if (null === $this->configuration_id) {
            return new Response('Module Unicat not yet configured. Node: '.$this->node->getId().'<br />');
        }

        $urm = $this->get('unicat')->getConfigurationManager($this->configuration_id);

        $requestedCategories = $urm->findCategoriesBySlug($slug);

        foreach ($requestedCategories as $category) {
            $this->get('cms.breadcrumbs')->add($this->generateUrl('unicat.category', ['slug' => $category->getSlugFull()]).'/', $category->getTitle());
        }

        $lastCategory = end($requestedCategories);

        if ($lastCategory instanceof CategoryModel) {
            $this->get('html')->setMetas($lastCategory->getMeta());
            $childenCategories = $urm->getCategoryRepository()->findBy([
                'is_enabled' => true,
                'parent'     => $lastCategory,
                'structure'  => $urm->getDefaultStructure(),
            ], ['position' => 'ASC']);
        } else {
            $childenCategories = $urm->getCategoryRepository()->findBy([
                'is_enabled' => true,
                'parent'     => null,
                'structure'  => $urm->getDefaultStructure(),
            ], ['position' => 'ASC']);
        }

        $this->node->addFrontControl('create_item')
            ->setTitle('Добавить запись')
            ->setUri($this->generateUrl('unicat_admin.item_create_in_category', [
                'configuration'       => $urm->getConfiguration()->getName(),
                'default_category_id' => empty($lastCategory) ? 0 : $lastCategory->getId(),
            ]));

        if (!empty($lastCategory)) {
            $this->node->addFrontControl('create_category')
                ->setIsDefault(false)
                ->setTitle('Создать категорию')
                ->setUri($this->generateUrl('unicat_admin.structure_with_parent_id', [
                    'configuration' => $urm->getConfiguration()->getName(),
                    'parent_id'     => empty($lastCategory) ? 0 : $lastCategory->getId(),
                    'id'            => $lastCategory->getStructure()->getId(),
                ]));

            $this->node->addFrontControl('edit_category')
                ->setIsDefault(false)
                ->setTitle('Редактировать категорию')
                ->setUri($this->generateUrl('unicat_admin.category', [
                    'configuration' => $urm->getConfiguration()->getName(),
                    'id'            => $lastCategory->getId(),
                    'structure_id'  => $lastCategory->getStructure()->getId(),
                ]));
        }

        $this->node->addFrontControl('manage_configuration')
            ->setIsDefault(false)
            ->setTitle('Управление каталогом')
            ->setUri($this->generateUrl('unicat_admin.configuration', ['configuration' => $urm->getConfiguration()->getName()]));

        return $this->render('UnicatModule::items.html.twig', [
            'category'          => $lastCategory,
            'childenCategories' => $childenCategories,
            'items'             => $lastCategory ? $urm->findItemsInCategory($lastCategory) : null,
        ]);
    }

    /**
     * @param string $slug
     * @param string $itemSlug
     * @return Response
     */
    public function itemAction($slug, $itemSlug)
    {
        if (null === $this->configuration_id) {
            return new Response('Module Unicat not yet configured. Node: '.$this->node->getId().'<br />');
        }

        $urm = $this->get('unicat')->getConfigurationManager($this->configuration_id);

        $requestedCategories = $urm->findCategoriesBySlug($slug);

        foreach ($requestedCategories as $category) {
            $this->get('cms.breadcrumbs')->add($this->generateUrl('unicat.category', ['slug' => $category->getSlugFull()]).'/', $category->getTitle());
        }

        $lastCategory = end($requestedCategories);

        if ($lastCategory instanceof CategoryModel) {
            $childenCategories = $urm->getCategoryRepository()->findBy([
                'is_enabled' => true,
                'parent'     => $lastCategory,
                'structure'  => $urm->getDefaultStructure(),
            ]);
        } else {
            $childenCategories = $urm->getCategoryRepository()->findBy([
                'is_enabled' => true,
                'parent'     => null,
                'structure'  => $urm->getDefaultStructure(),
            ]);
        }

        $item = $urm->findItem($itemSlug);

        if (empty($item)) {
            throw $this->createNotFoundException();
        }

        $this->get('html')->setMetas($item->getMeta());

        $this->get('cms.breadcrumbs')->add($this->generateUrl('unicat.item', [
                'slug' => $lastCategory->getSlugFull(),
                'itemSlug' => $item->getSlug(),
            ]).'/', $item->getAttribute('title'));

        $this->node->addFrontControl('edit')
            ->setTitle('Редактировать')
            ->setUri($this->generateUrl('unicat_admin.item_edit', ['configuration' => $urm->getConfiguration()->getName(), 'id' => $item->getId() ]));

        return $this->render('UnicatModule::item.html.twig', [
            'category'          => $lastCategory,
            'childenCategories' => $childenCategories,
            'item'              => $item,
        ]);
    }
}
