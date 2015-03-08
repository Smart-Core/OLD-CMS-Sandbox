<?php

namespace SmartCore\Module\Unicat\Form\Type;

use Doctrine\Common\Persistence\ManagerRegistry;
use SmartCore\Bundle\SeoBundle\Form\Type\MetaFormType;
use SmartCore\Module\Unicat\Entity\UnicatConfiguration;
use SmartCore\Module\Unicat\Entity\UnicatStructure;
use SmartCore\Module\Unicat\Form\Tree\CategoryTreeType;
use SmartCore\Module\Unicat\Model\CategoryModel;
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
     * @var UnicatConfiguration
     */
    protected $configuration;

    /**
     * @param UnicatConfiguration $configuration
     * @param ManagerRegistry $doctrine
     */
    public function __construct(UnicatConfiguration $configuration, ManagerRegistry $doctrine)
    {
        $this->doctrine = $doctrine;
        $this->configuration = $configuration;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var CategoryModel $category */
        $category = $options['data'];

        $categoryTreeType = (new CategoryTreeType($this->doctrine))->setStructure($category->getStructure());

        $builder
            ->add('is_enabled',     null, ['required' => false])
            ->add('title',          null, ['attr' => ['autofocus' => 'autofocus']])
            ->add('slug')
            ->add('is_inheritance', null, ['required' => false])
            ->add('position')
            ->add('parent', $categoryTreeType)
            ->add('meta', new MetaFormType(), ['label' => 'Meta tags'])
        ;

        if (!$category->getStructure()->isTree()) {
            $builder->remove('parent');
        }

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
            'data_class' => $this->configuration->getCategoryClass(),
        ]);
    }

    public function getName()
    {
        return 'unicat_category_'.$this->configuration->getName();
    }
}
