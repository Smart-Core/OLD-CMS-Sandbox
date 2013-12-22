<?php

namespace SmartCore\Bundle\CMSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use SmartCore\Bundle\CMSBundle\Engine\View;

abstract class Controller extends BaseController
{
    /**
     * View object
     * @var View
     */
    protected $View;

    /**
     * Constructor.
     * 
     * @todo пересмотреть логику... ненравится мне эта инициализация вида...
     */
    public function __construct()
    {
        // По умолчанию устанавливается имя шаблона, как короткое имя контроллера.
        $reflector = new \ReflectionClass(get_class($this));

        if (substr($reflector->getShortName(), -10) == 'Controller') {
            $template = substr($reflector->getShortName(), 0, strlen($reflector->getShortName()) - 10);
        } else {
            $template = $reflector->getShortName();
        }

        $this->View = new View([
            'template' => strtolower($template),
            'engine' => 'twig',
        ]);
    }
}
