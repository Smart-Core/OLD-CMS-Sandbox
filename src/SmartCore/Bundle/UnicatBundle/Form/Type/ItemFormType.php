<?php

namespace SmartCore\Bundle\UnicatBundle\Form\Type;

use Doctrine\ORM\EntityRepository;
use SmartCore\Bundle\CMSBundle\Container;
use SmartCore\Bundle\UnicatBundle\Entity\UnicatRepository;
use SmartCore\Bundle\UnicatBundle\Model\CategoryModel;
use SmartCore\Bundle\UnicatBundle\Model\PropertyModel;
use SmartCore\Bundle\UnicatBundle\Service\UnicatService;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ItemFormType extends AbstractType
{
    /**
     * @var UnicatRepository
     */
    protected $repository;

    /**
     * @param UnicatRepository $repository
     */
    public function __construct(UnicatRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @return \SmartCore\Bundle\UnicatBundle\Entity\UnicatRepository
     */
    public function getRepository()
    {
        return $this->repository;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        // @todo Meta-Keywords и Meta-Description

        $builder
            ->add('slug', null, ['attr' => ['class' => 'focused']])
            ->add('is_enabled', null, ['required' => false])
        ;

        /** @var \Doctrine\ORM\EntityManager $em */
        //$em = Container::get('doctrine.orm.entity_manager');

        foreach ($this->repository->getStructures() as $structure) {
            $optionsCat = [
                'label'     => $structure->getTitleForm(),
                'required'  => $structure->getIsRequired(),
                'expanded'  => ('multi' === $structure->getEntries()) ? true : false,
                'multiple'  => ('multi' === $structure->getEntries()) ? true : false,
                'class'     => $this->repository->getCategoryClass(),
                'query_builder' => function(EntityRepository $er) use ($structure) {
                    return $er
                        ->createQueryBuilder('c')
                        ->where('c.structure = :structure')
                        ->setParameter('structure', $structure)
                        ;
                },
            ];

            if ('single' === $structure->getEntries()) {
                /** @var CategoryModel $category */
                foreach ($options['data']->getCategories() as $category) {
                    if ($category->getStructure()->getName() === $structure->getName()) {
                        $optionsCat['data'] = $category;
                    }
                }
            }

            $builder->add('structure:' . $structure->getName(), 'entity', $optionsCat);
        }

        /** @var UnicatService $unicat */
        $unicat = Container::get('unicat');
        $properties = $unicat->getProperties($this->repository);

        /** @var $property PropertyModel */
        foreach ($properties as $property) {
            $builder->add('property:' . $property->getName(), $property->getType(), [
                'required'  => $property->getIsRequired(),
                'label'     => $property->getTitle(),
            ]);
        }
    }

    /**
     * @param OptionsResolverInterface $resolver
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => $this->repository->getItemClass(),
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'smart_unicat_repository_' . $this->repository->getName() . '_item';
    }
}
