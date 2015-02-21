<?php

namespace SmartCore\Module\Feedback\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FeedbackFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title', null, ['attr' => ['autofocus' => 'autofocus']])
            ->add('email')
            ->add('text')
            ->add('captcha', 'genemu_captcha', ['mapped' => false])
            ->add('send', 'submit', ['label' => 'Send'])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'SmartCore\Module\Feedback\Entity\Feedback',
        ]);
    }

    public function getName()
    {
        return 'smart_module_feedback';
    }
}
