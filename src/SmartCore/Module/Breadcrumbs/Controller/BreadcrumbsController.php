<?php

namespace SmartCore\Module\Breadcrumbs\Controller;

use SmartCore\Bundle\CMSBundle\Module\Controller;
use SmartCore\Bundle\CMSBundle\Response;

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
        $this->view->setEngine('php');
    }

    /**
     * Запуск модуля.
     */
    public function indexAction()
    {
        $this->view->delimiter = $this->delimiter;
        $this->view->items = $this->get('cms.breadcrumbs');
        $this->view->hide_if_only_home = $this->hide_if_only_home;

        return new Response($this->view);
    }
}
