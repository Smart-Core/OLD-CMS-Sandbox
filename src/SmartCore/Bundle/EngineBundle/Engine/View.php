<?php

namespace SmartCore\Bundle\EngineBundle\Engine;

use SmartCore\Bundle\EngineBundle\Container;

class View
{
    /**
     * Опции.
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
            'engine'        => 'echo',      // Движок отрисовки. ('twig', 'php', 'echo' - простое отображение всех свойств.)
            'template'      => null,        // Имя файла шаблона.
            'bundle'        => '::',        // Имя бандла или модуля.
            'decorators'    => null,        // Декораторы - отображаются до и после рендеринга.
        ];
        $this->__options = $options + $this->__options;
    }
    
    /**
     * Утановить опции.
     *
     * @param array $options
     */
    public function setOptions(array $options = [])
    {
        $this->__options = $options + $this->__options;
    }

    /**
     * Получить данные свойств.
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
    
    public function setEngine($method)
    {
        $this->__options['engine'] = $method;
    }
    
    public function setDecorators($before, $after)
    {
        $this->__options['decorators'] = [$before, $after];
    }
    
    public function setTemplateName($name)
    {
        $this->__options['template'] = strtolower($name);
    }
    
    /**
     * Установка свойства.
     * 
     * @param string $name
     * @param string $value
     */
    public function set($name, $value)
    {
        $this->$name = $value;
    }
    
    /**
     * Магическая установка свойства.
     * 
     * @param string $name
     * @param string $value
     */
    public function __set($name, $value)
    {
        $this->set($name, $value);
    }
    
    /**
     * Получить свойство.
     * 
     * @param string $name
     * @return bool
     */
    public function get($name)
    {
        return isset($this->$name) ? $this->$name : null;
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
     * Проверить существует ли свойство.
     *
     * @param $name
     * @return bool
     */
    public function has($name)
    {
        return isset($this->$name) ? true : false;
    }
    
    /**
     * Отрисовка формы.
     * @return text
     */
    public function __toString()
    {
        ob_start();
        $this->display();
        return ob_get_clean();
    }

    /**
     * Отображение данных вида.
     */
    public function display()
    {        
        if (!empty($this->__options['decorators'])) {
            echo $this->__options['decorators'][0];
        }

        switch (strtolower($this->__options['engine'])) {
            case 'twig':
                echo Container::get('templating')->render($this->__options['bundle'] . $this->__options['template'] . '.html.twig' , $this->all());
                break;
            case 'php':
                echo Container::get('templating')->render($this->__options['bundle'] . $this->__options['template'] . '.html.php' , $this->all());
                break;
            case 'echo':
                foreach ($this as $property => $__dummy) {
                    if ($property == '__options') {
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
