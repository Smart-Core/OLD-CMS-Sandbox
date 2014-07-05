<?php

namespace SmartCore\Bundle\CMSBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;

/**
 * @ORM\Entity
 * @ORM\Table(name="engine_settings",
 *      indexes={
 *          @ORM\Index(name="bundle", columns={"bundle"}),
 *          @ORM\Index(name="key_name", columns={"key_name"}),
 *      },
 *      uniqueConstraints={
 *          @ORM\UniqueConstraint(name="bundle_key", columns={"bundle", "key_name"}),
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
     * @return integer
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
     * @param boolean $serialized
     * @return $this
     */
    public function setSerialized($serialized)
    {
        $this->serialized = $serialized;

        return $this;
    }

    /**
     * @return boolean
     */
    public function getSerialized()
    {
        return $this->serialized;
    }
}
