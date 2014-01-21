<?php

namespace SmartCore\Bundle\CMSBundle\Module;

use SmartCore\Bundle\CMSBundle\Controller\Controller as BaseController;
use SmartCore\Bundle\CMSBundle\Response;
use SmartCore\Bundle\CMSBundle\Entity\Node;
use Symfony\Component\HttpFoundation\Request;

abstract class Controller extends BaseController
{
    /**
     * Edit-In-Place
     * @var bool
     */
    private $_eip = false;


    /**
     * Свойства ноды.
     * @var Node
     */
    protected $node;
    
    /**
     * Базовый конструктор. Модули используют в качестве конструктора метод init();
     * 
     * @param int $node_id
     */
    final public function __construct()
    {
        parent::__construct();

        // Запуск метода init(), который является заменой конструктора для модулей.
        if (method_exists($this, 'init')) {
            $this->init();
        }
    }

    /**
     * @param $eip
     */
    public function setEip($eip)
    {
        $this->_eip = $eip;
    }

    /**
     * @return bool
     */
    public function isEip()
    {
        return $this->_eip;
    }

    /**
     * Проверка, включен ли тулбар.
     *
     * @return bool
     *
     * @todo сделать.
     */
    public function isToolbar()
    {
        return true;
    }

    /**
     * Установить параметры ноды.
     */
    public function setNode(Node $node)
    {
        $this->node = $node;
        foreach ($node->getParams() as $key => $value) {
            $this->$key = $value;
        }

        $this->view->setOptions([
            'bundle' => $node->getModule() . 'Module' . '::',
            'node'   => $node,
        ]);
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
