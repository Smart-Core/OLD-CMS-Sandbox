<?php

namespace SmartCore\Bundle\CMSBundle\Form\Type;

use Doctrine\ORM\EntityManager;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class UserFormType extends AbstractType
{
    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @param EntityManager $em
     */
    public function __construct(EntityManager $em)
    {
        $this->em = $em;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $roles = [];
        foreach ($this->em->getRepository('CMSBundle:Role')->findAll() as $role) {
            $roles[$role->getName()] = $role->getName();
        }

        $builder
            ->add('enabled', null,  ['required' => false])
            ->add('username', null, [
                'label' => 'form.username',
                'translation_domain' => 'FOSUserBundle',
                'attr' => ['autofocus' => 'autofocus'],
            ])
            ->add('email', 'email', [
                'label' => 'form.email',
                'translation_domain' => 'FOSUserBundle',
            ])
            ->add('plainPassword', 'repeated', [
                'type'            => 'password',
                'required'        => false,
                'options'         => ['translation_domain' => 'FOSUserBundle'],
                'first_options'   => ['label' => 'form.new_password'],
                'second_options'  => ['label' => 'form.new_password_confirmation'],
                'invalid_message' => 'fos_user.password.mismatch',
            ])->add('roles', 'choice', [
                'required'        => false,
                'expanded'        => true,
                'multiple'        => true,
                'choices'         => $roles,
            ])
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'SmartCore\Bundle\CMSBundle\Entity\User',
        ]);
    }

    public function getName()
    {
        return 'smart_core_cms_user';
    }
}
