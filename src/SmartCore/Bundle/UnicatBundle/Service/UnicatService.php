<?php

namespace SmartCore\Bundle\UnicatBundle\Service;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\ORM\EntityManager;
use SmartCore\Bundle\MediaBundle\Service\CollectionService;
use SmartCore\Bundle\MediaBundle\Service\MediaCloudService;
use SmartCore\Bundle\UnicatBundle\Entity\UnicatRepository;
use SmartCore\Bundle\UnicatBundle\Entity\UnicatStructure;
use SmartCore\Bundle\UnicatBundle\Form\Type\CategoryCreateFormType;
use SmartCore\Bundle\UnicatBundle\Form\Type\CategoryFormType;
use SmartCore\Bundle\UnicatBundle\Form\Type\ItemFormType;
use SmartCore\Bundle\UnicatBundle\Form\Type\PropertyFormType;
use SmartCore\Bundle\UnicatBundle\Model\CategoryModel;
use SmartCore\Bundle\UnicatBundle\Model\ItemModel;
use SmartCore\Bundle\UnicatBundle\Model\PropertyModel;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\Form\FormInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Security\Core\SecurityContextInterface;

class UnicatService
{
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
     * @var SecurityContextInterface
     */
    protected $securityContext;

    /**
     * @var UnicatRepositoryManager[]
     */
    protected $urms;

    /**
     * @param EntityManager $em
     * @param FormFactoryInterface $formFactory
     * @param MediaCloudService $mediaCloud
     */
    public function __construct(
        EntityManager $em,
        FormFactoryInterface $formFactory,
        MediaCloudService $mediaCloud,
        SecurityContextInterface $securityContext
    ) {
        $this->em          = $em;
        $this->formFactory = $formFactory;
        $this->mc          = $mediaCloud->getCollection(1); // @todo настройку медиаколлекции.
        $this->securityContext = $securityContext;
    }

    /**
     * @param string|int $repository_id
     * @return UnicatRepositoryManager|null
     */
    public function getRepositoryManager($repository_id)
    {
        if (empty($repository_id)) {
            return null;
        }

        $repository = $this->getRepository($repository_id);

        if (!isset($this->urms[$repository->getId()])) {
            $this->urms[$repository->getId()] = new UnicatRepositoryManager($this->em, $this->formFactory, $repository);
        }

        return $this->urms[$repository->getId()];
    }

    /**
     * @param UnicatRepository $repository
     * @param mixed $data    The initial data for the form
     * @param array $options
     *
     * @return \Symfony\Component\Form\Form
     */
    public function getCategoryForm(UnicatRepository $repository, $data = null, array $options = [])
    {
        return $this->formFactory->create(new CategoryFormType($repository), $data, $options);
    }

    /**
     * @param UnicatStructure $structure
     * @param array $options
     *
     * @return \Symfony\Component\Form\Form
     */
    public function getCategoryCreateForm(UnicatStructure $structure, array $options = [])
    {
        $category = $structure->getRepository()->createCategory();
        $category
            ->setStructure($structure)
            ->setIsInheritance($structure->getIsDefaultInheritance())
            ->setUserId($this->getUserId())
        ;

        return $this->formFactory->create(new CategoryCreateFormType($structure->getRepository()), $category, $options)
            ->add('create', 'submit', ['attr' => [ 'class' => 'btn btn-success' ]]);
    }

    /**
     * @param UnicatRepository $repository
     * @param array $options
     *
     * @return \Symfony\Component\Form\Form
     */
    public function getCategoryEditForm(CategoryModel $category, array $options = [])
    {
        return $this->getCategoryForm($category->getStructure()->getRepository(), $category, $options)
            ->add('update', 'submit', ['attr' => [ 'class' => 'btn btn-success' ]])
            ->add('cancel', 'submit', ['attr' => [ 'class' => 'btn', 'formnovalidate' => 'formnovalidate' ]]);
    }

    /**
     * @param UnicatRepository $repository
     * @param mixed $data    The initial data for the form
     * @param array $options
     *
     * @return \Symfony\Component\Form\Form
     */
    public function getPropertyForm(UnicatRepository $repository, $data = null, array $options = [])
    {
        return $this->formFactory->create(new PropertyFormType($repository), $data, $options);
    }

    /**
     * @param UnicatRepository $repository
     * @param mixed $data    The initial data for the form
     * @param array $options
     *
     * @return \Symfony\Component\Form\Form
     */
    public function getItemForm(UnicatRepository $repository, $data = null, array $options = [])
    {
        return $this->formFactory->create(new ItemFormType($repository), $data, $options);
    }

    /**
     * @param UnicatRepository $repository
     * @param mixed $data    The initial data for the form
     * @param array $options
     *
     * @return \Symfony\Component\Form\Form
     */
    public function getItemCreateForm(UnicatRepository $repository, $data = null, array $options = [])
    {
        return $this->getItemForm($repository, $data, $options)
            ->add('create', 'submit', ['attr' => [ 'class' => 'btn btn-success' ]]);
    }

    /**
     * @param UnicatRepository $repository
     * @param mixed $data    The initial data for the form
     * @param array $options
     *
     * @return \Symfony\Component\Form\Form
     */
    public function getItemEditForm(UnicatRepository $repository, $data = null, array $options = [])
    {
        return $this->getItemForm($repository, $data, $options)
            ->add('update', 'submit', ['attr' => [ 'class' => 'btn btn-success' ]])
            ->add('cancel', 'submit', ['attr' => [ 'class' => 'btn', 'formnovalidate' => 'formnovalidate' ]]);
    }

    /**
     * @param UnicatRepository $repository
     * @param array $options
     *
     * @return \Symfony\Component\Form\Form
     */
    public function getPropertyCreateForm(UnicatRepository $repository, $groupId, array $options = [])
    {
        $property = $repository->createProperty();
        $property
            ->setGroup($this->em->getRepository($repository->getPropertiesGroupClass())->find($groupId))
            ->setUserId($this->getUserId())
        ;

        return $this->getPropertyForm($repository, $property, $options)
            ->add('create', 'submit', ['attr' => [ 'class' => 'btn btn-success' ]]);
    }

    /**
     * @param UnicatRepository $repository
     * @param array $options
     *
     * @return \Symfony\Component\Form\Form
     */
    public function getPropertyEditForm(UnicatRepository $repository, $property, array $options = [])
    {
        return $this->getPropertyForm($repository, $property, $options)
            ->add('update', 'submit', ['attr' => [ 'class' => 'btn btn-success' ]])
            ->add('cancel', 'submit', ['attr' => [ 'class' => 'btn', 'formnovalidate' => 'formnovalidate' ]]);
    }

    /**
     * @param UnicatStructure $structure
     * @param int $id
     * @return CategoryModel|null
     */
    public function getCategory(UnicatStructure $structure, $id)
    {
        return $this->em->getRepository($structure->getRepository()->getCategoryClass())->find($id);
    }

    /**
     * @param UnicatRepository $repository
     * @param int $id
     * @return ItemModel|null
     */
    public function getItem(UnicatRepository $repository, $id)
    {
        return $this->em->getRepository($repository->getItemClass())->find($id);
    }

    /**
     * @param UnicatRepository $repository
     * @param array|null $orderBy
     * @return ItemModel|null
     */
    public function findAllItems(UnicatRepository $repository, $orderBy = null)
    {
        return $this->em->getRepository($repository->getItemClass())->findBy([], $orderBy);
    }

    /**
     * @param UnicatRepository $repository
     * @param int $groupId
     * @return PropertyModel[]
     */
    public function getProperties(UnicatRepository $repository, $groupId = null)
    {
        $filter = ($groupId) ? ['group' => $groupId] : [];

        return $this->em->getRepository($repository->getPropertyClass())->findBy($filter, ['position' => 'ASC']);
    }

    /**
     * @param UnicatRepository $repository
     * @param int $groupId
     * @return PropertyModel[]
     */
    public function getPropertiesGroup(UnicatRepository $repository, $groupId)
    {
        return $this->em->getRepository($repository->getPropertiesGroupClass())->find($groupId);
    }

    /**
     * @param UnicatRepository $repository
     * @param int $groupId
     * @return PropertyModel[]
     */
    public function getProperty(UnicatRepository $repository, $id)
    {
        return $this->em->getRepository($repository->getPropertyClass())->find($id);
    }

    /**
     * @param int|string $val
     * @return UnicatRepository
     */
    public function getRepository($val)
    {
        $key = intval($val) ? 'id' : 'name';

        return $this->em->getRepository('UnicatBundle:UnicatRepository')->findOneBy([$key => $val]);
    }

    /**
     * @return UnicatRepository[]
     */
    public function allRepositories()
    {
        return $this->em->getRepository('UnicatBundle:UnicatRepository')->findAll();
    }

    /**
     * @param int $id
     * @return UnicatStructure
     */
    public function getStructure($id)
    {
        return $this->em->getRepository('UnicatBundle:UnicatStructure')->find($id);
    }

    /**
     * @param CategoryModel $category
     * @return $this
     */
    public function createCategory(CategoryModel $category)
    {
        $this->em->persist($category);
        $this->em->flush($category);

        return $this;
    }

    /**
     * @param FormInterface $form
     * @param Request $request
     * @return $this
     */
    public function createItem(FormInterface $form, Request $request)
    {
        return $this->saveItem($form, $request);
    }

    /**
     * @param FormInterface $form
     * @param Request $request
     * @return $this
     */
    public function updateItem(FormInterface $form, Request $request)
    {
        return $this->saveItem($form, $request);
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

        /** @var UnicatRepository $repository */
        $repository = $form->getConfig()->getType()->getInnerType()->getRepository();

        // Проверка и модификация свойств. В частности загрука картинок и валидация.
        $properties = $this->getProperties($repository);

        foreach ($properties as $property) {
            if ($property->isType('image') and $item->hasProperty($property->getName()) ) {
                $tableItems = $this->em->getClassMetadata($repository->getItemClass())->getTableName();
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
                if (is_array($_delete_) and isset($_delete_['property:' . $property->getName()]) and 'on' === $_delete_['property:' . $property->getName()]) {
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

        //@todo $structuresColection = $this->em->getRepository($repository->getCategoryClass())->findIn($structures);

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
            $list_string .= $node_id . ',';
        }

        $list_string = substr($list_string, 0, strlen($list_string)-1);

        if (false == $list_string) {
            return [];
        }

        $structuresSingleColection = $this->em->createQuery("
            SELECT c
            FROM {$repository->getCategoryClass()} c
            WHERE c.id IN({$list_string})
        ")->getResult();

        $structuresColection = new ArrayCollection(); // @todo наследуемые категории.

        $item->setCategories($structuresSingleColection)
             ->setCategoriesSingle($structuresSingleColection);

        $this->em->persist($item);
        $this->em->flush($item);

        return $this;
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
        $properties = $category->getProperties();

        foreach ($properties as $propertyName => $propertyValue) {
            if ($propertyValue instanceof UploadedFile) {
                $fileId = $this->mc->upload($propertyValue);
                $category->setProperty($propertyName, $fileId);
            }
        }

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
     * @param CategoryModel $category
     * @return $this
     */
    public function deleteCategory(CategoryModel $category)
    {
        throw new \Exception('@todo решить что сделать с вложенными категориями, а также с сопряженными записями');

        $this->em->remove($category);
        $this->em->flush($category);

        return $this;
    }

    /**
     * @param PropertyModel $property
     * @return $this
     */
    public function deleteProperty(PropertyModel $property)
    {
        throw new \Exception('@todo надо решить как поступать с данными записей');

        $this->em->remove($property);
        $this->em->flush($property);

        return $this;
    }

    /**
     * @return int
     */
    protected function getUserId()
    {
        if (null === $token = $this->securityContext->getToken()) {
            return 0;
        }

        if (!is_object($user = $token->getUser())) {
            return 0;
        }

        return $user->getId();
    }
}
