<?php

namespace SmartCore\Bundle\CMSBundle\Module;

use Symfony\Component\HttpKernel\Bundle\Bundle as BaseBundle;

class Bundle extends BaseBundle
{
    /**
     * @return string
     */
    public function __toString()
    {
        return get_class($this);
    }

    /**
     * Получить имя контроллера по умолчанию.
     * Вычисляется как посленяя часть пространства имён.
     * 
     * @return string
     *
     * @todo REMOVE
     */
    public function getDefaultController()
    {
        // @todo сделать кеширование в АРС.
        $reflector = new \ReflectionClass(get_class($this));
        $namespace = explode('\\', $reflector->getNamespaceName());

        return $namespace[count($namespace) - 1];
    }

    /**
     * Получить имя экшена по умолчанию.
     * 
     * @return string
     *
     * @todo REMOVE
     */
    public function getDefaultAction()
    {
        return 'index';
    }

    /**
     * Действие при создании ноды.
     */
    public function createNode($node)
    {
    }

    /**
     * Получить короткое имя (без суффикса Module).
     *
     * @return string
     */
    public function getShortName()
    {
        return substr($this->getName(), 0, -6);
    }

    /**
     * Есть ли у модуля административный раздел.
     *
     * @return bool
     *
     * @todo переделать на более красивую логику, например основываясь на наличии контроллера админки.
     */
    public function hasAdmin()
    {
        return false;
    }
}
