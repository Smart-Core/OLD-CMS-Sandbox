<?php

namespace SmartCore\Module\Gallery\Form\Type;

use SmartCore\Bundle\CMSBundle\Container;
use SmartCore\Bundle\CMSBundle\Module\AbstractNodePropertiesFormType;
use Symfony\Component\Form\FormBuilderInterface;

class NodePropertiesFormType extends AbstractNodePropertiesFormType
{
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $galleries = [];
        foreach ($this->em->getRepository('GalleryModule:Gallery')->findAll() as $gallery) {
            $galleries[$gallery->getId()] = $gallery;
        }

        $builder
            ->add('gallery_id', 'choice',   ['choices' => $galleries])
        ;
    }

    public function getName()
    {
        return 'smart_module_gallery_node_properties';
    }
}
