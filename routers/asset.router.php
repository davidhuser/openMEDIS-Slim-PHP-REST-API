<?php

use models\Asset;
use lib\RequestHelper as R;
use lib\LogHelper as Log;

// API versioning
$app->group('/v1', function () use ($app) {

    /**
     * POST route for creating new asset
     *
     */
    $app->post('/asset', function () use ($app) {

        //required parameters not empty or 'null'
        $toVerify = array('GenericAssetID', 'AssetFullName', 'ManufacturerID', 'Model', 'WarrantyContractID',
            'SerialNumber', 'LocationID', 'DonorID', 'AssetStatusID', 'AssetUtilizationID', 'AgentID', 'SupplierID');
        R::verifyRequiredParams($toVerify);

        $app->add(new \Slim\Middleware\ContentTypes());

        $body = $app->request()->getBody();
        $request = json_decode($body, true);

        $response = array();

        $genericAssetId = $request['GenericAssetID'];
        $umdns = $request['UMDNS'];
        $assetFullName = $request['AssetFullName'];
        $manufacturerId = $request['ManufacturerID'];
        $model = $request['Model'];
        $serialNumber = $request['SerialNumber'];
        $internaliventoryNumber = $request['InternalIventoryNumber'];
        $locationId = $request['LocationID'];
        $responsiblePers = $request['ResponiblePers'];
        $assetStatusId = $request['AssetStatusID'];
        $assetUtilizationId = $request['AssetUtilizationID'];
        $purchaseDate = $request['PurchaseDate'];
        $installationDate = $request['InstallationDate'];
        $lifeTime = $request['Lifetime'];
        $purchasePrice = $request['PurchasePrice'];
        $currentValue = $request['CurrentValue'];
        $warrantyContractId = $request['WarrantyContractID'];
        $agentId = $request['AgentID'];
        $warrantyContractExp = $request['WarrantyContractExp'];
        $warrantyContractNotes = $request['WarrantyContractNotes'];
        $employeeId = $request['EmployeeID'];
        $supplierId = $request['SupplierID'];
        $donorId = $request['DonorID'];
        $serviceManual = $request['ServiceManual'];
        $notes = $request['Notes'];
        $picture = $request['Picture'];
        $URL_Manual = $request['URL_Manual'];
        $metrologyDocument = $request['MetrologyDocument'];
        $metrologyDate = $request['MetrologyDate'];
        $metrology = $request['Metrology'];

        //read user db and name from request
        $userdb = R::readDbFromRequest();
        $by_user = R::readUsernameFromRequest();


        $oAsset = new Asset($userdb);

        $new_asset_id = $oAsset->insertAsset($genericAssetId, $umdns, $assetFullName, $manufacturerId, $model, $serialNumber,
            $internaliventoryNumber, $locationId, $responsiblePers, $assetStatusId, $assetUtilizationId, $purchaseDate,
            $installationDate, $lifeTime, $purchasePrice, $currentValue, $warrantyContractId, $agentId, $warrantyContractExp,
            $warrantyContractNotes, $employeeId, $supplierId, $donorId, $serviceManual, $notes, $picture,
            $by_user, $URL_Manual, $metrologyDocument, $metrologyDate, $metrology);

        if ($new_asset_id != NULL) {
            $response["error"] = false;
            $response["message"] = "Asset created";
            Log::write("Asset with ID " . $new_asset_id . "created successfully.", $by_user);
            R::echoResponse(201, $response);
        } else {
            $response["error"] = true;
            $response["message"] = "Could not create asset";
            Log::write("Failed to create new asset.", $by_user);
            R::echoResponse(500, $response);
        }

    });

    /**
     * PUT route for updating existing asset
     *
     */
    $app->put('/asset/:id', function ($id) use ($app) {

        $toVerify = array('GenericAssetID', 'AssetFullName', 'ManufacturerID', 'Model', 'WarrantyContractID', 'SerialNumber', 'LocationID', 'DonorID', 'AssetStatusID', 'AssetUtilizationID', 'AgentID', 'SupplierID');
        R::verifyRequiredParams($toVerify);

        $body = $app->request()->getBody();
        $request = json_decode($body, true);

        $response = array();

        // function parameter
        $assetId = $id;

        $genericAssetId = $request['GenericAssetID'];
        $umdns = $request['UMDNS'];
        $assetFullName = $request['AssetFullName'];
        $manufacturerId = $request['ManufacturerID'];
        $model = $request['Model'];
        $serialNumber = $request['SerialNumber'];
        $internaliventoryNumber = $request['InternalIventoryNumber'];
        $locationId = $request['LocationID'];
        $responsiblePers = $request['ResponiblePers'];
        $assetStatusId = $request['AssetStatusID'];
        $assetUtilizationId = $request['AssetUtilizationID'];
        $purchaseDate = $request['PurchaseDate'];
        $installationDate = $request['InstallationDate'];
        $lifeTime = $request['Lifetime'];
        $purchasePrice = $request['PurchasePrice'];
        $currentValue = $request['CurrentValue'];
        $warrantyContractId = $request['WarrantyContractID'];
        $agentId = $request['AgentID'];
        $warrantyContractExp = $request['WarrantyContractExp'];
        $warrantyContractNotes = $request['WarrantyContractNotes'];
        $employeeId = $request['EmployeeID'];
        $supplierId = $request['SupplierID'];
        $donorId = $request['DonorID'];
        $serviceManual = $request['ServiceManual'];
        $notes = $request['Notes'];
        $picture = $request['Picture'];
        $URL_Manual = $request['URL_Manual'];
        $metrologyDocument = $request['MetrologyDocument'];
        $metrologyDate = $request['MetrologyDate'];
        $metrology = $request['Metrology'];

        $userdb = R::readDbFromRequest();
        $by_user = R::readUsernameFromRequest();

        $oAsset = new Asset($userdb);

        $result = $oAsset->updateAsset($assetId, $genericAssetId, $umdns, $assetFullName, $manufacturerId, $model, $serialNumber,
            $internaliventoryNumber, $locationId, $responsiblePers, $assetStatusId, $assetUtilizationId, $purchaseDate,
            $installationDate, $lifeTime, $purchasePrice, $currentValue, $warrantyContractId, $agentId, $warrantyContractExp,
            $warrantyContractNotes, $employeeId, $supplierId, $donorId, $serviceManual, $notes, $picture,
            $by_user, $URL_Manual, $metrologyDocument, $metrologyDate, $metrology);


        //if Asset::updateAsset returned true
        if ($result) {
            $response["error"] = false;
            $response["message"] = "Asset updated";
            Log::write("Asset with ID " . $id . " updated successfully.", $by_user);
            R::echoResponse(200, $response);
        } else {
            $response["error"] = true;
            $response["message"] = "Could not update asset";
            Log::write("Could not update Asset with ID " . $id, $by_user);
            R::echoResponse(500, $response);
        }
    });

    /**
     * DELETE route for deleting asset
     *
     */
    $app->delete('/asset/:assetid', function ($assetid) use ($app) {
        $userdb = R::readDbFromRequest();
        $username = R::readUsernameFromRequest();

        $oAsset = new Asset($userdb);

        //check if asset exists, if not, throw 404 error.
        $exists = $oAsset->checkIfAssetExists($assetid);

        if (!$exists) {
            $response["error"] = true;
            $response["message"] = "Asset does not exist";
            R::echoResponse(404, $response);
        } else {

            $result = $oAsset->deleteAsset($userdb, $assetid);

            if ($result) {
                $response["error"] = false;
                $response["message"] = "Asset deleted";
                Log::write("Asset " . $assetid . " deleted.", $username);
                R::echoResponse(200, $response);
            } else {
                $response["error"] = true;
                $response["message"] = "Could not delete asset";
                Log::write("Could not delete Asset " . $assetid .
                    " - Possible failures on deleting associated requests/interventions/intervention_work/_material", $username);
                R::echoResponse(500, $response);
            }
        }
    });
});