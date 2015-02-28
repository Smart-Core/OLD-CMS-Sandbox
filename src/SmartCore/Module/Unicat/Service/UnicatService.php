<?php

namespace SmartCore\Module\Unicat\Service;

use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\EntityManager;
use SmartCore\Bundle\MediaBundle\Service\CollectionService;
use SmartCore\Bundle\MediaBundle\Service\MediaCloudService;
use SmartCore\Module\Unicat\Entity\UnicatConfiguration;
use SmartCore\Module\Unicat\Entity\UnicatStructure;
use SmartCore\Module\Unicat\Model\AttributeModel;
use SmartCore\Module\Unicat\Model\CategoryModel;
use SmartCore\Module\Unicat\Model\ItemModel;
use Symfony\Component\Form\FormFactoryInterface;
use Symfony\Component\HttpFoundation\File\UploadedFile;
use Symfony\Component\Security\Core\SecurityContextInterface;

class UnicatService
{
    /**
     * @var ManagerRegistry
     */
    protected $doctrine;

    /**
     * @var EntityManager
     */
    protected $em;

    /**
     * @var FormFactoryInterface
     */
    protected $formFactory;

    /**
     * @var CollectionService
     */
    protected $mc;

    /**
     * @var SecurityContextInterface
     */
    protected $securityContext;

    /**
     * @var UnicatConfigurationManager[]
     */
    protected $ucm;

    /** @var UnicatConfiguration|null */
    protected $currentConfiguration;

    /**
     * @param EntityManager $em
     * @param FormFactoryInterface $formFactory
     * @param MediaCloudService $mediaCloud
     * @param SecurityContextInterface $securityContext
     */
    public function __construct(
        ManagerRegistry $doctrine,
        FormFactoryInterface $formFactory,
        MediaCloudService $mediaCloud,
        SecurityContextInterface $securityContext
    ) {
        $this->currentConfiguration = null;
        $this->doctrine    = $doctrine;
        $this->em          = $doctrine->getManager();
        $this->formFactory = $formFactory;
        $this->mc          = $mediaCloud->getCollection(1); // @todo настройку медиаколлекции. @important
        $this->securityContext = $securityContext;
    }

    /**
     * @param UnicatConfiguration $currentConfiguration
     * @return $this
     */
    public function setCurrentConfiguration(UnicatConfiguration $currentConfiguration)
    {
        $this->currentConfiguration = $currentConfiguration;

        return $this;
    }

    /**
     * @return UnicatConfiguration|null
     */
    public function getCurrentConfiguration()
    {
        return $this->currentConfiguration;
    }

    /**
     * @param string|int $configuration_id
     * @return UnicatConfigurationManager|null
     */
    public function getConfigurationManager($configuration_id)
    {
        if (empty($configuration_id)) {
            return null;
        }

        $configuration = $this->getConfiguration($configuration_id);

        $this->setCurrentConfiguration($configuration);

        if (!isset($this->ucm[$configuration->getId()])) {
            $this->ucm[$configuration->getId()] = new UnicatConfigurationManager($this->doctrine, $this->formFactory, $configuration, $this->mc, $this->securityContext);
        }

        return $this->ucm[$configuration->getId()];
    }

    /**
     * @param UnicatConfiguration|int $configuration
     * @return AttributeModel[]
     */
    public function getAttributes($configuration)
    {
        if ($configuration instanceof UnicatConfiguration) {
            $configuration = $configuration->getId();
        }

        return $this->getConfigurationManager($configuration)->getAttributes();
    }


    /**
     * @param UnicatConfiguration $configuration
     * @param int $id
     *
     * @return ItemModel|null
     *
     * @deprecated
     */
    public function getItem(UnicatConfiguration $configuration, $id)
    {
        return $this->em->getRepository($configuration->getItemClass())->find($id);
    }

    /**
     * @param UnicatConfiguration $configuration
     * @param array|null $orderBy
     *
     * @return ItemModel|null
     *
     * @deprecated
     */
    public function findAllItems(UnicatConfiguration $configuration, $orderBy = null)
    {
        return $this->em->getRepository($configuration->getItemClass())->findBy([], $orderBy);
    }

    /**
     * @param int|string $val
     * @return UnicatConfiguration
     */
    public function getConfiguration($val)
    {
        $key = intval($val) ? 'id' : 'name';

        return $this->em->getRepository('UnicatModule:UnicatConfiguration')->findOneBy([$key => $val]);
    }

    /**
     * @return UnicatConfiguration[]
     */
    public function allConfigurations()
    {
        return $this->em->getRepository('UnicatModule:UnicatConfiguration')->findAll();
    }

    /**
     * @param int $id
     * @return UnicatStructure
     */
    public function getStructure($id)
    {
        return $this->em->getRepository('UnicatModule:UnicatStructure')->find($id);
    }

    /**
     * @param CategoryModel $category
     * @return $this
     */
    public function createCategory(CategoryModel $category)
    {
        $this->em->persist($category);
        $this->em->flush($category);

        return $this;
    }

    /**
     * @param AttributeModel $entity
     * @return $this
     */
    public function createAttribute(AttributeModel $entity)
    {
        $this->em->persist($entity);
        $this->em->flush($entity);

        return $this;
    }

    /**
     * @param CategoryModel $category
     * @return $this
     */
    public function updateCategory(CategoryModel $category)
    {
        $properties = $category->getProperties();

        foreach ($properties as $propertyName => $propertyValue) {
            if ($propertyValue instanceof UploadedFile) {
                $fileId = $this->mc->upload($propertyValue);
                $category->setProperty($propertyName, $fileId);
            }
        }

        $this->em->persist($category);
        $this->em->flush($category);

        return $this;
    }

    /**
     * @param AttributeModel $entity
     * @return $this
     */
    public function updateAttribute(AttributeModel $entity)
    {
        $this->em->persist($entity);
        $this->em->flush($entity);

        return $this;
    }

    /**
     * @param CategoryModel $category
     * @return $this
     */
    public function deleteCategory(CategoryModel $category)
    {
        throw new \Exception('@todo решить что сделать с вложенными категориями, а также с сопряженными записями');

        $this->em->remove($category);
        $this->em->flush($category);

        return $this;
    }

    /**
     * @param AttributeModel $entity
     * @return $this
     */
    public function deleteAttribute(AttributeModel $entity)
    {
        throw new \Exception('@todo надо решить как поступать с данными записей');

        $this->em->remove($entity);
        $this->em->flush($entity);

        return $this;
    }
}
