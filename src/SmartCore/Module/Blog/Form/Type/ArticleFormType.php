<?php

namespace SmartCore\Module\Blog\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolver;

class ArticleFormType extends AbstractType
{
    protected $class;

    /**
     * @param string $class
     */
    public function __construct($class)
    {
        $this->class = $class;
    }

    /**
     * @param FormBuilderInterface $builder
     * @param array $options
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('title',      null, ['attr' => ['autofocus' => 'autofocus']])
            ->add('slug')
            ->add('annotation', null, ['required' => false])
            ->add('text',       null, ['attr' => ['class' => 'wysiwyg', 'data-theme' => 'advanced']])
            ->add('description')
            ->add('keywords')
        ;

        if (array_key_exists('SmartCore\Module\Blog\Model\CategoryTrait', class_uses($this->class, false))
            or array_key_exists('SmartCore\Module\Blog\Model\CategorizedInterface', class_implements($this->class, false))
        ) {
            // @todo сделать отображение вложенных категорий.
            $builder->add('category');
        }

        if (array_key_exists('SmartCore\Module\Blog\Model\TagTrait', class_uses($this->class, false))
            or array_key_exists('SmartCore\Module\Blog\Model\TaggableInterface', class_implements($this->class, false))
        ) {
            $builder->add('tags', null, [
                'expanded' => true,
                'required' => false,
            ]);
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'data_class' => $this->class,
        ]);
    }

    public function getName()
    {
        return 'smart_blog_article';
    }
}
