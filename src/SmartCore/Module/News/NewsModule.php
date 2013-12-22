<?php

namespace SmartCore\Module\News;

use SmartCore\Bundle\EngineBundle\Module\Bundle;

class NewsModule extends Bundle
{
    public function hasAdmin()
    {
        return true;
    }
}
