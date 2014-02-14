<?php

namespace SmartCore\Module\Menu\Form\Type;

use SmartCore\Module\Menu\Entity\Group;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;
use Symfony\Component\Yaml\Yaml;

class ItemFormType extends AbstractType
{
    /**
     * @var Group
     */
    protected $group;

    /**
     * Constructor.
     */
    public function __construct(Group $group = null)
    {
        $this->group = $group;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('is_active')
            ->add('parent_item', 'entity', [
                'class'     => 'MenuModule:Item',
                'required'  => false,
            ])
            ->add('folder', 'folder_tree', ['required' => false])
            ->add('title',      null, ['attr' => ['class' => 'input-block-level focused']])
            ->add('url',        null, ['attr' => ['class' => 'input-block-level']])
            ->add('descr',      null, ['attr' => ['class' => 'input-block-level']])
            ->add('position',   null, ['attr' => ['class' => 'input-block-level']])
        ;

        if ($options['data']->getGroup() instanceof Group) {
            $this->group = $options['data']->getGroup();
        }

        if ($this->group) {
            $properties = Yaml::parse($this->group->getProperties());

            if (is_array($properties)) {
                $builder->add($builder->create(
                    'properties',
                    new ItemPropertiesFormType($properties),
                    ['required' => false]
                ));
            }
        }
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'SmartCore\Module\Menu\Entity\Item',
        ]);
    }

    public function getName()
    {
        return 'smart_module_menu_item';
    }
}
