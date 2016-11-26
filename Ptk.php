<?php
include "vendor/autoload.php";

use Symfony\Component\Console\Application;
use App\GenerateCommand;

$application = new Application();
$application->add(new GenerateCommand());
$application->run();
