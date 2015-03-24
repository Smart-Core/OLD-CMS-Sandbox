<?php

namespace SmartCore\Module\WebForm\Form\Type;

use SmartCore\Module\WebForm\Entity\Message;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class MessageType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('status', 'choice', ['choices' => Message::getFormChoicesStatuses()])
            ->add('comment')
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'SmartCore\Module\WebForm\Entity\Message',
        ]);
    }

    public function getName()
    {
        return 'smart_module_webform_message';
    }
}
