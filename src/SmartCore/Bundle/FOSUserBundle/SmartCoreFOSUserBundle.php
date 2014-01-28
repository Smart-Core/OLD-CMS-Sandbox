<?php

namespace SmartCore\Bundle\FOSUserBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SmartCoreFOSUserBundle extends Bundle
{
    public function getParent()
    {
        return 'FOSUserBundle';
    }
}
