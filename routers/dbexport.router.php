<?php

use lib\Config;
use models\DbExport;
use models\User;
use lib\RequestHelper as R;
use lib\LogHelper as Log;

// API Versioning
$app->group('/v1', function () use ($app) {

    /**
     * GET route to export whole database to JSON
     *
     */
    $app->get('/database/export', function () use ($app) {

        //create empty user
        $oUser = new User();

        //request header
        $request = $app->request();
        $public_key = $request->headers('X-PublicKey');

        //get User array from sent public key
        $user = $oUser->getUserByPublicKey($public_key);
        $userdb = $oUser->setDefaultDatabase($user['LoginID']);

        //get access level string of user
        $access_level = $oUser->getAccessLevel($user['LoginID']);

        //create new instance with the user specific database
        $tempTool = new DbExport($userdb);

        //read relevant table names with the user specific access level
        $export = $tempTool->readRelevantTables($access_level);

        $app->contentType('application/json;charset=utf-8');
        echo json_encode($export);
    });

    /**
     * GET route to export database meta information to JSON
     *
     */
    $app->get('/database/scheme', function () use ($app) {

        $userdb = R::readDbFromRequest();
        $by_user = R::readUsernameFromRequest();

        $tempTool = new DbExport($userdb);

        //read metainformation of tables
        $export = $tempTool->readRelevantTablesMetaInformation($userdb);

        if(!isset($export)){
            Log::write("The user's DefaultDB does not exist on the server!", $by_user);
        }

        $app->contentType('application/json;charset=utf-8');
        echo json_encode($export);
    });

});