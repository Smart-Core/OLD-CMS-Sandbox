<?php

namespace Smart\CoreBundle\Command;

use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class TwigClearCacheCommand extends ContainerAwareCommand
{
    protected function configure()
    {
        $this
            ->setName('cache:twig:clear')
            ->setDescription('Clears the twig template cache files on the filesystem.')
            ->setAliases(['twig:clear:cache'])
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $twig = $this->getContainer()->get('twig');

        $output->write('- <comment>Clearing twig template cache files... </comment> ');

        $twig->clearCacheFiles();

        $output->writeln('<info>OK</info>');
    }
}
