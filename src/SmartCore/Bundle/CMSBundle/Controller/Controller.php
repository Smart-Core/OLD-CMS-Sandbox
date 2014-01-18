<?php

namespace SmartCore\Bundle\CMSBundle\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use SmartCore\Bundle\CMSBundle\Engine\View;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

abstract class Controller extends BaseController
{
    /**
     * @var View
     */
    protected $View;

    /**
     * Constructor.
     * 
     * @todo пересмотреть логику... не нравится мне эта инициализация вида...
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
            'engine'   => 'twig',
        ]);
    }

    public function generateModuleAdminUrl($route, $parameters = array(), $referenceType = UrlGeneratorInterface::ABSOLUTE_PATH)
    {
        return $this->container->get('cms.router')->generateModuleAdminUrl($route, $parameters, $referenceType);
    }
}
