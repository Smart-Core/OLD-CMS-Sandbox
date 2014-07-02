<?php

namespace SmartCore\Bundle\Unicat2Bundle\Service;

use Doctrine\ORM\EntityManager;
use SmartCore\Bundle\MediaBundle\Service\CollectionService;
use SmartCore\Bundle\MediaBundle\Service\MediaCloudService;
use SmartCore\Bundle\Unicat2Bundle\Entity\UnicatRepository;
use SmartCore\Bundle\Unicat2Bundle\Entity\UnicatStructure;
use SmartCore\Bundle\Unicat2Bundle\Form\Type\PropertyFormType;
use SmartCore\Bundle\Unicat2Bundle\Form\Type\RepositoryFormType;
use SmartCore\Bundle\Unicat2Bundle\Model\ItemModel;
use SmartCore\Bundle\Unicat2Bundle\Model\PropertyModel;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
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
     * @param SecurityContextInterface $securityContext
     */
    public function __construct(
        EntityManager $em,
        FormFactoryInterface $formFactory,
        MediaCloudService $mediaCloud,
        SecurityContextInterface $securityContext
    ) {
        $this->em          = $em;
        $this->formFactory = $formFactory;
        $this->mc          = $mediaCloud->getCollection(1); // @todo настройку медиаколлекции. @important
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
            $this->urms[$repository->getId()] = new UnicatRepositoryManager(
                $this->em,
                $this->formFactory,
                $repository,
                $this->mc,
                $this->securityContext
            );
        }

        return $this->urms[$repository->getId()];
    }

    /**
     * @param string|int $repository_id
     * @return \SmartCore\Bundle\UnicatBundle\Model\PropertyModel[]
     */
    public function getProperties($repository)
    {
        if ($repository instanceof UnicatRepository) {
            $repository = $repository->getId();
        }

        return $this->getRepositoryManager($repository)->getProperties();
    }

    /**
     * @param UnicatRepository $repository
     * @param mixed $data    The initial data for the form
     * @param array $options
     *
     * @return \Symfony\Component\Form\Form
     *
     * @deprecated убрать в UnicatRepositoryManager
     */
    public function getCategoryForm(UnicatRepository $repository, $data = null, array $options = [])
    {
        return $this->formFactory->create(new CategoryFormType($repository), $data, $options);
    }

    /**
     * @param UnicatRepository $repository
     * @param array $options
     *
     * @return \Symfony\Component\Form\Form
     *
     * @deprecated
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
     *
     * @deprecated
     */
    public function getPropertyForm(UnicatRepository $repository, $data = null, array $options = [])
    {
        return $this->formFactory->create(new PropertyFormType($repository), $data, $options);
    }

    /**
     * @param UnicatRepository $repository
     * @param array $options
     *
     * @return \Symfony\Component\Form\Form
     *
     * @deprecated
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
     *
     * @deprecated
     */
    public function getPropertyEditForm(UnicatRepository $repository, $property, array $options = [])
    {
        return $this->getPropertyForm($repository, $property, $options)
            ->add('update', 'submit', ['attr' => [ 'class' => 'btn btn-success' ]])
            ->add('cancel', 'submit', ['attr' => [ 'class' => 'btn', 'formnovalidate' => 'formnovalidate' ]]);
    }

    /**
     * @param UnicatRepository $repository
     * @param array $options
     *
     * @return \Symfony\Component\Form\Form
     */
    public function getRepositoryEditForm($data = null, array $options = [])
    {
        return $this->getRepositoryForm($data, $options)
            ->add('update', 'submit', ['attr' => [ 'class' => 'btn btn-success' ]])
            ->add('cancel', 'submit', ['attr' => [ 'class' => 'btn', 'formnovalidate' => 'formnovalidate' ]]);
    }

    /**
     * @param UnicatRepository|null $data
     * @param array $options
     * @return \Symfony\Component\Form\FormInterface
     */
    public function getRepositoryForm($data = null, array $options = [])
    {
        return $this->formFactory->create(new RepositoryFormType(), $data, $options);
    }

    /**
     * @param UnicatStructure $structure
     * @param int $id
     *
     * @return ItemModel|null
     *
     * @deprecated
     */
    public function getCategory(UnicatStructure $structure, $id)
    {
        return $this->em->getRepository($structure->getRepository()->getCategoryClass())->find($id);
    }

    /**
     * @param UnicatRepository $repository
     * @param int $id
     *
     * @return ItemModel|null
     *
     * @deprecated
     */
    public function getItem(UnicatRepository $repository, $id)
    {
        return $this->em->getRepository($repository->getItemClass())->find($id);
    }

    /**
     * @param UnicatRepository $repository
     * @param array|null $orderBy
     *
     * @return ItemModel|null
     *
     * @deprecated
     */
    public function findAllItems(UnicatRepository $repository, $orderBy = null)
    {
        return $this->em->getRepository($repository->getItemClass())->findBy([], $orderBy);
    }

    /**
     * @param UnicatRepository $repository
     * @param int $groupId
     * @return PropertyModel[]
     *
     * @deprecated
     */
    public function getPropertiesGroup(UnicatRepository $repository, $groupId)
    {
        return $this->em->getRepository($repository->getPropertiesGroupClass())->find($groupId);
    }

    /**
     * @param UnicatRepository $repository
     * @param int $groupId
     * @return PropertyModel[]
     *
     * @deprecated
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

        return $this->em->getRepository('Unicat2Bundle:UnicatRepository')->findOneBy([$key => $val]);
    }

    /**
     * @return UnicatRepository[]
     */
    public function allRepositories()
    {
        return $this->em->getRepository('Unicat2Bundle:UnicatRepository')->findAll();
    }

    /**
     * @param int $id
     * @return UnicatStructure
     */
    public function getStructure($id)
    {
        return $this->em->getRepository('Unicat2Bundle:UnicatStructure')->find($id);
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
     *
     * @deprecated
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
     * @param UnicatRepository $repository
     * @return $this
     */
    public function updateRepository(UnicatRepository $repository)
    {
        $this->em->persist($repository);
        $this->em->flush($repository);

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
     *
     * @deprecated
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
