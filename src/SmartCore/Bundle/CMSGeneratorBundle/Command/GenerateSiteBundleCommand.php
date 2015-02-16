<?php

namespace SmartCore\Bundle\CMSGeneratorBundle\Command;

use SmartCore\Bundle\CMSGeneratorBundle\Generator\SiteBundleGenerator;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\Question;

class GenerateSiteBundleCommand extends GeneratorCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setDefinition([
                new InputOption('name', '', InputOption::VALUE_REQUIRED, 'Site name.'),
            ])
            ->setDescription('Generate SiteBundle for Smart Core CMS')
            ->setName('cms:generate:sitebundle')
        ;
    }

    /**
     * @see Command
     *
     * @throws \InvalidArgumentException When namespace doesn't end with Bundle
     * @throws \RuntimeException         When bundle can't be executed
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $questionHelper = $this->getQuestionHelper();

        $name = 'My';
        $question = new Question($questionHelper->getQuestion('Site name.', $name), $name);

        $name = $questionHelper->ask($input, $output, $question);

        $dir = dirname($this->getContainer()->getParameter('kernel.root_dir')).'/src';
        $format = 'yml';
        $structure = 'no';

        if (!$this->getContainer()->get('filesystem')->isAbsolutePath($dir)) {
            $dir = getcwd().'/'.$dir;
        }

        $bundle = 'SiteBundle';
        $namespace = $name.$bundle;

        $generator = $this->getGenerator();
        $generator->generate($namespace, $bundle, $dir, $format, $structure);

        $output->writeln('Generating the bundle code: <info>OK</info>');

        $errors = array();
        $runner = $questionHelper->getRunner($output, $errors);

        // check that the namespace is already autoloaded
        $runner($this->checkAutoloader($output, $namespace, $bundle, $dir));

        $questionHelper->writeGeneratorSummary($output, $errors);
    }

    protected function checkAutoloader(OutputInterface $output, $namespace, $bundle, $dir)
    {
        $output->write('Checking that the bundle is autoloaded: ');
        if (!class_exists($namespace.'\\'.$bundle)) {
            return array(
                '- Edit the <comment>composer.json</comment> file and register the bundle',
                '  namespace in the "autoload" section:',
                '',
            );
        }
    }

    protected function createGenerator()
    {
        return new SiteBundleGenerator($this->getContainer()->get('filesystem'));
    }
}
