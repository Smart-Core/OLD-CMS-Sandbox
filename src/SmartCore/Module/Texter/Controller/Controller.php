<?php

namespace SmartCore\Module\Texter\Controller;

use SmartCore\Bundle\CMSBundle\Module\Controller as BaseController;

class Controller extends BaseController
{
    /**
     * Для каждого экземпляра ноды хранится ИД текстовой записи.
     * @var int
     */
    protected $text_item_id;

    /**
     * Какой редактор использовать.
     * !!!note: пока используется как флаг, где 0 - не использовать визивиг, а 1 - использовать.
     * @var string
     */
    protected $editor = 0;
}
