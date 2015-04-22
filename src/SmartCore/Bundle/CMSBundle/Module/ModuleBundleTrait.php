<?php

namespace SmartCore\Bundle\CMSBundle\Module;

use SmartCore\Bundle\CMSBundle\Entity\Node;

trait ModuleBundleTrait
{
    /**
     * Действие при создании ноды.
     *
     * @param Node $node
     */
    public function createNode(Node $node)
    {
    }

    /**
     * Действие при удалении ноды.
     *
     * @param Node $node
     */
    public function deleteNode(Node $node)
    {
    }

    /**
     * Действие при обновлении ноды.
     *
     * @param Node $node
     */
    public function updateNode(Node $node)
    {
    }

    /**
     * Получить виджеты для рабочего стола.
     *
     * @return array
     */
    public function getDashboard()
    {
        return [];
    }

    /**
     * Получить оповещения.
     *
     * @return array
     */
    public function getNotifications()
    {
        return [];
    }

    /**
     * @todo Получение списка доступных виджетов у модуля.
     *
     * @return array
     */
    public function getWidgets()
    {
        return [];
    }

    /**
     * Получить короткое имя (без суффикса Module).
     * Сейчас используется только в админке для получения списка.
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
        return $this->container->has('cms.router_module.'.$this->getShortName().'.admin') ? true : false;
    }

    public function getRequiredParams()
    {
        return [];
    }
}
