<?php
namespace App;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

class GenerateCommand extends Command
{

    /**
     * Configure the command
     */
    protected function configure()
    {
        $this
            ->setName('generate:last-issue')
            ->setDescription('Generate the last issue.')
        ;
    }

    /**
     * Execute the command
     *
     * @param InputInterface $input
     * @param OutputInterface $output
     *
     * @return mixed
     */
    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $ptk = new Ptk();
        $output->writeln('Generating the last issue of politika.rs');
        $ptk->generatePdfFile();

        $output->writeln('Lase issue is generated. Happy reading!');
    }
}