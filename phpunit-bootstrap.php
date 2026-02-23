<?php

use Symfony\Component\Dotenv\Dotenv;

ob_start();
$appRoot = dirname(__DIR__, 4);
require $appRoot . '/vendor/autoload.php';

$oroEnv = getenv('ORO_ENV') ?: ($_ENV['ORO_ENV'] ?? 'dev');
putenv('APP_ENV=' . $oroEnv);
$_ENV['APP_ENV'] = $_SERVER['APP_ENV'] = $oroEnv;

(new Dotenv('ORO_ENV', 'ORO_DEBUG'))
    ->setProdEnvs(['prod', 'behat_test'])
    ->bootEnv($appRoot . '/.env-app', 'prod', ['dev', 'prod']);
