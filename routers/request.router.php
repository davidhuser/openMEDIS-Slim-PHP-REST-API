<?php

use \models\Request;
use lib\RequestHelper as R;
use lib\LogHelper as Log;

// API versioning
$app->group('/v1', function () use ($app) {

    /**
     * POST route for adding new request on an asset
     *
     */
    $app->post('/asset/:id/request', function ($assetid) use ($app) {

        //required parameters not empty or 'null'
        $toVerify = array('Request_date', 'Request_desc', 'Request_contact_name', 'Request_note', 'Request_st_id', 'VisiTpID');
        R::verifyRequiredParams($toVerify);

        $app->add(new \Slim\Middleware\ContentTypes());

        $body = $app->request()->getBody();
        $request = json_decode($body, true);

        $response = array();

        $date = $request['Request_date'];
        $desc = $request['Request_desc'];
        $contact_name = $request['Request_contact_name'];
        $note = $request['Request_note'];
        $st_id = $request['Request_st_id'];
        $visitpid = $request['VisiTpID'];

        $userdb = R::readDbFromRequest();
        $by_user = R::readUsernameFromRequest();

        $oRequest = new Request($userdb);

        $requestId = $oRequest->addRequest($assetid, $date, $desc, $st_id, $contact_name, $note, $by_user, $visitpid);

        if ($requestId != NULL) {
            $response["error"] = false;
            $response["message"] = "Request added";
            Log::write("Request added. RequestID: " . $requestId . " | AssetID: " . $assetid, $by_user);
            R::echoResponse(201, $response);
        } else {
            $response["error"] = true;
            $response["message"] = "Could not add request";
            Log::write("Could not add request with ID " . $requestId . " for Asset " . $assetid, $by_user);
            R::echoResponse(500, $response);
        }

    });


    /**
     * PUT route for updating existing request on an asset
     *
     */
    $app->put('/asset/:assetid/:requestid', function ($assetid, $requestId) use ($app) {

        //required parameters not empty or 'null'
        $toVerify = array('Request_date', 'Request_desc', 'Request_contact_name', 'Request_note', 'Request_st_id', 'VisiTpID');
        R::verifyRequiredParams($toVerify);

        $app->add(new \Slim\Middleware\ContentTypes());

        $body = $app->request()->getBody();
        $request = json_decode($body, true);

        $response = array();

        $date = $request['Request_date'];
        $desc = $request['Request_desc'];
        $contact_name = $request['Request_contact_name'];
        $note = $request['Request_note'];
        $st_id = $request['Request_st_id'];
        $visitpid = $request['VisiTpID'];

        $userdb = R::readDbFromRequest();
        $by_user = R::readUsernameFromRequest();

        $oRequest = new Request($userdb);

        $result = $oRequest->updateRequest($requestId, $assetid, $date, $desc, $st_id, $contact_name, $note, $by_user, $visitpid);

        if ($result) {
            $response["error"] = false;
            $response["message"] = "Request updated";
            Log::write("Request updated. RequestID: " . $requestId . " | AssetID: " . $assetid, by_user);
            R::echoResponse(200, $response);
        } else {
            $response["error"] = true;
            $response["message"] = "Could not update request";
            Log::write("Could not update request with ID " . $requestId . "for Asset " . $assetid, $by_user);
            R::echoResponse(500, $response);
        }

    });

    /**
     * DELETE route for deleting request
     *
     */
    $app->delete('/request/:requestid', function ($requestId) use ($app) {
        $userdb = R::readDbFromRequest();
        $by_user = R::readUsernameFromRequest();

        $oReq = new Request($userdb);

        //check if asset exists, if not, throw 404 error.
        $exists = $oReq->checkIfRequestExists($requestId);

        if (!$exists) {
            $response["error"] = true;
            $response["message"] = "Request does not exist";
            R::echoResponse(404, $response);
        } else {

            $result = $oReq->deleteRequest($userdb, $requestId);

            if ($result) {
                $response["error"] = false;
                $response["message"] = "Request deleted";
                Log::write("Request " . $requestId . " deleted.", $by_user);
                R::echoResponse(200, $response);
            } else {
                $response["error"] = true;
                $response["message"] = "Could not delete request";
                Log::write("Could not delete Request " . $requestId .
                    " - Possible failures on deleting associated interventions/intervention_work/_material", $by_user);
                R::echoResponse(500, $response);
            }
        }
    });

});