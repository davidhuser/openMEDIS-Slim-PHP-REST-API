<?php

namespace models;

use lib\Core;

use lib\PasswordHelper as P;

/**
 * Model class for user
 *
 */
class User
{

    protected $core;

    function __construct()
    {
        $this->core = Core::getInstance();
    }

    /**
     * Get all users from Database
     *
     * @return Array of users
     */
    public function getUsers()
    {
        if ($result = $this->core->dbh->query("SELECT LoginID, GroupID, DefaultDB, locale FROM login")) {

            $rows = array();
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            return $rows;
        }
    }

    /**
     * Get user with specific LoginID
     *
     * @param String $id LoginID of user
     * @return Array of one user
     */
    public function getUserById($id)
    {
        $result = array();

        $query = "SELECT * FROM login WHERE id='.$id.'";
        if ($stmt = $this->core->dbh->prepare($query)) {

            $stmt->bind_param("i", $id);

            /* Execute it */
            $stmt->execute();

            /* Bind results */
            $stmt->bind_result($result);

            /* Fetch the value */
            $stmt->fetch();

            echo $result;

            /* Close statement */
            $stmt->close();
        }
        return $result;
    }




    /**
     * Get user with specific LoginID
     *
     * @param String $username Username of user
     * @param String $password Password of user
     * @return boolean If username with password could be found
     */
    public function checkLogin($username, $password)
    {

        $password_hash = P::hash($password);

        // fetching user by username
        $stmt = $this->core->dbh->prepare("SELECT password FROM login WHERE username = ?");

        $stmt->bind_param("s", $username);

        $stmt->execute();

        $stmt->bind_result($password_hash);

        $stmt->store_result();

        if ($stmt->num_rows > 0) {

            $stmt->fetch();

            $stmt->close();

            if (P::check_password($password_hash, $password)) {
                // User password is correct
                return TRUE;
            } else {
                // user password is incorrect
                return FALSE;
            }
        } else {
            $stmt->close();

            // user not existed with the email
            return FALSE;
        }
    }

    /**
     * Get user with username
     *
     * @param String $un Username of user
     * @return Array of user
     */
    public function getUserByUsername($un)
    {
        $username = mysqli_real_escape_string($this->core->dbh, $un);

        $query = "SELECT LoginID, username, GroupID, locale FROM login WHERE username= ?";

        if ($stmt = $this->core->dbh->prepare($query)) {

            $stmt->bind_param("s", $username);

            /* Execute it */
            $stmt->execute();

            $metaResults = $stmt->result_metadata();
            $fields = $metaResults->fetch_fields();
            $statementParams = '';
            $column = '';
            //build the bind_results statement dynamically so I can get the results in an array
            foreach ($fields as $field) {
                if (empty($statementParams)) {
                    $statementParams .= "\$column['" . $field->name . "']";
                } else {
                    $statementParams .= ", \$column['" . $field->name . "']";
                }
            }
            $statment = "\$stmt->bind_result($statementParams);";
            eval($statment);
            while ($stmt->fetch()) {
                return $column;
            }
        }
    }

    /**
     * Get DefaultDB field of user
     *
     * @param Int $loginId Username of user
     * @return mixed Returns Database name or NULL on failure
     */
    public function setDefaultDatabase($loginId)
    {
        $query = "SELECT DefaultDB FROM login WHERE LoginID = " . $loginId;

        if ($result = $this->core->dbh->query($query)) {
            $row = $result->fetch_row();
            $this->core->dbh->select_db("'" . $row[0] . "'");
            $result->close();
            return $row[0];
        }else{
            return NULL;
        }
    }

    /**
     * Get access_level of user
     *
     * @param Int $loginId LoginID of user
     * @return mixed Returns access_level or NULL on failure
     */
    public function getAccessLevel($loginId){

        $query = "SELECT Accesslevel FROM employees WHERE LoginID = " . $loginId;

        if ($result = $this->core->dbh->query($query)) {
            $row = $result->fetch_row();
            $this->core->dbh->select_db("'" . $row[0] . "'");
            $result->close();
            return $row[0];
        }else{
            return NULL;
        }
    }

    /**
     * Get access_level of user
     *
     * @param Int $loginId LoginID of user
     * @param String $username Username of user
     * @param String $password Username of user
     * @return mixed Returns existing or new public_key, or NULL on failure
     */
    public function generateKeys($loginId, $username, $password){

        $keysExist = FALSE;

        //check if keys already exist
        $query = "SELECT public_key FROM login WHERE LoginID = " . $loginId;
        if ($result = $this->core->dbh->query($query)) {
            $row = $result->fetch_row();
            $public_key = $row[0];
            if($row[0] != NULL){
                $keysExist = TRUE;
            }
            $result->close();
        }

        //if keys don't exist, create new keys and insert them into login table
        if(!$keysExist){
            $private_key = hash('sha256', $username . $password);
            $public_key = hash('sha256', uniqid());

            $stmt = $this->core->dbh->prepare("UPDATE login SET private_key = ?, public_key = ? WHERE LoginID = ?");
            $stmt->bind_param('ssi',$private_key, $public_key, $loginId);
            if($stmt->execute()){
                $stmt->close();
                return $public_key;
            }else{
                return NULL;
            }
        }else{
            return $public_key;
        }
    }

    /**
     * Get private key of user
     *
     * @param Int $loginId LoginID of user
     * @return mixed Returns private_key, or FALSE on failure
     */
    public function getPrivateKey($loginId){

        $query = "SELECT private_key FROM login WHERE LoginID = " . $loginId;
        if ($result = $this->core->dbh->query($query)) {
            $row = $result->fetch_row();
            $result->close();
            return $row[0];
        }else{
            return FALSE;
        }

    }

    /**
     * Get private key of user
     *
     * @param String $public_key public_key of user
     * @return Array of user
     */
    public function getUserByPublicKey($public_key){


        $query = "SELECT LoginID, username, GroupID, locale FROM login WHERE public_key= ?";

        if ($stmt = $this->core->dbh->prepare($query)) {

            $stmt->bind_param("s", $public_key);

            /* Execute it */
            $stmt->execute();

            $metaResults = $stmt->result_metadata();
            $fields = $metaResults->fetch_fields();
            $statementParams = '';
            $column = '';
            //build the bind_results statement dynamically so I can get the results in an array
            foreach ($fields as $field) {
                if (empty($statementParams)) {
                    $statementParams .= "\$column['" . $field->name . "']";
                } else {
                    $statementParams .= ", \$column['" . $field->name . "']";
                }
            }
            $statment = "\$stmt->bind_result($statementParams);";
            eval($statment);
            while ($stmt->fetch()) {
                return $column;
            }
        }else{
            return FALSE;
        }

    }
}