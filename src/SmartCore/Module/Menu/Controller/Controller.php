<?php

namespace SmartCore\Module\Menu\Controller;

use SmartCore\Bundle\CMSBundle\Module\Controller as BaseController;

abstract class Controller extends BaseController
{
    protected $css_class = null;
    protected $depth = 0;
    protected $group_id = null;
    protected $selected_inheritance = false;
    protected $tpl = null; // @todo

    protected function init()
    {
        $this->View->setEngine('echo');
    }
}
