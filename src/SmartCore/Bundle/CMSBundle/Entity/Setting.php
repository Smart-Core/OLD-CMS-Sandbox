<?php

namespace SmartCore\Bundle\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="engine_settings",
 *      indexes={
 *          @ORM\Index(name="bundle_engine_settings", columns={"bundle"}),
 *          @ORM\Index(name="key_name_engine_settings", columns={"key_name"}),
 *      },
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="bundle_key_engine_settings", columns={"bundle", "key_name"}),
 *      }
 * )
 *
 * @UniqueEntity(fields={"bundle", "key"}, message="В каждом бандле должены быть уникальные ключи")
 */
class Setting
{
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(type="string", length=32, nullable=false)
     * @Assert\NotBlank()
     */
    protected $bundle;

    /**
     * @var string
     *
     * @ORM\Column(type="string", name="key_name", length=64, nullable=false)
     * @Assert\NotBlank()
     */
    protected $key;

    /**
     * @var string
     *
     * @ORM\Column(type="text", nullable=true)
     */
    protected $value;

    /**
     * @var bool
     *
     * @ORM\Column(type="boolean")
     */
    protected $serialized;

    /**
     * Constructor.
     */
    public function __construct()
    {
        $this->serialized = false;
    }

    /**
     * @return string
     */
    public function __toString()
    {
        return $this->value;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @param string $bundle
     * @return $this
     */
    public function setBundle($bundle)
    {
        $this->bundle = $bundle;

        return $this;
    }

    /**
     * @return string
     */
    public function getBundle()
    {
        return $this->bundle;
    }

    /**
     * @param string $key
     * @return $this
     */
    public function setKey($key)
    {
        $this->key = $key;

        return $this;
    }

    /**
     * @return string
     */
    public function getKey()
    {
        return $this->key;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setValue($value)
    {
        if (is_array($value)) {
            $this->serialized = true;
            $this->value = serialize($value);
        } else {
            $this->value = $value;
        }

        return $this;
    }

    /**
     * @return string
     */
    public function getValue()
    {
        return $this->serialized ? unserialize($this->value) : $this->value;
    }

    /**
     * @param bool $serialized
     * @return $this
     */
    public function setSerialized($serialized)
    {
        $this->serialized = $serialized;

        return $this;
    }

    /**
     * @return bool
     */
    public function getSerialized()
    {
        return $this->serialized;
    }
}
