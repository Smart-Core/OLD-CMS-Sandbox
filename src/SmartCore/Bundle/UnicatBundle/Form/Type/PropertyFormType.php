<?php

namespace SmartCore\Bundle\UnicatBundle\Form\Type;

use SmartCore\Bundle\UnicatBundle\Entity\UnicatRepository;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class PropertyFormType extends AbstractType
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
            'data_class' => $this->repository->getPropertyClass(),
        ]);
    }

    public function getName()
    {
        return 'smart_unicat_repository_'.$this->repository->getName().'_property';
    }
}
