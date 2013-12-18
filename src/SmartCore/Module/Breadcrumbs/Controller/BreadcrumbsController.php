<?php
/**
 * Хлебные крошки (Дублирующая навигация).
 */
namespace SmartCore\Module\Breadcrumbs\Controller;

use SmartCore\Bundle\EngineBundle\Module\Controller;
use SmartCore\Bundle\EngineBundle\Response;
use SmartCore\Module\Breadcrumbs\Breadcrumbs;
 
class BreadcrumbsController extends Controller
{
    /**
     * Разделитель.
     * @var string
     */
    protected $delimiter = '&raquo;';

    /**
     * Скрыть "хлебные крошки", если выбрана корневая папка.
     * @var bool
     */
    protected $hide_if_only_home = false;

    /**
     * Конструктор.
     */
    protected function init()
    {
        $this->View->setEngine('php');
    }

    /**
     * Запуск модуля.
     */
    public function indexAction()
    {
        $router_data = $this->get('engine.folder')->getRouterData();

        $breadcrumbs = new Breadcrumbs();

        // Формирование "Хлебных крошек".
        /** @var $folder \SmartCore\Bundle\EngineBundle\Entity\Folder */
        foreach ($router_data['folders'] as $folder) {
            $breadcrumbs->add($folder->getUri(), $folder->getTitle(), $folder->getDescr());
        }
        if ($router_data['node_route']['response']) {
            foreach ($router_data['node_route']['response']->getBreadcrumbs() as $bc) {
                $breadcrumbs->add($bc['uri'], $bc['title'], $bc['descr']);
            }
        }

        $this->View->delimiter = $this->delimiter;
        $this->View->items = $breadcrumbs;
        $this->View->hide_if_only_home = $this->hide_if_only_home;

        return new Response($this->View);
    }
}
