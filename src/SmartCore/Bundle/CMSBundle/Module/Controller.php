<?php

namespace SmartCore\Bundle\CMSBundle\Module;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use SmartCore\Bundle\CMSBundle\Response;
use SmartCore\Bundle\CMSBundle\Entity\Node;
use Symfony\Component\HttpFoundation\Request;

abstract class Controller extends BaseController
{
    /**
     * Свойства ноды.
     * @var Node
     */
    protected $node;
    
    /**
     * Установить параметры ноды.
     *
     * @todo сделать проверку на доступные параметры в классе и выдавать предупреждение.
     */
    final public function setNode(Node $node)
    {
        $this->node = $node;
        foreach ($node->getParams() as $key => $value) {
            $this->$key = $value;
        }
    }

    /**
     * @return \RickySu\Tagcache\Adapter\TagcacheAdapter
     */
    protected function getCacheService()
    {
        return $this->get('tagcache');
    }

    /**
     * Обработчик POST данных.
     *
     * @return Response
     *
     * @todo описать нормальный Exception класс.
     */
    public function postAction(Request $request)
    {
        throw new \Exception('Method ' . get_class($this) . '::postAction is not exist');
    }
}
