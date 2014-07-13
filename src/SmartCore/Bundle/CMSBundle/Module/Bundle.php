<?php

namespace SmartCore\Bundle\CMSBundle\Module;

use SmartCore\Bundle\CMSBundle\Entity\Node;

/**
 * Использование Knp\RadBundle для автоматического конфигурирования сервисов,
 * если у модуля существует файл config/services.yml
 */
abstract class Bundle extends \Knp\RadBundle\AppBundle\Bundle
{
    /**
     * Действие при создании ноды.
     * @param Node $node
     */
    public function createNode(Node $node)
    {
    }

    /**
     * Действие при удалении ноды.
     * @param Node $node
     */
    public function deleteNode(Node $node)
    {
    }

    /**
     * Действие при обновлении ноды.
     * @param Node $node
     */
    public function updateNode(Node $node)
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
