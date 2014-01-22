<?php

namespace SmartCore\Module\Texter\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use SmartCore\Bundle\CMSBundle\Module\NodeTrait;

class Controller extends BaseController
{
    use NodeTrait;

    /**
     * Для каждого экземпляра ноды хранится ИД текстовой записи.
     * @var int
     */
    protected $text_item_id;

    /**
     * Какой редактор использовать.
     * @todo пока используется как флаг, где 0 - не использовать визивиг, а 1 - использовать.
     * @var string
     */
    protected $editor = 0;
}
