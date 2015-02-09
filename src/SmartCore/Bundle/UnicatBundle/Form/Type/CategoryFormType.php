<?php

namespace SmartCore\Bundle\UnicatBundle\Form\Type;

use Doctrine\Common\Persistence\ManagerRegistry;
use SmartCore\Bundle\SeoBundle\Form\Type\MetaFormType;
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

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var CategoryModel $category */
        $category = $options['data'];

        $categoryTreeType = (new CategoryTreeType($this->doctrine))->setStructure($category->getStructure());

        $builder
            ->add('is_enabled',     null, ['required' => false])
            ->add('title',          null, ['attr' => ['class' => 'focused']])
            ->add('slug')
            ->add('is_inheritance', null, ['required' => false])
            ->add('position')
            ->add('parent', $categoryTreeType)
            ->add('meta', new MetaFormType(), ['label' => 'Meta tags'])
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
        return 'smart_unicat_repository_'.$this->repository->getName().'_category';
    }
}
