<?php

include "vendor/autoload.php";

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use App\Ptk;

/*class PtkCommand extends Command
{
    protected function configure()
    {
        $this
            // the name of the command (the part after "bin/console")
            ->setName('app:generate')

            // the short description shown while running "php bin/console list"
            ->setDescription('Generate politika pdf file.')

            // the full command description shown when running the command with
            // the "--help" option
            ->setHelp("This command generate pdf file that can be used in any ebook reader.")
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $output->write('Generating issue ...');
        (new Ptk())->generateKindleFile();
    }
}*/

(new Ptk())->generateKindleFile();
