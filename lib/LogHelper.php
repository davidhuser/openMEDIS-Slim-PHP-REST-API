<?php

namespace lib;

/**
 * Class for Log handling
 *
 */
class LogHelper
{
    /**
     * Write to log with current timestamp and username
     *
     * @param String $string
     * @param String username
     */
    public static function write($string, $username = '-'){
        $app = \Slim\Slim::getInstance();
        $log = $app->getLog();

        $timestamp = new \DateTime();
        $formatted = $timestamp->format('Y-m-d H:i:s');

        $log->info($formatted . "  [" . $username .  "]  " . $string);
    }
}
