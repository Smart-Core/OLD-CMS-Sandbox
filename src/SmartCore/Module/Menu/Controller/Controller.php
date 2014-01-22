<?php

namespace SmartCore\Module\Menu\Controller;

use SmartCore\Bundle\CMSBundle\Module\CacheTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;
use SmartCore\Bundle\CMSBundle\Module\NodeTrait;

abstract class Controller extends BaseController
{
    use CacheTrait;
    use NodeTrait;

    protected $css_class = null;
    protected $depth = 0;
    protected $group_id = null;
    protected $selected_inheritance = false;
    protected $tpl = null; // @todo
}
