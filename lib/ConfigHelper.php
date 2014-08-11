<?php

namespace lib;

/**
 * Config class to read and write config parameters
 *
 */
class ConfigHelper {
    static $confArray;

    public static function read($name) {
        return self::$confArray[$name];
    }

    public static function write($name, $value) {
        self::$confArray[$name] = $value;
    }
}