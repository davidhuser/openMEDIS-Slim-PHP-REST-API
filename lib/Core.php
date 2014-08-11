<?php

namespace lib;

use mysqli;

/**
 * Core class which exists only once through the application
 *
 */
class Core
{
    public $dbh; // handle of the db connexion
    private static $instance;

    // constructor to create a MySQLi instance (="MySQL Improved Extension")
    private function __construct()
    {

        $db_host = ConfigHelper::read('db.host');
        $db_name = ConfigHelper::read('db.basename');
        $db_user = ConfigHelper::read('db.user');
        $db_pass = ConfigHelper::read('db.password');

        $this->dbh = new mysqli($db_host, $db_user, $db_pass, $db_name);

        //set UTF8 as global encoding
        $this->dbh->set_charset("utf8");
    }

    /**
     * get instance of Core object
     *
     * @return Object self
     */
    public static function getInstance()
    {
        if (!isset(self::$instance)) {
            $object = __CLASS__;
            self::$instance = new $object;
        }
        return self::$instance;
    }

    /**
     * get instance with a user specific database (DefaultDB)
     *
     * @param String $userDb
     * @return Object self
     */
    public static function getInstanceWithUserDB($userDb)
    {
        if (!isset(self::$instance)) {
            $object = __CLASS__;
            self::$instance = new $object;
        }
        self::$instance->dbh->select_db($userDb);
        return self::$instance;
    }
}