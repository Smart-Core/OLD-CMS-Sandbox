<?php

namespace SmartCore\Bundle\SimpleProfilerBundle;

use Symfony\Component\HttpKernel\Bundle\Bundle;

class SmartSimpleProfilerBundle extends Bundle
{
    public function boot()
    {
        \Profiler::setKernel($this->container->get('kernel'));

        if ($this->container->get('kernel')->getEnvironment() == 'prod' and $this->container->has('smart_simple_profiler.db.logger')) {
            $this
                ->container
                ->get('database_connection')
                ->getConfiguration()
                ->setSQLLogger($this->container->get('smart_simple_profiler.db.logger'))
            ;
        }
    }
}
