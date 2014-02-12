<?php

namespace SmartCore\Bundle\UnicatBundle\Service;

use Doctrine\ORM\EntityManager;
use SmartCore\Bundle\UnicatBundle\Entity\UnicatRepository;
use SmartCore\Bundle\UnicatBundle\Entity\UnicatStructure;
use SmartCore\Bundle\UnicatBundle\Form\Type\CategoryFormType;
use SmartCore\Bundle\UnicatBundle\Model\CategoryModel;
use Symfony\Component\Form\FormFactoryInterface;

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
     * @param EntityManager $em
     * @param FormFactoryInterface $formFactory
     */
    public function __construct(EntityManager $em, FormFactoryInterface $formFactory)
    {
        $this->em = $em;
        $this->formFactory = $formFactory;
    }

    /**
     * @param UnicatRepository $repository
     * @param mixed $data    The initial data for the form
     * @param array $options Options for the form
     *
     * @return \Symfony\Component\Form\Form
     */
    public function getCategoryForm(UnicatRepository $repository, $data = null, array $options = [])
    {
        return $this->formFactory->create(new CategoryFormType($repository), $data, $options);
    }

    /**
     * @param UnicatRepository $repository
     * @param array $options Options for the form
     *
     * @return \Symfony\Component\Form\Form
     */
    public function getCategoryCreateForm(UnicatStructure $structure, array $options = [])
    {
        $category = $structure->getRepository()->createCategory();
        $category->setStructure($structure);

        return $this->getCategoryForm($structure->getRepository(), $category, $options)
            ->add('create', 'submit', ['attr' => [ 'class' => 'btn btn-success' ]]);
    }

    /**
     * @param UnicatRepository $repository
     * @param array $options Options for the form
     *
     * @return \Symfony\Component\Form\Form
     */
    public function getCategoryEditForm(CategoryModel $category, array $options = [])
    {
        return $this->getCategoryForm($category->getStructure()->getRepository(), $category, $options)
            ->add('update', 'submit', ['attr' => [ 'class' => 'btn btn-success' ]])
            ->add('cancel', 'submit', ['attr' => [ 'class' => 'btn' ]])
        ;
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
}
