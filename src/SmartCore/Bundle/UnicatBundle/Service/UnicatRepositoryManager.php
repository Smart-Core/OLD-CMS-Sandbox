<?php

namespace SmartCore\Bundle\UnicatBundle\Service;

use Doctrine\ORM\EntityManager;
use SmartCore\Bundle\UnicatBundle\Entity\UnicatRepository;
use SmartCore\Bundle\UnicatBundle\Entity\UnicatStructure;
use SmartCore\Bundle\UnicatBundle\Form\Type\PropertiesGroupFormType;
use SmartCore\Bundle\UnicatBundle\Form\Type\StructureFormType;
use SmartCore\Bundle\UnicatBundle\Model\CategoryModel;
use SmartCore\Bundle\UnicatBundle\Model\ItemModel;
use SmartCore\Bundle\UnicatBundle\Model\PropertiesGroupModel;
use SmartCore\Bundle\UnicatBundle\Model\PropertyModel;
use Symfony\Component\Form\FormFactoryInterface;

class UnicatRepositoryManager
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
     * @var UnicatRepository
     */
    protected $repository;

    /**
     * @param UnicatRepository $repository
     */
    public function __construct(EntityManager $em, FormFactoryInterface $formFactory, UnicatRepository $repository)
    {
        $this->em          = $em;
        $this->formFactory = $formFactory;
        $this->repository  = $repository;
    }

    /**
     * @return UnicatRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @param array|null $orderBy
     * @return ItemModel|null
     */
    public function findAllItems($orderBy = null)
    {
        return $this->em->getRepository($this->repository->getItemClass())->findBy([], $orderBy);
    }

    /**
     * @return \Doctrine\ORM\EntityRepository
     */
    public function getCategoryRepository()
    {
        return $this->em->getRepository($this->repository->getCategoryClass());
    }
    
    /**
     * @return string
     */
    public function getCategoryClass()
    {
        return $this->repository->getCategoryClass();
    }

    /**
     * @param array $options
     * @return $this|\Symfony\Component\Form\Form
     */
    public function getStructureCreateForm(array $options = [])
    {
        $structure = new UnicatStructure();
        $structure->setRepository($this->repository);

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
        $group = $this->repository->createPropertiesGroup();
        $group->setRepository($this->repository);

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
        return $this->formFactory->create(new PropertiesGroupFormType($this->repository), $data, $options);
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
