<?php

namespace lib;


/**
 * Class for Password handling
 *
 */
class PasswordHelper
{

    /**
     * Method to check hash against a password
     *
     * @param String $hash The hash to be checked
     * @param String $password The password to be checked
     * @return boolean True if equal
     */
    public static function check_password($hash, $password)
    {
        $new_hash = md5($password);

        if ($new_hash == $hash) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Method to hash a password with MD5
     *
     * @param String $password The original password
     * @return String hashed password
     */
    public static function hash($password)
    {
        return md5($password);
    }
}