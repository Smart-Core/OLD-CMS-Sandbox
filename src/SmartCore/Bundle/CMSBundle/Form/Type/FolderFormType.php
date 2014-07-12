<?php

namespace SmartCore\Bundle\CMSBundle\Form\Type;

//use SmartCore\Bundle\CMSBundle\Form\EventListener\FolderSubscriber;
use SmartCore\Bundle\CMSBundle\Container;
use SmartCore\Bundle\SeoBundle\Form\Type\MetaFormType;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FolderFormType extends AbstractType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $finder = new Finder();
        $finder->files()->sortByName()->name('*.html.twig')->in(Container::getParameter('kernel.root_dir') . '/Resources/views');

        $templates = ['' => ''];
        /** @var \Symfony\Component\Finder\SplFileInfo $file */
        foreach ($finder as $file) {
            $name = str_replace('.html.twig', '', $file->getFilename());
            $templates[$name] = $name;
        }

        $builder
            ->add('title', null, ['attr' => ['class' => 'focused']])
            ->add('uri_part')
            ->add('descr')
            ->add('parent_folder', 'cms_folder_tree')
            ->add('router_node_id')
            ->add('position')
            ->add('is_active')
            ->add('is_file')
            ->add('has_inherit_nodes')
            ->add('template_inheritable', 'choice', [
                'choices'  => $templates,
                'required' => false,
            ])
            ->add('template_self', 'choice', [
                'choices'  => $templates,
                'required' => false,
            ])
            ->add('meta', new MetaFormType(), ['label' => 'Meta tags'])
            //->add('permissions', 'text')
            //->add('lockout_nodes', 'text')
            //->addEventSubscriber(new FolderSubscriber())
        ;
    }

    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults([
            'data_class' => 'SmartCore\Bundle\CMSBundle\Entity\Folder',
        ]);
    }

    public function getName()
    {
        return 'smart_core_folder';
    }
}
