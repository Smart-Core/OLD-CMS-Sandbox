<?php

namespace SmartCore\Bundle\SettingsBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SettingListCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('smart:settings:list')
            ->setDescription('@todo.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    }
}
