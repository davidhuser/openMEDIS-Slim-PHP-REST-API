<?php

use models\Intervention;
use lib\RequestHelper as R;
use lib\LogHelper as Log;

// API versioning
$app->group('/v1', function () use ($app) {

    /**
     * POST route for adding new intervention
     *
     */
    $app->post('/asset/:assetid/:requestid/intervention', function ($assetId, $requestId) use ($app) {

        $response = array();
        $toVerify = array();

        //get intervention type from request
        $userdb = R::readDbFromRequest();
        $by_user = R::readUsernameFromRequest();
        $oIntervention = new Intervention($userdb);
        $visitType = $oIntervention->getInterventionType($requestId);

        //change required fields based on intervention type
        if ($visitType) {
            if ($visitType == "Repair") {
                $toVerify = array('Date', 'EmployeeID', 'AssetID_Visit', 'AssetStatusID', 'Interv_desc', 'Comments', 'RespEng', 'FaildPart', 'FailurCategID', 'FailureCauseID');
            } else if ($visitType == "Maintenance") {
                $toVerify = array('Date', 'EmployeeID', 'AssetID_Visit', 'AssetStatusID', 'Interv_desc', 'Comments', 'RespEng');
            }
        } else {
            $response['error'] = true;
            $response['message'] = "Could not select intervention type of request";
            Log::write("Could not select intervention type of request " . $requestId, $by_user);
            R::echoResponse(500, $response);
        }
        R::verifyRequiredParams($toVerify);

        $app->add(new \Slim\Middleware\ContentTypes());

        $body = $app->request()->getBody();

        $request = json_decode($body, true);

        $date = $request['Date'];
        $employeeId = $request['EmployeeID'];
        $assetStatusId = $request['AssetStatusID'];
        $faildpart = $request['FaildPart'];
        $failurCategId = $request['FailurCategID'];
        $failureCauseId = $request['FailureCauseID'];
        $intervDesc = $request['Interv_desc'];
        $comments = $request['Comments'];
        $respEng = $request['RespEng'];
        $totalWork = $request['TotalWork'];
        $totalCosts = $request['TotalCosts'];

        //we get these as 'null' Strings so we convert them to float.
        if ($request['TotalWork'] == NULL || strtolower($request['TotalWork'] == 'null')) {
            $totalWork = 0;
        }

        if ($request['TotalCosts'] == NULL || strtolower($request['TotalCosts'] == 'null')) {
            $totalCosts = 0;
        }

        $interventionId = $oIntervention->addIntervention($date, $employeeId, $assetId, $assetStatusId, $requestId,
            $faildpart, $failurCategId, $failureCauseId, $intervDesc, $comments, $respEng, $totalWork, $totalCosts, $by_user);

        if ($interventionId != NULL) {
            $response["error"] = false;
            $response["message"] = "Intervention added";
            Log::write("Intervention added. RequestID: " . $requestId, $by_user);
            R::echoResponse(201, $response);
        } else {
            $response["error"] = true;
            Log::write("Could not add intervention", $by_user);
            R::echoResponse(500, $response);
        }

    });

    /**
     * PUT route for updating existing intervention on a request
     *
     */
    $app->put('/asset/:assetid/:requestid/:interventionid', function ($assetid, $requestId, $interventionId) use ($app) {

        $response = array();
        $toVerify = array();

        //get intervention type from request
        $userdb = R::readDbFromRequest();
        //get user DB from request
        $by_user = R::readUsernameFromRequest();
        $oIntervention = new Intervention($userdb);
        $visitType = $oIntervention->getInterventionType($requestId);

        //change required fields based on intervention type
        if ($visitType) {
            if ($visitType == "Repair") {
                $toVerify = array('Date', 'EmployeeID', 'AssetID_Visit', 'AssetStatusID', 'Interv_desc', 'Comments', 'RespEng', 'FaildPart', 'FailurCategID', 'FailureCauseID');
            } else if ($visitType == "Maintenance") {
                $toVerify = array('Date', 'EmployeeID', 'AssetID_Visit', 'AssetStatusID', 'Interv_desc', 'Comments', 'RespEng');
            }
        } else {
            $response['error'] = true;
            $response['message'] = "Could not select intervention type of request.";
            Log::write("Could not select intervention type of request " . $requestId, $by_user);
            R::echoResponse(500, $response);
        }
        R::verifyRequiredParams($toVerify);

        $app->add(new \Slim\Middleware\ContentTypes());

        $body = $app->request()->getBody();
        $request = json_decode($body, true);

        $response = array();

        $date = $request['Date'];
        $employeeId = $request['EmployeeID'];
        $assetStatusId = $request['AssetStatusID'];
        $faildpart = $request['FaildPart'];
        $failurCategId = $request['FailurCategID'];
        $failureCauseId = $request['FailureCauseID'];
        $intervDesc = $request['Interv_desc'];
        $comments = $request['Comments'];
        $respEng = $request['RespEng'];
        $totalWork = $request['TotalWork'];
        $totalCosts = $request['TotalCosts'];

        //we get these as 'null' Strings so we convert them to float.
        if ($request['TotalWork'] == NULL || strtolower($request['TotalWork'] == 'null')) {
            $totalWork = 0;
        }

        if ($request['TotalCosts'] == NULL || strtolower($request['TotalCosts'] == 'null')) {
            $totalCosts = 0;
        }

        $result = $oIntervention->updateIntervention($interventionId, $date, $employeeId, $assetid, $assetStatusId, $requestId,
            $faildpart, $failurCategId, $failureCauseId, $intervDesc, $comments, $respEng, $totalWork, $totalCosts, $by_user);

        if ($result) {
            $response["error"] = false;
            $response["message"] = "Intervention updated";
            Log::write("Intervention updated. RequestID: " . $requestId . " | InterventionID: " . $interventionId, $by_user);
            R::echoResponse(200, $response);
        } else {
            $response["error"] = true;
            $response["message"] = "Could not update intervention";
            Log::write("Could not update intervention with ID " . $interventionId . "for Request " . $requestId, $by_user);
            R::echoResponse(500, $response);
        }
    });

    /**
     * DELETE route for deleting intervention
     *
     */
    $app->delete('/intervention/:interventionid', function ($intervId) use ($app) {
        $userdb = R::readDbFromRequest();

        $by_user = R::readUsernameFromRequest();

        $oInt = new Intervention($userdb);

        //check if asset exists, if not, throw 404 error.
        $exists = $oInt->checkIfInterventionExists($intervId);

        if (!$exists) {
            $response["error"] = true;
            $response["message"] = "Intervention does not exist";
            R::echoResponse(404, $response);
        } else {

            $result = $oInt->deleteIntervention($userdb, $intervId);

            if ($result) {
                $response["error"] = false;
                $response["message"] = "Intervention deleted";
                Log::write("Intervention " . $intervId . " deleted.", $by_user );
                R::echoResponse(200, $response);
            } else {
                $response["error"] = true;
                $response["message"] = "Could not delete intervention";
                Log::write("Could not delete intervention " . $intervId .
                    " - Possible failures on deleting associated intervention_work/_material", $by_user);
                R::echoResponse(500, $response);
            }
        }
    });

    /**
     * POST route for creating new intervention material
     *
     */
    $app->post('/intervention/material', function () use ($app) {
        //required parameters not empty or 'null'
        $toVerify = array('Description', 'Amount', 'PartNumber', 'UnitPrice', 'IntervID');
        R::verifyRequiredParams($toVerify);

        $app->add(new \Slim\Middleware\ContentTypes());

        $body = $app->request()->getBody();
        $request = json_decode($body, true);

        $response = array();

        $desc = $request['Description'];
        $amount = $request['Amount'];
        $partNumber = $request['PartNumber'];
        $unitPrice = $request['UnitPrice'];
        $intervID = $request['IntervID'];

        //read user db and name from request
        $userdb = R::readDbFromRequest();
        $by_user = R::readUsernameFromRequest();

        //read old total cost
        $oIntervention = new Intervention($userdb);
        $materialId = $oIntervention->addMaterial($desc, $amount, $partNumber, $unitPrice, $intervID, $by_user);

        $oldTotalCosts = $oIntervention->getTotalCosts($intervID);

        //update new total cost
        $newTotalCost = $oldTotalCosts + ($unitPrice * $amount);
        $InUpdated = $oIntervention->updateInterventionMaterial($intervID, $newTotalCost, $by_user);


        if ($materialId != NULL & $InUpdated) {
            $response["error"] = false;
            $response["message"] = "Intervention material created";
            Log::write("Intervention material with ID " . $materialId . "created successfully.", $by_user);
            R::echoResponse(201, $response);
        } else {
            $response["error"] = true;
            $response["message"] = "Could not create intervention material";
            Log::write("Failed to create new intervention material.", $by_user);
            R::echoResponse(500, $response);
        }

    });

    /**
     * POST route for creating new intervention work
     *
     */
    $app->post('/intervention/work', function () use ($app) {
        //required parameters not empty or 'null'
        $toVerify = array('IntervID', 'Action', 'Date_action', 'Time');
        R::verifyRequiredParams($toVerify);

        $app->add(new \Slim\Middleware\ContentTypes());

        $body = $app->request()->getBody();
        $request = json_decode($body, true);

        $response = array();

        $intervID = $request['IntervID'];
        $action = $request['Action'];
        $date_action = $request['Date_action'];
        $time = $request['Time'];

        //read user db and name from request
        $userdb = R::readDbFromRequest();
        $by_user = R::readUsernameFromRequest();

        //add work
        $oIntervention = new Intervention($userdb);
        $workId = $oIntervention->addWork($intervID, $action, $date_action, $time, $by_user);

        //read old total work
        $oldTotalWork = $oIntervention->getTotalWork($intervID);

        //update new total work as hours (not as minutes)
        $newTotalWork = $oldTotalWork + ($time/60);
        $InUpdated = $oIntervention->updateInterventionWork($intervID, $newTotalWork, $by_user);

        if ($workId != NULL & $InUpdated) {
            $response["error"] = false;
            $response["message"] = "Intervention work created";
            Log::write("Intervention work with ID " . $workId . " created successfully.", $by_user);
            R::echoResponse(201, $response);
        } else {
            $response["error"] = true;
            $response["message"] = "Could not create intervention work";
            Log::write("Failed to create new intervention work.", $by_user);
            R::echoResponse(500, $response);
        }

    });
});