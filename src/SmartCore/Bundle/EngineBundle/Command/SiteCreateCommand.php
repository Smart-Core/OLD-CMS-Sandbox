<?php

namespace SmartCore\Bundle\EngineBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
//use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;

class SiteCreateCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('engine:site:create')
            ->setDescription('@todo| Create new site instance.')
            ->addArgument('domain', InputArgument::OPTIONAL, 'Default domain')
            ->addOption('descr', null, InputOption::VALUE_NONE, 'Description')
//            ->addOption('yell', null, InputOption::VALUE_NONE, 'If set, the task will yell in uppercase letters')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $domain = $input->getArgument('domain');

        if ($domain) {
            $text = 'Creating http://' . $domain;
        } else {
            $text = 'Hello';
        }

        /*
        if ($input->getOption('yell')) {
            $text = strtoupper($text);
        }
        */
        $output->writeln($text);
    }
}