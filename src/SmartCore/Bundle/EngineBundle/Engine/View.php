<?php

namespace SmartCore\Bundle\EngineBundle\Engine;

use SmartCore\Bundle\EngineBundle\Container;
use SmartCore\Bundle\EngineBundle\Entity\Node;
use Symfony\Component\HttpKernel\HttpKernelInterface;

class View
{
    /**
     * @var array
     */
    protected $__options = [];

    /**
     * Constructor.
     *
     * @param array $options
     */
    public function __construct(array $options = [])
    {
        $this->__options = [
            'comment'       => null,        // Служебный комментарий
            'engine'        => 'echo',      // Движок отрисовки. ('twig', 'php', 'echo' - простое отображение всех свойств по порядку, 'forward' - выполнить контроллер.)
            'template'      => null,        // Имя файла шаблона.
            'bundle'        => '::',        // Имя бандла или модуля.
            'node'          => null,        // Нода.
            'decorators'    => null,        // Декораторы - отображаются до и после рендеринга.
        ];
        $this->__options = $options + $this->__options;
    }

    /**
     * Магическое чтение свойства.
     *
     * @param string $name
     * @return string|null
     */
    public function __get($name)
    {
        return $this->get($name);
    }

    /**
     * Магическая установка свойства.
     *
     * @param string $name
     * @param mixed $value
     */
    public function __set($name, $value)
    {
        $this->set($name, $value);
    }

    /**
     * Отрисовка вида.
     *
     * @return string
     */
    public function __toString()
    {
        return $this->render();
    }

    /**
     * Отрисовка вида.
     *
     * @return string
     */
    public function render()
    {
        ob_start();
        $this->display();
        return ob_get_clean();
    }

    /**
     * Очистка всех свойств.
     *
     * @return $this
     */
    public function removeProperties()
    {
        foreach ($this as $property => $__dummy) {
            if ($property == '__options') {
                continue;
            }

            unset($this->$property);
        }

        return $this;
    }

    /**
     * Утановить опции.
     *
     * @param array $options
     * @return $this
     */
    public function setOptions(array $options = [])
    {
        $this->__options = $options + $this->__options;

        return $this;
    }

    /**
     * Утановить опцию.
     *
     * @param string $name
     * @param string $value
     * @return $this
     */
    public function setOption($name, $value)
    {
        if (isset($this->__options[$name])) {
            $this->__options[$name] = $value;
        } else {
            throw new \Exception('Опция не доступна'); // @todo перевод и отдельный класс.
        }

        return $this;
    }

    /**
     * Получить данные свойств.
     *
     * @return array
     */
    public function all()
    {
        $properties = [];
        foreach ($this as $property => $data) {
            if ($property === '__options') {
                continue;
            }            
            $properties[$property] = $data;
        }

        return $properties;        
    }
    
    /**
     * Получить имя шаблона, по умолчанию при инициализации модуля устанавливается такое же как
     * имя контроллера, но в результате работы, модуль может установить другой шаблон.
     * 
     * @final
     * @return string
     */
    final public function getTemplateName()
    {
        return $this->__options['template'];
    }

    /**
     * @param string $engine
     * @return $this
     */
    public function setEngine($engine)
    {
        $this->__options['engine'] = $engine;

        return $this;
    }

    /**
     * @param string $before
     * @param string $after
     * @return $this
     */
    public function setDecorators($before, $after)
    {
        $this->__options['decorators'] = [$before, $after];

        return $this;
    }

    /**
     * @return string
     */
    public function getEngine()
    {
        return $this->__options['engine'];
    }

    /**
     * @param string $name
     * @return $this
     */
    public function setTemplateName($name)
    {
        $this->__options['template'] = strtolower($name);

        return $this;
    }

    /**
     * @param Node $node
     * @return $this
     */
    public function setNode(Node $node)
    {
        $this->__options['node'] = $node;

        return $this;
    }

    /**
     * @return Node|null
     */
    public function getNode()
    {
        return $this->__options['node'];
    }

    /**
     * Установка свойства.
     * 
     * @param string $name
     * @param mixed $value
     * @return $this
     */
    public function set($name, $value)
    {
        $this->$name = $value;

        return $this;
    }
    
    /**
     * Получить свойство.
     * 
     * @param string $name
     * @return mixed|null
     */
    public function get($name)
    {
        return isset($this->$name) ? $this->$name : null;
    }
    
    /**
     * Проверить существует ли свойство.
     *
     * @param string $name
     * @return bool
     */
    public function has($name)
    {
        return isset($this->$name) ? true : false;
    }

    /**
     * Выполнить контроллер из шаблона
     * http://symfony.com/doc/current/book/templating.html#embedding-controllers
     *
     * @param string $controller
     * @param array $args
     */
    public function forward($controller, $args = [])
    {
        $this
            ->setOption('engine', 'forward')
            ->set('controller', $controller)
            ->set('args', $args)
        ;
    }

    /**
     * Отображение данных вида.
     *
     * @return void
     */
    public function display()
    {        
        if (!empty($this->__options['decorators'])) {
            echo $this->__options['decorators'][0];
        }

        if (!empty($this->__options['node'])) {
            $this->node = $this->__options['node'];
        }

        switch (strtolower($this->__options['engine'])) {
            case 'twig':
                echo Container::get('templating')->render($this->__options['bundle'] . $this->__options['template'] . '.html.twig' , $this->all());
                break;
            case 'php':
                echo Container::get('templating')->render($this->__options['bundle'] . $this->__options['template'] . '.html.php' , $this->all());
                break;
            case 'forward':
                $query = [];
                $path = $this->get('args');
                $path['_controller'] = $this->get('controller');
                $subRequest = Container::get('request')->duplicate($query, null, $path);
                echo Container::get('http_kernel')->handle($subRequest, HttpKernelInterface::SUB_REQUEST)->getContent();
                break;
            case 'echo':
                foreach ($this as $property => $__dummy) {
                    if ($property == '__options' or $property == 'node') {
                        continue;
                    }

                    echo $this->$property;
                }
                break;
            default;
                echo 'Неопознанный шаблонный движок.';
        }

        if (!empty($this->__options['decorators'])) {
           echo $this->__options['decorators'][1];
        }
    }
}
