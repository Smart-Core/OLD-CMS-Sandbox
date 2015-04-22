<?php

namespace SmartCore\Module\Texter\Controller;

use SmartCore\Bundle\CMSBundle\Module\NodeTrait;

class Controller extends \Symfony\Bundle\FrameworkBundle\Controller\Controller
{
    use NodeTrait;

    /**
     * Для каждого экземпляра ноды хранится ИД текстовой записи.
     *
     * @var int
     */
    protected $text_item_id;

    /**
     * Какой редактор использовать.
     * Пока используется как флаг, где:
     *  0 - Codemirror
     *  1 - TinyMCE.
     *
     * @var int
     */
    protected $editor = 1;
}
