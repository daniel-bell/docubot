<?php

namespace Docubot\Commands;

use Docubot\InputAdapters\DocblockInputAdapter;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class RunCommand
 * @package Docubot\Commands
 */
class RunCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('docubot:run')
            ->setDescription('Run')
            ->addArgument(
                'directory',
                InputArgument::REQUIRED,
                'Directory for Docubot to run on'
            );
    }

    /**
     * @param InputInterface $input
     * @param OutputInterface $output
     * @return string
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $directory = $input->getArgument('directory');
        $output->writeln("<info>Running Docubot on $directory</info>");

        $db = new DocblockInputAdapter($directory);
        $db->process();
    }
}