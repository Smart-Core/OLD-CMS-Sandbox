<?php

namespace SmartCore\Module\Menu\Controller;

use SmartCore\Bundle\CMSBundle\Module\CacheTrait;
use SmartCore\Bundle\CMSBundle\Module\NodeTrait;
use Symfony\Bundle\FrameworkBundle\Controller\Controller as BaseController;

abstract class Controller extends BaseController
{
    use CacheTrait;
    use NodeTrait;

    protected $css_class     = null;
    protected $current_class = 'active';
    protected $depth         = 0;
    protected $menu_id       = null;
    protected $selected_inheritance = false;
}
