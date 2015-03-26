<?php

namespace SmartCore\Module\WebForm;

use SmartCore\Bundle\CMSBundle\Module\ModuleBundle;

class WebFormModule extends ModuleBundle
{
    public function getRequiredParams()
    {
        return [
            'webform_id'
        ];
    }
}
