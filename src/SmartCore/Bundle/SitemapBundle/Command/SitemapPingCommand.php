<?php

namespace SmartCore\Bundle\SitemapBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class SitemapPingCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('smart:sitemap:ping')
            ->setDescription('@todo Sitemap ping.')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    }
}
