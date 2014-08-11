<?php

use models\User;
use lib\RequestHelper as R;
use lib\LogHelper as Log;

// API versioning
$app->group('/v1', function () use ($app) {

    /**
     * POST route to login
     *
     */
    $app->post('/login', function () use ($app) {

        // check for required params
        $toVerify = array('username', 'password');
        R::verifyRequiredParams($toVerify);

        $app->add(new \Slim\Middleware\ContentTypes());

        $response = array();
        $code = 500;

        $body = $app->request()->getBody();

        $request = json_decode($body, true);

        $username = $request['username'];
        $password = $request['password'];

        $oUser = new User();

        // check for correct email and password
        if ($oUser->checkLogin($username, $password)) {

            // get the user by username
            $user = $oUser->getUserByUsername($username);

            if ($user != NULL) {

                $loginId = $user['LoginID'];

                $response['error'] = false;
                $response['login_id'] = $user['LoginID'];
                $response['username'] = $user['username'];
                $response['group_id'] = $user['GroupID'];
                $response['locale'] = $user['locale'];


                $public_key = $oUser->generateKeys($loginId, $username, $password);

                // field in DB is NULL by default, so let's check if they have been already created before.
                if ($public_key != NULL) {
                    $response['public_key'] = $public_key;
                    $code = 200;
                } else {
                    $response['public_key'] = "could not create or read keys";
                    $code = 500;
                    Log::write("Could not create or read keys from user", $user['username']);
                }

                //get access level of user (stored in employee table)
                $access_level = $oUser->getAccessLevel($loginId);
                $response['access_level'] = $access_level;

                if ($access_level == NULL) {
                    Log::write("*** access_level of user is NULL",  $user['username']);
                }

            } else {
                // unknown error occurred
                $response['error'] = true;
                $response['message'] = "An error occurred. Possible duplicate username?";
                $code = 500;
                Log::write("Could not get user from database", $user['username']);
            }
        } else {
            // user credentials are wrong
            $response['error'] = true;
            $response['message'] = 'Login failed. Incorrect credentials';
            $code = 401;
            Log::write("Login failed. Incorrect credentials");
        }
        //finally the response
        R::echoResponse($code, $response);
    });
});