<?php

namespace SmartCore\Module\Unicat\Form\Type;

use SmartCore\Module\Unicat\Entity\UnicatConfiguration;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class AttributeFormType extends AbstractType
{
    /**
     * @var UnicatConfiguration
     */
    protected $configuration;

    /**
     * @param UnicatConfiguration $configuration
     */
    public function __construct(UnicatConfiguration $configuration)
    {
        $this->configuration = $configuration;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, ['attr' => ['autofocus' => 'autofocus']])
            ->add('name')
            ->add('type', 'choice', [
                'choices' => [
                    'text'        => 'Text',
                    'textarea'    => 'Textarea',
                    'integer'     => 'Integer',
                    'email'       => 'Email',
                    'url'         => 'URL',
                    'date'        => 'Date',
                    'datetime'    => 'Datetime',
                    'checkbox'    => 'Сheckbox',
                    'image'       => 'Image',
                    'select'      => 'Select',
                    'multiselect' => 'Multiselect',
                ],
            ])
            ->add('params_yaml',   null, ['attr' => ['data-editor' => 'yaml']])
            ->add('position')
            ->add('is_dedicated_table', null, ['required' => false])
            ->add('is_enabled',    null, ['required' => false])
            ->add('is_required',   null, ['required' => false])
            ->add('show_in_admin', null, ['required' => false])
            ->add('show_in_list',  null, ['required' => false])
            ->add('show_in_view',  null, ['required' => false])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => $this->configuration->getAttributeClass(),
        ]);
    }

    public function getName()
    {
        return 'smart_unicat_configuration_attribute';
    }
}
