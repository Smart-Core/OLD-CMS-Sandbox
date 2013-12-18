<?php

namespace SmartCore\Bundle\EngineBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
//use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SiteAllCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('engine:site:all')
            ->setDescription('@todo| List all registred sites on platform.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

    }
}