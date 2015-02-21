<?php

namespace SmartCore\Bundle\UnicatBundle\Form\Type;

use Doctrine\Common\Persistence\ManagerRegistry;
use SmartCore\Bundle\CMSBundle\Container;
use SmartCore\Bundle\SeoBundle\Form\Type\MetaFormType;
use SmartCore\Bundle\UnicatBundle\Entity\UnicatRepository;
use SmartCore\Bundle\UnicatBundle\Form\Tree\CategoryTreeType;
use SmartCore\Bundle\UnicatBundle\Model\CategoryModel;
use SmartCore\Bundle\UnicatBundle\Model\PropertyModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ItemFormType extends AbstractType
{
    /**
     * @var ManagerRegistry
     */
    protected $doctrine;

    /**
     * @var UnicatRepository
     */
    protected $repository;

    /**
     * @param UnicatRepository $repository
     */
    public function __construct(UnicatRepository $repository, ManagerRegistry $doctrine)
    {
        $this->doctrine   = $doctrine;
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
        $builder
            ->add('slug', null, ['attr' => ['autofocus' => 'autofocus']])
            ->add('is_enabled')
            ->add('position')
            ->add('meta', new MetaFormType(), ['label' => 'Meta tags'])
        ;

        foreach ($this->repository->getStructures() as $structure) {
            $optionsCat = [
                'label'     => $structure->getTitleForm(),
                'required'  => $structure->getIsRequired(),
                'expanded'  => $structure->isMultipleEntries() ? true : false,
                'multiple'  => $structure->isMultipleEntries() ? true : false,
                'class'     => $this->repository->getCategoryClass(),
            ];

            if ('single' === $structure->getEntries() and isset($options['data'])) {
                /** @var CategoryModel $category */
                foreach ($options['data']->getCategories() as $category) {
                    if ($category->getStructure()->getName() === $structure->getName()) {
                        $optionsCat['data'] = $category;
                    }
                }
            }

            $categoryTreeType = (new CategoryTreeType($this->doctrine))->setStructure($structure);
            $builder->add('structure:'.$structure->getName(), $categoryTreeType, $optionsCat);
        }

        /** @var $property PropertyModel */
        foreach (Container::getContainer()->get('unicat')->getProperties($this->repository) as $property) {
            $type = $property->getType();
            $propertyOptions = [
                'required'  => $property->getIsRequired(),
                'label'     => $property->getTitle(),
            ];

            $propertyOptions = array_merge($propertyOptions, $property->getParams());

            if ($property->isType('image')) {
                // @todo сделать виджет загрузки картинок.
                //$type = 'genemu_jqueryimage';
                $type = new PropertyImageFormType();

                if (isset($options['data'])) {
                    $propertyOptions['data'] = $options['data']->getProperty($property->getName());
                }
            }

            if ($property->isType('select')) {
                $type = 'choice';
            }

            if ($property->isType('multiselect')) {
                $type = 'choice';
                $propertyOptions['expanded'] = true;
                //$propertyOptions['multiple'] = true; // @todo FS#407 продумать мультиселект
            }

            if (isset($propertyOptions['constraints'])) {
                $constraintsObjects = [];

                foreach ($propertyOptions['constraints'] as $constraintsList) {
                    foreach ($constraintsList as $constraintClass => $constraintParams) {
                        $_class = '\Symfony\Component\Validator\Constraints\\'.$constraintClass;

                        $constraintsObjects[] = new $_class($constraintParams);
                    }
                }

                $propertyOptions['constraints'] = $constraintsObjects;
            }

            $builder->add('property:'.$property->getName(), $type, $propertyOptions);
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
        return 'smart_unicat_repository_'.$this->repository->getName().'_item';
    }
}
