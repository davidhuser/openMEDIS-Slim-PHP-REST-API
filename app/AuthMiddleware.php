<?php

namespace app;

use lib\ConfigHelper;
use lib\LogHelper as Log;
use models\User;

/**
 * Middleware which is called before any other call on a request.
 *
 */
class AuthMiddleware extends \Slim\Middleware
{

    protected $allowedRoutes;

    public function __construct()
    {
        // allowed routes and REQUEST_METHODS which need no authentication
        // include versioning and the API Specification route at http://myurl.com/api/public/ with 'GET/'
        $this->allowedRoutes = array(
            'POST/v1/login',
            'GET/'
        );
    }

    /**
     * Deny access
     *
     */
    public function denyAccess()
    {
        $req = $this->app->request();
        $path = $req->getPath();
        $ip = $req->getIp();
        Log::write("Access denied:  " . $path . " from " . $ip);

        $this->app->response()->setStatus(401);
    }

    /**
     * Check Allowed Routes.
     *
     * @param $routeCheck Array to check if allowed
     * @return boolean If success or not
     */
    public function checkAllowedRoutes($routeCheck)
    {
        foreach ($this->allowedRoutes as $routeString) {
            if ($routeCheck == $routeString) {
                return TRUE;
            }
        }
        return FALSE;
    }

    /**
     * This is the authenticate method where we check the X-Hash header from the client against
     * a hash that we will recreate here on the server. If the 2 match, it's a pass.
     *
     * @param String $public_key
     * @return boolean If success or not
     */
    public function authenticate($public_key)
    {

        //get request and X-Hash HTTP header
        $request = $this->app->request();
        $contentHash = $request->headers('X-Hash');

        $oUser = new User();
        $user = $oUser->getUserByPublicKey($public_key);

        //get private key for hashing
        $private_key = $oUser->getPrivateKey($user['LoginID']);

        //get HTTP request body for hashing
        $requestBody = $request->getBody();

        //hash the body and clientside timestamp and our private key from the user
        $hash = hash_hmac('sha256', $requestBody, $private_key);

        //if they match, the request is valid.
        if (md5($contentHash) === md5($hash)) {
            Log::write("authenticated for " . strtoupper($request->getMethod()) . "/" . $request->getPath(), $user['username']);
            return TRUE;
        } else {
            Log::write("Hashes do not match.", $user['username']);
            Log::write("Clienthash: " . $contentHash, $user['username']);
            Log::write("Serverhash: " . $hash, $user['username']);
            return FALSE;
        }
    }

    /**
     * This method will check the HTTP request headers for previous authentication. If
     * the request has already authenticated, the next middleware is called. Otherwise,
     * a 401 Authentication Required response is returned to the client.
     */
    public function call()
    {
        $req = $this->app->request();
        //check if request is allowed to access the route without authentication, if yes, the next middleware is called.
        if ($this->checkAllowedRoutes($req->getMethod() . $req->getResourceUri())) {
            $this->next->call();
        } else {
            $public_key = $req->headers('X-PublicKey');
            if ($this->authenticate($public_key)) {
                $this->next->call();
            } else {
                $this->denyAccess();
            }
        }
    }
}