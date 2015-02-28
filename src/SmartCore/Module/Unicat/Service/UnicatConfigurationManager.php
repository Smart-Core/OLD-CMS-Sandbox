<?php

namespace SmartCore\Module\Unicat\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use SmartCore\Bundle\MediaBundle\Service\CollectionService;
use SmartCore\Module\Unicat\Entity\UnicatConfiguration;
use SmartCore\Module\Unicat\Entity\UnicatStructure;
use SmartCore\Module\Unicat\Form\Type\ItemFormType;
use SmartCore\Module\Unicat\Form\Type\PropertiesGroupFormType;
use SmartCore\Module\Unicat\Form\Type\StructureFormType;
use SmartCore\Module\Unicat\Model\CategoryModel;
use SmartCore\Module\Unicat\Model\ItemModel;
use SmartCore\Module\Unicat\Model\PropertiesGroupModel;
use SmartCore\Module\Unicat\Model\PropertyModel;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;

class UnicatConfigurationManager
{
    /**
     * @var ManagerRegistry
     */
    protected $doctrine;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @var CollectionService
     */
    protected $mc;

    /**
     * @var UnicatConfiguration
     */
    protected $configuration;

    /**
     * @param ManagerRegistry $doctrine
     * @param FormFactoryInterface $formFactory
     * @param UnicatConfiguration $configuration
     * @param CollectionService $mc
     */
    public function __construct(
        ManagerRegistry $doctrine,
        FormFactoryInterface $formFactory,
        UnicatConfiguration $configuration,
        CollectionService $mc
    ) {
        $this->doctrine    = $doctrine;
        $this->em          = $doctrine->getManager();
        $this->formFactory = $formFactory;
        $this->mc          = $mc;
        $this->configuration = $configuration;
    }

    /**
     * @return UnicatConfiguration
     */
    public function getConfiguration()
    {
        return $this->configuration;
    }

    /**
     * @param array|null $orderBy
     * @return ItemModel|null
     */
    public function findAllItems($orderBy = null)
    {
        return $this->em->getRepository($this->configuration->getItemClass())->findBy([], $orderBy);
    }

    /**
     * @param CategoryModel $category
     * @param array $order
     * @return ItemModel[]|null
     *
     * @todo сделать настройку сортировки
     */
    public function findItemsInCategory(CategoryModel $category, array $order = ['position' => 'ASC'])
    {
        $itemEntity = $this->configuration->getItemClass();

        $query = $this->em->createQuery("
           SELECT i
           FROM $itemEntity AS i
           JOIN i.categoriesSingle AS cs
           WHERE cs.id = :category
           AND i.is_enabled = 1
           ORDER BY i.position ASC
        ")->setParameter('category', $category->getId());

        return $query->getResult();
    }

    /**
     * @param string|int $val
     * @return ItemModel|null
     */
    public function findItem($val)
    {
        $key = intval($val) ? 'id' : 'slug';

        return $this->em->getRepository($this->configuration->getItemClass())->findOneBy([$key => $val]);
    }

    /**
     * @param string $slug
     * @return CategoryModel[]
     * @throws \Symfony\Component\HttpKernel\Exception\NotFoundHttpException
     */
    public function findCategoriesBySlug($slug = null)
    {
        $categories = [];
        $parent = null;
        foreach (explode('/', $slug) as $categoryName) {
            if (strlen($categoryName) == 0) {
                break;
            }

            /** @var CategoryModel $category */
            $category = $this->getCategoryRepository()->findOneBy([
                'is_enabled' => true,
                'parent' => $parent,
                'slug'   => $categoryName,
            ]);

            if ($category) {
                $categories[] = $category;
                $parent = $category;
            } else {
                throw new NotFoundHttpException();
            }
        }

        return $categories;
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getCategoryRepository()
    {
        return $this->em->getRepository($this->configuration->getCategoryClass());
    }

    /**
     * @return string
     */
    public function getCategoryClass()
    {
        return $this->configuration->getCategoryClass();
    }

    /**
     * @return UnicatStructure
     */
    public function getDefaultStructure()
    {
        return $this->configuration->getDefaultStructure();
    }

    /**
     * @param mixed $data    The initial data for the form
     * @param array $options
     *
     * @return \Symfony\Component\Form\Form
     */
    public function getItemEditForm($data = null, array $options = [])
    {
        return $this->getItemForm($data, $options)
            ->add('update', 'submit', ['attr' => [ 'class' => 'btn btn-success' ]])
            ->add('delete', 'submit', ['attr' => [ 'class' => 'btn btn-danger', 'onclick' => "return confirm('Вы уверены, что хотите удалить запись?')" ]])
            ->add('cancel', 'submit', ['attr' => [ 'class' => 'btn', 'formnovalidate' => 'formnovalidate' ]]);
    }

    /**
     * @param mixed $data    The initial data for the form
     * @param array $options
     *
     * @return \Symfony\Component\Form\Form
     */
    public function getItemForm($data = null, array $options = [])
    {
        return $this->formFactory->create(new ItemFormType($this->configuration, $this->doctrine), $data, $options);
    }

    /**
     * @param mixed $data    The initial data for the form
     * @param array $options
     *
     * @return \Symfony\Component\Form\Form
     */
    public function getItemCreateForm($data = null, array $options = [])
    {
        return $this->getItemForm($data, $options)
            ->add('create', 'submit', ['attr' => [ 'class' => 'btn btn-success' ]])
            ->add('cancel', 'submit', ['attr' => [ 'class' => 'btn', 'formnovalidate' => 'formnovalidate' ]]);
    }

    /**
     * @param array $options
     * @return $this|\Symfony\Component\Form\Form
     */
    public function getStructureCreateForm(array $options = [])
    {
        $structure = new UnicatStructure();
        $structure->setConfiguration($this->configuration);

        return $this->getStructureForm($structure, $options)
            ->add('create', 'submit', ['attr' => [ 'class' => 'btn btn-success' ]])
            ->add('cancel', 'submit', ['attr' => [ 'class' => 'btn', 'formnovalidate' => 'formnovalidate' ]]);
    }

    /**
     * @param array $options
     * @return $this|\Symfony\Component\Form\Form
     */
    public function getStructureEditForm($data = null, array $options = [])
    {
        return $this->getStructureForm($data, $options)
            ->add('update', 'submit', ['attr' => [ 'class' => 'btn btn-success' ]])
            ->add('cancel', 'submit', ['attr' => [ 'class' => 'btn', 'formnovalidate' => 'formnovalidate' ]]);
    }

    /**
     * @param array $options
     * @return $this|\Symfony\Component\Form\Form
     */
    public function getPropertiesGroupCreateForm(array $options = [])
    {
        $group = $this->configuration->createPropertiesGroup();
        $group->setConfiguration($this->configuration);

        return $this->getPropertiesGroupForm($group, $options)
            ->add('create', 'submit', ['attr' => [ 'class' => 'btn btn-success' ]])
            ->add('cancel', 'submit', ['attr' => [ 'class' => 'btn', 'formnovalidate' => 'formnovalidate' ]]);
    }

    /**
     * @param mixed|null $data
     * @param array $options
     * @return \Symfony\Component\Form\Form
     */
    public function getStructureForm($data = null, array $options = [])
    {
        return $this->formFactory->create(new StructureFormType(), $data, $options);
    }

    /**
     * @param mixed|null $data
     * @param array $options
     * @return \Symfony\Component\Form\Form
     */
    public function getPropertiesGroupForm($data = null, array $options = [])
    {
        return $this->formFactory->create(new PropertiesGroupFormType($this->configuration), $data, $options);
    }

    /**
     * @param int $id
     * @return UnicatStructure
     */
    public function getStructure($id)
    {
        return $this->em->getRepository('UnicatModule:UnicatStructure')->find($id);
    }

    /**
     * @return ItemModel
     */
    public function createItemEntity()
    {
        $class = $this->configuration->getItemClass();

        return new $class();
    }

    /**
     * @param FormInterface $form
     * @param Request $request
     * @return $this
     *
     * @todo события
     */
    public function createItem(FormInterface $form, Request $request)
    {
        return $this->saveItem($form, $request);
    }

    /**
     * @param FormInterface $form
     * @param Request $request
     * @return $this
     *
     * @todo события
     */
    public function updateItem(FormInterface $form, Request $request)
    {
        return $this->saveItem($form, $request);
    }

    /**
     * @param ItemModel $item
     * @return $this
     *
     * @todo события
     */
    public function removeItem(ItemModel $item)
    {
        foreach ($this->getProperties() as $property) {
            if ($property->isType('image') and $item->hasProperty($property->getName())) {
                // @todo сделать кеширование при первом же вытаскивании данных о записи. тоже самое в saveItem(), а еще лучше выделить этот код в отельный защищенный метод.
                $tableItems = $this->em->getClassMetadata($this->configuration->getItemClass())->getTableName();
                $sql = "SELECT * FROM $tableItems WHERE id = '{$item->getId()}'";
                $res = $this->em->getConnection()->query($sql)->fetch();

                $fileId = null;
                if (!empty($res)) {
                    $previousProperties = unserialize($res['properties']);
                    $fileId = $previousProperties[$property->getName()];
                }

                $this->mc->remove($fileId);
            }
        }

        $this->em->remove($item);
        $this->em->flush(); // Надо делать полный flush т.к. каскадом удаляются связи с категориями.

        return $this;
    }

    /**
     * @param FormInterface $form
     * @param Request $request
     * @return $this|array
     */
    public function saveItem(FormInterface $form, Request $request)
    {
        /** @var ItemModel $item */
        $item = $form->getData();

        // Проверка и модификация свойств. В частности загрука картинок и валидация.
        foreach ($this->getProperties() as $property) {
            if ($property->isType('image') and $item->hasProperty($property->getName())) {
                $tableItems = $this->em->getClassMetadata($this->configuration->getItemClass())->getTableName();
                $sql = "SELECT * FROM $tableItems WHERE id = '{$item->getId()}'";
                $res = $this->em->getConnection()->query($sql)->fetch();

                if (!empty($res)) {
                    $previousProperties = unserialize($res['properties']);
                    $fileId = $previousProperties[$property->getName()];
                } else {
                    $fileId = null;
                }

                // удаление файла.
                $_delete_ = $request->request->get('_delete_');
                if (is_array($_delete_)
                    and isset($_delete_['property:'.$property->getName()])
                    and 'on' === $_delete_['property:'.$property->getName()]
                ) {
                    $this->mc->remove($fileId);
                    $fileId = null;
                } else {
                    $file = $item->getProperty($property->getName());

                    if ($file) {
                        $this->mc->remove($fileId);
                        $fileId = $this->mc->upload($file);
                    }
                }

                $item->setProperty($property->getName(), $fileId);
            }
        }

        //@todo $structuresColection = $this->em->getRepository($configuration->getCategoryClass())->findIn($structures);

        $pd = $request->request->get($form->getName());

        $structures = [];
        foreach ($pd as $key => $val) {
            if (false !== strpos($key, 'structure:')) {
                //$name = str_replace('structure:', '', $key);
                //$structures[$name] = $val;

                if (is_array($val)) {
                    foreach ($val as $val2) {
                        $structures[] = $val2;
                    }
                } else {
                    $structures[] = $val;
                }
            }
        }

        $request->request->set($form->getName(), $pd);

        // @todo убрать выборку структур в StructureRepository (Entity)
        $list_string = '';
        foreach ($structures as $node_id) {
            $list_string .= $node_id.',';
        }

        $list_string = substr($list_string, 0, strlen($list_string)-1);

        if (false == $list_string) {
            return [];
        }

        $structuresSingleColection = $this->em->createQuery("
            SELECT c
            FROM {$this->configuration->getCategoryClass()} c
            WHERE c.id IN({$list_string})
        ")->getResult();

        //$structuresCollection = new ArrayCollection(); // @todo наследуемые категории.

        $item->setCategories($structuresSingleColection)
            ->setCategoriesSingle($structuresSingleColection);

        $this->em->persist($item);
        $this->em->flush($item);

        return $this;
    }

    /**
     * @param int $groupId
     * @return PropertyModel[]
     */
    public function getProperties($groupId = null)
    {
        $filter = ($groupId) ? ['group' => $groupId] : [];

        return $this->em->getRepository($this->configuration->getPropertyClass())->findBy($filter, ['position' => 'ASC']);
    }

    /**
     * @param PropertyModel $property
     * @return $this
     */
    public function createProperty(PropertyModel $property)
    {
        $this->em->persist($property);
        $this->em->flush($property);

        return $this;
    }

    /**
     * @param CategoryModel $category
     * @return $this
     */
    public function updateCategory(CategoryModel $category)
    {
        $this->em->persist($category);
        $this->em->flush($category);

        return $this;
    }

    /**
     * @param PropertyModel $property
     * @return $this
     */
    public function updateProperty(PropertyModel $property)
    {
        $this->em->persist($property);
        $this->em->flush($property);

        return $this;
    }

    /**
     * @param PropertiesGroupModel $property
     * @return $this
     */
    public function updatePropertiesGroup(PropertiesGroupModel $entity)
    {
        $this->em->persist($entity);
        $this->em->flush($entity);

        return $this;
    }

    /**
     * @param UnicatStructure $entity
     * @return $this
     */
    public function updateStructure(UnicatStructure $entity)
    {
        $this->em->persist($entity);
        $this->em->flush($entity);

        return $this;
    }
}
