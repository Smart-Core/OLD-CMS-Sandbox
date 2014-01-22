<?php

namespace SmartCore\Module\Breadcrumbs\Controller;

use SmartCore\Bundle\CMSBundle\Module\NodeTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class BreadcrumbsController extends Controller
{
    use NodeTrait;

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
     * Запуск модуля.
     */
    public function indexAction()
    {
        return $this->render('BreadcrumbsModule::breadcrumbs.html.php', [
            'delimiter' => $this->delimiter,
            'items'     => $this->get('cms.breadcrumbs'),
            'hide_if_only_home' => $this->hide_if_only_home,
        ]);
    }
}
