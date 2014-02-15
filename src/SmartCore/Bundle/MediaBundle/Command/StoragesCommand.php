<?php

namespace SmartCore\Bundle\MediaBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class StoragesCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('smart:media:storages')
            ->setDescription('@todo Storages list.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {

    }
}
