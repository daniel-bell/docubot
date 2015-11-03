<?php

namespace Docubot;

require __DIR__.'/vendor/autoload.php';

use Docubot\Commands\RunCommand;
use Symfony\Component\Console\Application;

$app = new Application();
$app->add(new RunCommand());
$app->run();