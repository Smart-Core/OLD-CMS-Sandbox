<?php

namespace SmartCore\Bundle\CMSBundle\Module;

use SmartCore\Bundle\CMSBundle\Controller\Controller as BaseController;
use SmartCore\Bundle\CMSBundle\Container;
use SmartCore\Bundle\CMSBundle\Response;
use Symfony\Component\HttpFoundation\Request;
use SmartCore\Bundle\CMSBundle\Entity\Node;

abstract class Controller extends BaseController
{
    /**
     * Edit-In-Place
     * @var bool
     */
    private $_eip = false;

    /**
     * Действие по умолчанию.
     *
     * @var string|false
     */
    protected $default_action = false;
    
    /**
     * Фронтальные элементы управления для всего модуля.
     *
     * @var array|false
     */
    protected $frontend_controls = false;
    
    /**
     * Фронтальные элементы управления для внутренних элементов модуля.
     *
     * @var array|false
     */
    protected $frontend_inner_controls = false;
    
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

        $this->container = Container::getContainer();

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

        $this->View->setOptions([
            'bundle' => $node->getModule() . 'Module' . '::',
            'node'   => $node,
        ]);
    }

    /**
     * Получение данных из кеша.
     *
     * @return string
     */
    protected function getCache($key, $default = null)
    {
        if (!$this->node->getIsCached()) {
            return $default;
        }

        $dir = $this->get('kernel')->getCacheDir() . '/smart_core_cms/node/' . $this->node->getId() . '/';
        if (file_exists($dir . $key)) {
            return unserialize(file_get_contents($dir . $key));
        } else {
            return $default;
        }
    }

    /**
     * Поместить данные в кеш.
     *
     * @return string
     */
    protected function setCache($key, $value)
    {
        if (!$this->node->getIsCached()) {
            return false;
        }

        $dir = $this->get('kernel')->getCacheDir() . '/smart_core_cms/node/' . $this->node->getId() . '/';

        if (!is_dir($dir)) {
            if (false === @mkdir($dir, 0777, true)) {
                throw new \RuntimeException(sprintf('Unable to create the %s directory', $dir));
            }
        } elseif (!is_writable($dir)) {
            throw new \RuntimeException(sprintf('Unable to write in the %s directory', $dir));
        }

        /** @see \Symfony\Component\Config\ConfigCache */
        file_put_contents($dir . $key, serialize($value));

        return true;
    }

    /**
     * Обработчик POST данных.
     *
     * @return Response
     */
    public function postAction(Request $request)
    {
        return new Response('Method postAction is not exist', 404);
    }

    // @todo пересмотреть нижеописанные методы!
    // -------------------------------------------------------------------------------------
    /**
     * Метод-заглушка, для модулей, которые не имеют фронт администрирования. 
     * Возвращает пустой массив или null или 0, следовательно движок ничего не отображает.
     * 
     * @access public
     * @returns array|false
     *
    public function __getFrontControls()
    {
        return $this->frontend_controls;
    }
    
    /**
     * Внутренние элменты управления ноды.
     * 
     * @access public
     * @returns array|false
     *
    public function __getFrontControlsInner()
    {
        return $this->frontend_inner_controls;
    }
    
    /**
     * Действие по умолчанию.
     * 
     * @access public
     * @returns string|false
     *
    public function __getFrontControlsDefaultAction()
    {
        return $this->default_action;
    }
    */
}
