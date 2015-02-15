<?php

namespace Smart\CoreBundle\Utils;

use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

trait OutputWritelnTrait
{
    /** @var InputInterface */
    protected $input;

    /** @var OutputInterface */
    protected $output;

    /**
     * @param InputInterface $input
     * @return $this
     */
    public function setInput(InputInterface $input)
    {
        $this->input = $input;

        return $this;
    }

    /**
     * @param OutputInterface $output
     * @return $this
     */
    public function setOutput(OutputInterface $output)
    {
        $this->output = $output;

        return $this;
    }

    /**
     * @param string $messages
     */
    protected function outputWriteln($messages = '')
    {
        if ($this->input instanceof InputInterface and $this->output instanceof OutputInterface) {
            $this->input->getOption('v') ? $this->output->writeln($messages) : null;
        }
    }
}
