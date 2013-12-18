<?php

namespace SmartCore\Bundle\EngineBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
//use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SiteSetDefaultCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('engine:site:set-default')
            ->setDescription('@todo| Set default site_id.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

    }
}