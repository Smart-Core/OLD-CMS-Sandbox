<?php

namespace SmartCore\Bundle\CMSBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
//use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class FolderCreateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('cms:folder:create')
            ->setDescription('@todo| Create new folder.')
            ->addArgument('uri_part', InputArgument::OPTIONAL, 'URI part')
            ->addArgument('name', InputArgument::OPTIONAL, 'Name')
            ->addOption('descr', null, InputOption::VALUE_NONE, 'Description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
    }
}
