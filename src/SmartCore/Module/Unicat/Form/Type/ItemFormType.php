<?php

namespace SmartCore\Module\Unicat\Form\Type;

use Doctrine\Common\Persistence\ManagerRegistry;
use SmartCore\Bundle\CMSBundle\Container;
use SmartCore\Bundle\SeoBundle\Form\Type\MetaFormType;
use SmartCore\Module\Unicat\Entity\UnicatConfiguration;
use SmartCore\Module\Unicat\Form\Tree\CategoryTreeType;
use SmartCore\Module\Unicat\Model\CategoryModel;
use SmartCore\Module\Unicat\Model\PropertyModel;
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
     * @var UnicatConfiguration
     */
    protected $configuration;

    /**
     * @param UnicatConfiguration $configuration
     */
    public function __construct(UnicatConfiguration $configuration, ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
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

        foreach ($this->configuration->getStructures() as $structure) {
            $optionsCat = [
                'label'     => $structure->getTitleForm(),
                'required'  => $structure->getIsRequired(),
                'expanded'  => $structure->isMultipleEntries() ? true : false,
                'multiple'  => $structure->isMultipleEntries() ? true : false,
                'class'     => $this->configuration->getCategoryClass(),
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
        foreach (Container::getContainer()->get('unicat')->getProperties($this->configuration) as $property) {
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
            'data_class' => $this->configuration->getItemClass(),
        ]);
    }

    /**
     * @return string
     */
    public function getName()
    {
        return 'smart_unicat_configuration_'.$this->configuration->getName().'_item';
    }
}
