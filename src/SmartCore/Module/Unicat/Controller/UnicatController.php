<?php

namespace SmartCore\Module\Unicat\Controller;

use Knp\RadBundle\Controller\Controller;
use SmartCore\Bundle\CMSBundle\Module\NodeTrait;
use SmartCore\Module\Unicat\Model\CategoryModel;
use SmartCore\Module\Unicat\Service\UnicatConfigurationManager;
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

        $ucm = $this->get('unicat')->getConfigurationManager($this->configuration_id);

        $requestedCategories = $ucm->findCategoriesBySlug($slug, $ucm->getDefaultStructure());

        foreach ($requestedCategories as $category) {
            $this->get('cms.breadcrumbs')->add($this->generateUrl('unicat.category', ['slug' => $category->getSlugFull()]).'/', $category->getTitle());
        }

        $lastCategory = end($requestedCategories);

        if ($lastCategory instanceof CategoryModel) {
            $this->get('html')->setMetas($lastCategory->getMeta());
            $childenCategories = $ucm->getCategoryRepository()->findBy([
                'is_enabled' => true,
                'parent'     => $lastCategory,
                'structure'  => $ucm->getDefaultStructure(),
            ], ['position' => 'ASC']);
        } else {
            $childenCategories = $ucm->getCategoryRepository()->findBy([
                'is_enabled' => true,
                'parent'     => null,
                'structure'  => $ucm->getDefaultStructure(),
            ], ['position' => 'ASC']);
        }

        $this->buuldFrontControlForCategory($ucm, $lastCategory);

        $items = null;

        if ($slug) {
            $items = $lastCategory ? $ucm->findItemsInCategory($lastCategory) : null;
        } elseif($ucm->getConfiguration()->isInheritance()) {
            $items = $ucm->findAllItems();
        }

        return $this->render('UnicatModule::items.html.twig', [
            'configuration'     => $ucm->getConfiguration(),
            'lastCategory'      => $lastCategory,
            'childenCategories' => $childenCategories,
            'items'             => $items,
        ]);
    }

    /**
     * @param UnicatConfigurationManager $ucm
     * @param CategoryModel|false $lastCategory
     * @throws \Exception
     */
    protected function buuldFrontControlForCategory(UnicatConfigurationManager $ucm, $lastCategory = false)
    {
        $this->node->addFrontControl('create_item')
            ->setTitle('Добавить запись')
            ->setUri($this->generateUrl('unicat_admin.item_create_in_category', [
                'configuration'       => $ucm->getConfiguration()->getName(),
                'default_category_id' => empty($lastCategory) ? 0 : $lastCategory->getId(),
            ]));

        if (!empty($lastCategory)) {
            $this->node->addFrontControl('create_category')
                ->setIsDefault(false)
                ->setTitle('Создать категорию')
                ->setUri($this->generateUrl('unicat_admin.structure_with_parent_id', [
                    'configuration' => $ucm->getConfiguration()->getName(),
                    'parent_id'     => empty($lastCategory) ? 0 : $lastCategory->getId(),
                    'id'            => $lastCategory->getStructure()->getId(),
                ]));

            $this->node->addFrontControl('edit_category')
                ->setIsDefault(false)
                ->setTitle('Редактировать категорию')
                ->setUri($this->generateUrl('unicat_admin.category', [
                    'configuration' => $ucm->getConfiguration()->getName(),
                    'id'            => $lastCategory->getId(),
                    'structure_id'  => $lastCategory->getStructure()->getId(),
                ]));
        }

        $this->node->addFrontControl('manage_configuration')
            ->setIsDefault(false)
            ->setTitle('Управление каталогом')
            ->setUri($this->generateUrl('unicat_admin.configuration', ['configuration' => $ucm->getConfiguration()->getName()]));
    }
    
    /**
     * @param string|null $structureSlug
     * @param string $itemSlug
     * @return Response
     */
    public function itemAction($structureSlug = null, $itemSlug)
    {
        if (null === $this->configuration_id) {
            return new Response('Module Unicat not yet configured. Node: '.$this->node->getId().'<br />');
        }

        $ucm = $this->get('unicat')->getConfigurationManager($this->configuration_id);

        $requestedCategories = $ucm->findCategoriesBySlug($structureSlug, $ucm->getDefaultStructure());

        foreach ($requestedCategories as $category) {
            $this->get('cms.breadcrumbs')->add($this->generateUrl('unicat.category', ['slug' => $category->getSlugFull()]).'/', $category->getTitle());
        }

        $lastCategory = end($requestedCategories);

        if ($lastCategory instanceof CategoryModel) {
            $childenCategories = $ucm->getCategoryRepository()->findBy([
                'is_enabled' => true,
                'parent'     => $lastCategory,
                'structure'  => $ucm->getDefaultStructure(),
            ]);
        } else {
            $childenCategories = $ucm->getCategoryRepository()->findBy([
                'is_enabled' => true,
                'parent'     => null,
                'structure'  => $ucm->getDefaultStructure(),
            ]);
        }

        $item = $ucm->findItem($itemSlug);

        if (empty($item)) {
            throw $this->createNotFoundException();
        }

        $this->get('html')->setMetas($item->getMeta());

        $this->get('cms.breadcrumbs')->add($this->generateUrl('unicat.item', [
                'slug' => empty($lastCategory) ? '' : $lastCategory->getSlugFull(),
                'itemSlug' => $item->getSlug(),
            ]).'/', $item->getAttribute('title'));

        $this->node->addFrontControl('edit')
            ->setTitle('Редактировать')
            ->setUri($this->generateUrl('unicat_admin.item_edit', ['configuration' => $ucm->getConfiguration()->getName(), 'id' => $item->getId() ]));

        return $this->render('UnicatModule::item.html.twig', [
            'lastCategory'      => $lastCategory,
            'childenCategories' => $childenCategories,
            'item'              => $item,
        ]);
    }
}
