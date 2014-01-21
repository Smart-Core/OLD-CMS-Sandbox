<?php

namespace SmartCore\Bundle\CMSBundle\Module;

use Symfony\Component\HttpKernel\Bundle\Bundle as BaseBundle;

class Bundle extends BaseBundle
{
    /**
     * @return string
     */
    /*public function __toString()
    {
        return get_class($this);
    }
    */

    /**
     * Действие при создании ноды.
     */
    public function createNode($node)
    {
    }

    /**
     * Получить короткое имя (без суффикса Module).
     * Сейчас используется только в админке для получения списка
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
     */
    final public function hasAdmin()
    {
        return $this->container->has('cms.router_module.' . $this->getShortName() . '.admin') ? true : false;
    }
}
