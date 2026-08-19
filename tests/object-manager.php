<?php

use App\Kernel;
use Symfony\Component\Dotenv\Dotenv;

require dirname(__DIR__).'/vendor/autoload.php';

(new Dotenv())->bootEnv(dirname(__DIR__).'/.env');

assert(is_string($_SERVER['APP_ENV']));
$kernel = new Kernel($_SERVER['APP_ENV'], (bool) ($_SERVER['APP_DEBUG'] ?? true));
$kernel->boot();

return $kernel->getContainer()->get('doctrine')->getManager();
