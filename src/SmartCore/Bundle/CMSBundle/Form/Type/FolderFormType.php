<?php

namespace SmartCore\Bundle\CMSBundle\Form\Type;

//use SmartCore\Bundle\CMSBundle\Form\EventListener\FolderSubscriber;
use SmartCore\Bundle\SeoBundle\Form\Type\MetaFormType;
use Symfony\Component\DependencyInjection\ContainerAwareTrait;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Finder\Finder;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

class FolderFormType extends AbstractType
{
    use ContainerAwareTrait;

    /**
     * @param ContainerInterface $container
     */
    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $finder = new Finder();
        $finder->files()->sortByName()->name('*.html.twig')->in($this->container->getParameter('kernel.root_dir').'/Resources/views');

        $templates = ['' => ''];
        /** @var \Symfony\Component\Finder\SplFileInfo $file */
        foreach ($finder as $file) {
            $name = str_replace('.html.twig', '', $file->getFilename());
            $templates[$name] = $name;
        }

        $routedNodes = ['' => ''];
        /** @var \SmartCore\Bundle\CMSBundle\Entity\Node $node */
        foreach ($this->container->get('cms.node')->findInFolder($options['data']) as $node) {
            if (!$this->container->has('cms.router_module.'.$node->getModule())) {
                continue;
            }

            $nodeTitle = $node->getId().': '.$node->getModule();

            if ($node->getDescr()) {
                $nodeTitle .= ' ('.$node->getDescr().')';
            }

            $routedNodes[$node->getId()] = $nodeTitle;
        }

        $builder
            ->add('title', null, ['attr' => ['class' => 'focused']])
            ->add('uri_part')
            ->add('descr')
            ->add('parent_folder', 'cms_folder_tree')
            ->add('router_node_id', 'choice', [
                'choices'  => $routedNodes,
                'required' => false,
            ])
            ->add('position')
            ->add('is_active', null, ['required' => false])
            ->add('is_file',   null, ['required' => false])
            ->add('has_inherit_nodes', null, ['required' => false])
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
        return 'smart_core_cms_folder';
    }
}
