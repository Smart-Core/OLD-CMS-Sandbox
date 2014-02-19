<?php

namespace SmartCore\Bundle\UnicatBundle\Form\Type;

use SmartCore\Bundle\CMSBundle\Container;
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
            ->add('is_enabled')
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
                /** @var CategoryModel $category */
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
        foreach (Container::getContainer()->get('unicat')->getProperties($this->repository) as $property) {
            $type = $property->getType();
            $propertyOptions = [
                'required'  => $property->getIsRequired(),
                'label'     => $property->getTitle(),
            ];

            if ($property->isType('image')) {
                // @todo сделать вджет загрузки картинок.
                //$type = 'genemu_jqueryimage';
                $type = new PropertyImageFormType();

                if (isset($options['data'])) {
                    $propertyOptions['data'] = $options['data']->getProperty($property->getName());
                }
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
        return 'smart_unicat_repository_' . $this->repository->getName() . '_item';
    }
}
