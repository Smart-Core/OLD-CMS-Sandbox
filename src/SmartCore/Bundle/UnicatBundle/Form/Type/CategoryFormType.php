<?php

namespace SmartCore\Bundle\UnicatBundle\Form\Type;

use SmartCore\Bundle\CMSBundle\Container;
use SmartCore\Bundle\UnicatBundle\Entity\UnicatRepository;
use SmartCore\Bundle\UnicatBundle\Entity\UnicatStructure;
use SmartCore\Bundle\UnicatBundle\Form\Tree\CategoryTreeType;
use SmartCore\Bundle\UnicatBundle\Model\CategoryModel;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Yaml\Yaml;

class CategoryFormType extends AbstractType
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

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var CategoryModel $category */
        $category = $options['data'];

        $categoryTreeType = (new CategoryTreeType(Container::get('doctrine')))->setStructure($category->getStructure());

        $builder
            ->add('title', null, ['attr' => ['class' => 'focused']])
            ->add('slug')
            ->add('is_inheritance', null, ['required' => false])
            ->add('parent', $categoryTreeType)
        ;

        $structure = null;

        if (is_object($category) and $category->getStructure() instanceof UnicatStructure) {
            $structure = $category->getStructure();
        }

        if ($structure) {
            $properties = Yaml::parse($structure->getProperties());

            if (is_array($properties)) {
                $builder->add($builder->create(
                    'properties',
                    new CategoryPropertiesFormType($properties),
                    ['required' => false]
                ));
            }
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => $this->repository->getCategoryClass(),
        ]);
    }

    public function getName()
    {
        return 'smart_unicat_repository_' . $this->repository->getName() . '_category';
    }
}
