<?php

namespace SmartCore\Bundle\Unicat2Bundle\Form\Type;

use SmartCore\Bundle\CMSBundle\Container;
use SmartCore\Bundle\SeoBundle\Form\Type\MetaFormType;
use SmartCore\Bundle\Unicat2Bundle\Entity\UnicatRepository;
use SmartCore\Bundle\Unicat2Bundle\Form\Tree\CategoryTreeType;
//use SmartCore\Bundle\Unicat2Bundle\Model\CategoryModel;
use SmartCore\Bundle\Unicat2Bundle\Model\ItemModel;
use SmartCore\Bundle\Unicat2Bundle\Model\PropertyModel;
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
        $builder
            ->add('slug', null, ['attr' => ['class' => 'focused']])
            ->add('is_enabled')
            ->add('position')
            ->add('meta', new MetaFormType(), ['label' => 'Meta tags'])
        ;

        foreach ($this->repository->getStructures() as $structure) {
            $optionsCat = [
                'label'     => $structure->getTitleForm(),
                'required'  => $structure->getIsRequired(),
                'expanded'  => ('multi' === $structure->getEntries()) ? true : false,
                'multiple'  => ('multi' === $structure->getEntries()) ? true : false,
                'class'     => $this->repository->getCategoryClass(),
            ];

            if ('single' === $structure->getEntries() and isset($options['data'])) {
                /** @var ItemModel $category */
                foreach ($options['data']->getCategories() as $category) {
                    if ($category->getStructure()->getName() === $structure->getName()) {
                        $optionsCat['data'] = $category;
                    }
                }
            }

            $categoryTreeType = (new CategoryTreeType(Container::get('doctrine')))->setStructure($structure);
            $builder->add('structure:' . $structure->getName(), $categoryTreeType, $optionsCat);
        }

        /** @var $property PropertyModel */
        foreach (Container::getContainer()->get('unicat2')->getProperties($this->repository) as $property) {
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
                        $_class = '\Symfony\Component\Validator\Constraints\\' . $constraintClass;

                        $constraintsObjects[] = new $_class($constraintParams);
                    }
                }

                $propertyOptions['constraints'] = $constraintsObjects;
            }

            $builder->add('property:' . $property->getName(), $type, $propertyOptions);
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
        return 'smart_unicat2_repository_' . $this->repository->getName() . '_item';
    }
}
