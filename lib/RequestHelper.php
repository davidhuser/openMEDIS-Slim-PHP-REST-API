<?php

namespace lib;
use models\User;

/**
 * Class for Request handling
 *
 */
class RequestHelper
{

    /**
     * Verify if required fields are PUTted or POSTed in the request.
     *
     * @param Array $required_fields Required fields to check
     *
     * @return void
     */
    public static function verifyRequiredParams($required_fields)
    {
        $error = false;
        $error_fields = "";

        // Handling PUT request params
        if ($_SERVER['REQUEST_METHOD'] == 'PUT') {
            $app = \Slim\Slim::getInstance();

            $requestBody = $app->request()->getBody();
            $request_params = json_decode($requestBody, true);
        }

        // Handling POST request params
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $app = \Slim\Slim::getInstance();

            //add Slim Middleware because the framework parses POST data as URLencoded and not JSON
            $app->add(new \Slim\Middleware\ContentTypes());

            $requestBody = $app->request()->getBody();
            $request_params = json_decode($requestBody, true);

        }

        foreach ($required_fields as $field) {
            if (!isset($request_params[$field]) ||
                strtolower($request_params[$field]) == 'null' ||
                $request_params[$field] == ''
            ) {
                $error = true;
                $error_fields .= $field . ', ';
            }
        }

        if ($error) {
            // Required field(s) are missing or empty
            // echo error json and stop the app
            $response = array();
            $app = \Slim\Slim::getInstance();
            $response["error"] = true;
            $response["message"] = 'Required field ' . substr($error_fields, 0, -2) . ' missing, empty or null';
            RequestHelper::echoResponse(400, $response);
            $app->stop();
        }
    }

    /**
     * Response for a client with content and a HTTP response code.
     *
     * @param String $status_code Http response code
     * @param Int $response Json response
     */
    public static function echoResponse($status_code, $response)
    {
        $app = \Slim\Slim::getInstance();
        // Http response code
        $app->status($status_code);

        // setting response content type to json
        $app->contentType('application/json;charset=utf-8');

        echo json_encode($response);
    }

    /**
     * Read the user belonging to the incoming request and get his/her defaultDB.
     *
     * @return String $userdb
     */
    public static function readDbFromRequest(){
        $app = \Slim\Slim::getInstance();

        $oUser = new User();

        $request = $app->request();
        $public_key = $request->headers('X-PublicKey');

        //get User array from sent public key
        $user = $oUser->getUserByPublicKey($public_key);

        $userdb = $oUser->setDefaultDatabase($user['LoginID']);

        if($userdb != NULL){
            return $userdb;
        }else{
            $response["error"] = true;
            $response["defaultdb"] = "Could not select database of user.";
            LogHelper::write("Could not select database, check if the user's DefaultDB is available.", $user['username']);
            RequestHelper::echoResponse(500, $response);
            $app->stop();
        }
    }

    /**
     * Read the user belonging to the incoming request and get his/her defaultDB.
     *
     * @return String $userdb
     */
    public static function readUsernameFromRequest(){
        $app = \Slim\Slim::getInstance();

        $oUser = new User();

        $request = $app->request();
        $public_key = $request->headers('X-PublicKey');

        //get User array from sent public key
        $user = $oUser->getUserByPublicKey($public_key);

        $username = $user['username'];

        if($username != NULL){
            return $username;
        }else{
            return false;
        }
    }

    /**
     * Get timestamp for lastmodified fields
     *
     * @return String $lastmodified
     */
    public static function getTimestamp(){

        $currentTime = new \DateTime();
        $lastmodified = $currentTime->format('Y-m-d H:i:s');
        return $lastmodified;
    }

} 