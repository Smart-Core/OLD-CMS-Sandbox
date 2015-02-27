<?php

namespace SmartCore\Bundle\CMSGeneratorBundle\Command;

use SmartCore\Bundle\CMSGeneratorBundle\Generator\ModuleGenerator;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Question\ConfirmationQuestion;
use Symfony\Component\Console\Question\Question;

class GenerateModuleCommand extends GeneratorCommand
{
    /**
     * @see Command
     */
    protected function configure()
    {
        $this
            ->setDefinition([
                new InputOption('namespace', '', InputOption::VALUE_REQUIRED, 'The namespace of the module to create'),
                new InputOption('dir', '', InputOption::VALUE_REQUIRED, 'The directory where to create the module'),
                new InputOption('bundle-name', '', InputOption::VALUE_REQUIRED, 'The optional module name'),
            ])
            ->setDescription('Generates a module')
            ->setName('cms:generate:module')
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

        if ($input->isInteractive()) {
            if (!$questionHelper->ask($input, $output, new ConfirmationQuestion($questionHelper->getQuestion('Do you confirm generation', 'yes', '?'), true))) {
                $output->writeln('<error>Command aborted</error>');

                return 1;
            }
        }

        foreach (['namespace', 'dir'] as $option) {
            if (null === $input->getOption($option)) {
                throw new \RuntimeException(sprintf('The "%s" option must be provided.', $option));
            }
        }

        $namespace = Validators::validateBundleNamespace($input->getOption('namespace'));
        if (!$bundle = $input->getOption('bundle-name')) {
            $bundle = strtr($namespace, ['\\' => '']);
        }
        $bundle = Validators::validateBundleName($bundle);
        $dir = Validators::validateTargetDir($input->getOption('dir'), $bundle, $namespace);

        $questionHelper->writeSection($output, 'Module generation');

        if (!$this->getContainer()->get('filesystem')->isAbsolutePath($dir)) {
            $dir = getcwd().'/'.$dir;
        }

        $generator = $this->getGenerator();
        $generator->generate($namespace, $bundle, $dir, 'yml');

        $output->writeln('Generating the module code: <info>OK</info>');

        $errors = [];
        $runner = $questionHelper->getRunner($output, $errors);

        // check that the namespace is already autoloaded
        $runner($this->checkAutoloader($output, $namespace, $bundle, $dir));

        $questionHelper->writeGeneratorSummary($output, $errors);
    }

    protected function interact(InputInterface $input, OutputInterface $output)
    {
        $questionHelper = $this->getQuestionHelper();
        $questionHelper->writeSection($output, 'Welcome to the Smart Core CMS module generator');

        // namespace
        $namespace = null;
        try {
            $namespace = $input->getOption('namespace') ? Validators::validateBundleNamespace($input->getOption('namespace')) : null;
        } catch (\Exception $error) {
            $output->writeln($questionHelper->getHelperSet()->get('formatter')->formatBlock($error->getMessage(), 'error'));
        }

        if (null === $namespace) {
            $output->writeln([
                '',
                'Each module is hosted under a namespace (like <comment>MySite/BlogModule</comment>).',
                'The namespace should begin with a "vendor" name like your company name, your',
                'project name, or your client name, followed by one or more optional category',
                'sub-namespaces, and it should end with the module name itself',
                '(which must have <comment>Module</comment> as a suffix).',
                '',
                'Use <comment>/</comment> instead of <comment>\\ </comment> for the namespace delimiter to avoid any problem.',
                '',
            ]);

            $acceptedNamespace = false;
            while (!$acceptedNamespace) {
                $question = new Question($questionHelper->getQuestion('Bundle namespace', $input->getOption('namespace')), $input->getOption('namespace'));
                $question->setValidator(function ($answer) {
                    return Validators::validateBundleNamespace($answer, false);
                });
                $namespace = $questionHelper->ask($input, $output, $question);

                // mark as accepted, unless they want to try again below
                $acceptedNamespace = true;

                // see if there is a vendor namespace. If not, this could be accidental
                if (false === strpos($namespace, '\\')) {
                    // language is (almost) duplicated in Validators
                    $msg = array();
                    $msg[] = '';
                    $msg[] = sprintf('The namespace sometimes contain a vendor namespace (e.g. <info>VendorName/BlogBundle</info> instead of simply <info>%s</info>).', $namespace, $namespace);
                    $msg[] = 'If you\'ve *did* type a vendor namespace, try using a forward slash <info>/</info> (<info>Acme/BlogBundle</info>)?';
                    $msg[] = '';
                    $output->writeln($msg);

                    $question = new ConfirmationQuestion($questionHelper->getQuestion(
                        sprintf('Keep <comment>%s</comment> as the bundle namespace (choose no to try again)?', $namespace),
                        'yes'
                    ), true);
                    $acceptedNamespace = $questionHelper->ask($input, $output, $question);
                }
            }
            $input->setOption('namespace', $namespace);
        }

        // bundle name
        $bundle = null;
        try {
            $bundle = $input->getOption('bundle-name') ? Validators::validateBundleName($input->getOption('bundle-name')) : null;
        } catch (\Exception $error) {
            $output->writeln($questionHelper->getHelperSet()->get('formatter')->formatBlock($error->getMessage(), 'error'));
        }

        if (null === $bundle) {
            $bundle = strtr(preg_match('/Module$/', $namespace) ? $namespace : $namespace.'Module', ['\\Module\\' => '', '\\' => '']);

            $output->writeln([
                '',
                'In your code, a module is often referenced by its name. It can be the',
                'concatenation of all namespace parts but it\'s really up to you to come',
                'up with a unique name (a good practice is to start with the vendor name).',
                'Based on the namespace, we suggest <comment>'.$bundle.'</comment>.',
                '',
            ]);
            $question = new Question($questionHelper->getQuestion('Bundle name', $bundle), $bundle);
            $question->setValidator(
                array('SmartCore\Bundle\CMSGeneratorBundle\Command\Validators', 'validateBundleName')
            );
            $bundle = $questionHelper->ask($input, $output, $question);
            $input->setOption('bundle-name', $bundle);
        }

        // target dir
        $dir = null;
        try {
            $dir = $input->getOption('dir') ? Validators::validateTargetDir($input->getOption('dir'), $bundle, $namespace) : null;
        } catch (\Exception $error) {
            $output->writeln($questionHelper->getHelperSet()->get('formatter')->formatBlock($error->getMessage(), 'error'));
        }

        if (null === $dir) {
            $dir = dirname($this->getContainer()->getParameter('kernel.root_dir')).'/src';

            $output->writeln([
                '',
                'The module can be generated anywhere. The suggested default directory uses',
                'the standard conventions.',
                '',
            ]);
            $question = new Question($questionHelper->getQuestion('Target directory', $dir), $dir);
            $question->setValidator(function ($dir) use ($bundle, $namespace) {
                return Validators::validateTargetDir($dir, $bundle, $namespace);
            });
            $dir = $questionHelper->ask($input, $output, $question);
            $input->setOption('dir', $dir);
        }

        // format
        $format = 'yml';

        // summary
        $output->writeln([
            '',
            $this->getHelper('formatter')->formatBlock('Summary before generation', 'bg=blue;fg=white', true),
            '',
            sprintf("You are going to generate a \"<info>%s\\%s</info>\" bundle\nin \"<info>%s</info>\" using the \"<info>%s</info>\" format.", $namespace, $bundle, $dir, $format),
            '',
        ]);
    }

    /**
     * @param OutputInterface $output
     * @param string $namespace
     * @param string $bundle
     * @param string $dir
     * @return array
     */
    protected function checkAutoloader(OutputInterface $output, $namespace, $bundle, $dir)
    {
        $output->write('Checking that the bundle is autoloaded: ');
        if (!class_exists($namespace.'\\'.$bundle)) {
            return [
                '- Edit the <comment>composer.json</comment> file and register the bundle',
                '  namespace in the "autoload" section:',
                '',
            ];
        }
    }

    /**
     * @return ModuleGenerator
     */
    protected function createGenerator()
    {
        return new ModuleGenerator($this->getContainer()->get('filesystem'));
    }
}
