<?php

namespace SmartCore\Module\Blog\Form\Type;

use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;

class ArticleEditFormType extends ArticleFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder->add('image');

        parent::buildForm($builder, $options);

        $builder->add('save', SubmitType::class, [
            'attr' => [
                'class' => 'btn btn-primary',
            ],
        ]);
    }

    public function getBlockPrefix()
    {
        return 'smart_blog_article_edit';
    }
}
