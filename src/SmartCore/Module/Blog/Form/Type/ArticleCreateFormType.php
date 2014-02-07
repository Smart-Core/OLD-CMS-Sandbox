<?php

namespace SmartCore\Module\Blog\Form\Type;

use Symfony\Component\Form\FormBuilderInterface;

class ArticleCreateFormType extends ArticleFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        parent::buildForm($builder, $options);

        $builder->add('create', 'submit', [
            'attr' => [
                'class' => 'btn btn-primary',
            ],
        ]);
    }

    public function getName()
    {
        return 'smart_blog_article_create';
    }
}
