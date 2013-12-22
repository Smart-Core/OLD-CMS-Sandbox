<?php

namespace SmartCore\Module\News;

use SmartCore\Bundle\CMSBundle\Module\Bundle;

class NewsModule extends Bundle
{
    public function hasAdmin()
    {
        return true;
    }
}
