<?php

namespace SmartCore\Module\Unicat\Form\Type;

use Doctrine\ORM\EntityRepository;
use SmartCore\Module\Unicat\Entity\UnicatConfiguration;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class ConfigurationSettingsFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        /** @var UnicatConfiguration $configuration */
        $configuration = $options['data'];

        $builder
            ->add('title',          null, ['attr'  => ['autofocus' => 'autofocus']])
            ->add('is_inheritance', null, ['required' => false])
            ->add('media_collection')
            ->add('default_structure', 'entity', [
                'class' => 'UnicatModule:UnicatStructure',
                'query_builder' => function (EntityRepository $er) use ($configuration) {
                    return $er->createQueryBuilder('s')
                        ->where('s.configuration = :configuration')
                        ->setParameter('configuration', $configuration)
                    ;
                },
                'required' => false,
            ])

            ->add('update', 'submit', ['attr' => ['class' => 'btn btn-primary']])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'SmartCore\Module\Unicat\Entity\UnicatConfiguration',
        ]);
    }

    public function getName()
    {
        return 'unicat_configuration';
    }
}
