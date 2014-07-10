<?php

namespace SmartCore\Bundle\Unicat2Bundle\Form\Type;

use SmartCore\Bundle\CMSBundle\Container;
use SmartCore\Bundle\SeoBundle\Form\Type\MetaFormType;
use SmartCore\Bundle\Unicat2Bundle\Form\Tree\CategoryTreeType;
use SmartCore\Bundle\Unicat2Bundle\Model\ItemModel;
use Symfony\Component\Form\FormBuilderInterface;

class CategoryFormType extends ItemFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var ItemModel $category */
        $category = $options['data'];

        $categoryTreeType = (new CategoryTreeType(Container::get('doctrine')))->setStructure($category->getStructure());

        $builder
            ->add('title',          null, ['attr' => ['class' => 'focused']])
            //->add('is_enabled',     null, ['required' => false])
            ->add('slug')
            ->add('position')
            ->add('parent')
            //->add('is_inheritance')
            ->add('parent', $categoryTreeType)
            //->add('meta', new MetaFormType(), ['label' => 'Meta tags'])
        ;
    }
}
