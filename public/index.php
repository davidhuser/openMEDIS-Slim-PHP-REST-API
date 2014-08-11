<?php

require '../vendor/autoload.php';
require '../config.php';

//read version number from composer file
$composer = json_decode(file_get_contents(__DIR__ . '/../composer.json'));

// check if logging is enabled, if yes, activate the logwriter
$logEnabled = \lib\ConfigHelper::read('log');
if ($logEnabled) {

    $filename = '../log/log.log';
    $log = new \Slim\LogWriter(fopen($filename, 'a+'));
    // how long the file should be kept, deletes log file content if last access more than config.php::log.hours
    $hours = \lib\ConfigHelper::read('log.hours');
    if (time() - filectime($filename) > $hours * 3600) {
        unlink($filename);
    }
}

$debugEnabled = \lib\ConfigHelper::read('debug');

//instanciate new Slim App instance
$app = new \Slim\Slim(array(
    'version' => $composer->version,
    'debug' => $debugEnabled,
    'log.enabled' => $logEnabled,
    'log.level' => \Slim\Log::INFO,
    'log.writer' => $log,
    'templates.path' => '../templates/'
));

//first authenticate any request with a Slim middleware
$app->add(new \app\AuthMiddleware());

require_once __DIR__ . '/../app/app.php';

$app->run();