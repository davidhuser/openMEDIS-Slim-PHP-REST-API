<?php

namespace models;

use lib\Core;
use lib\LogHelper;
use lib\RequestHelper;
use Slim\Log;
use Slim\Slim;

/**
 * Model class for assets
 *
 */
class Asset
{
    protected $core;

    function __construct($userdb)
    {
        $this->core = Core::getInstanceWithUserDB($userdb);
    }

    /**
     * Get all assets from Database
     *
     * @return Array of assets
     */
    public function getAssets()
    {
        if ($result = $this->core->dbh->query("SELECT * FROM assets")) {

            $rows = array();
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            return $rows;
        }
    }

    /**
     * Get specific asset from Database
     *
     * @param String $assetId
     * @return Array of one asset
     */
    public function getAssetById($assetId)
    {
        $query = "SELECT AssetID, AssetFullName, GenericAssetID, UMDNS, ManufacturerID, Model,
        SerialNumber, InternalIventoryNumber, LocationID, ResponsiblePers, AssetStatusID, AssetUtilizationId,
        PurchaseDate, InstallationDate, Lifetime, PurchasePrice, CurrentValue, WarrantyContractID, AgentID,
        WarrantyContractExp, WarrantyContractNotes, EmployeeID, SupplierID, DonorID, ServiceManual, Notes, Picture,
        lastmodified, by_user, URL_Manual, MetrologyDocument, MetrologyDate, Metrology FROM assets WHERE AssetID = ?";
        if ($stmt = $this->core->dbh->prepare($query)) {

            $stmt->bind_param("s", $assetId);

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
     * Insert new asset
     *
     * @param Int $genericAssetId
     * @param Int $umdns
     * @param String $assetFullName
     * @param String $manufacturerId
     * @param String $model
     * @param String $serialNumber
     * @param String $internaliventoryNumber
     * @param String $locationId
     * @param String $responsiblePers
     * @param Int $assetStatusId
     * @param Int $assetUtilizationId
     * @param String $purchaseDate
     * @param String $installationDate
     * @param String $lifeTime
     * @param Double $purchasePrice
     * @param Double $currentValue
     * @param Int $warrantyContractId
     * @param String $agentId
     * @param String $warrantyContractExp
     * @param String $warrantyContractNotes
     * @param String $employeeId
     * @param String $supplierId
     * @param String $donorId
     * @param String $serviceManual
     * @param String $notes
     * @param String $picture
     * @param String $by_user
     * @param String $URL_Manual
     * @param String $metrologyDocument
     * @param String $metrologyDate
     * @param String $metrology
     * @param String $assetFullName
     *
     * @return String assetID
     */
    public function insertAsset($genericAssetId, $umdns, $assetFullName, $manufacturerId, $model,
                                $serialNumber, $internaliventoryNumber, $locationId, $responsiblePers, $assetStatusId,
                                $assetUtilizationId, $purchaseDate, $installationDate, $lifeTime, $purchasePrice,
                                $currentValue, $warrantyContractId, $agentId, $warrantyContractExp,
                                $warrantyContractNotes, $employeeId, $supplierId, $donorId, $serviceManual,
                                $notes, $picture, $by_user, $URL_Manual, $metrologyDocument,
                                $metrologyDate, $metrology)
    {
        //generate an AssetID because the field is not AUTO_INCREMENT
        $assetId = uniqid();

        $lastmodified = RequestHelper::getTimestamp();

        $sql = "INSERT INTO assets (AssetId, GenericAssetID, UMDNS, AssetFullName, ManufacturerID, Model,
        SerialNumber, InternalIventoryNumber, LocationID, ResponsiblePers, AssetStatusID, AssetUtilizationID,
        PurchaseDate, InstallationDate, Lifetime, PurchasePrice, CurrentValue, WarrantyContractID, AgentID,
        WarrantyContractExp, WarrantyContractNotes, EmployeeID, SupplierID, DonorID, ServiceManual, Notes, Picture,
        lastmodified, by_user, URL_Manual, MetrologyDocument, MetrologyDate, Metrology) VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

        if ($stmt = $this->core->dbh->prepare($sql)) {
            $stmt->bind_param("siisssssssiissiddisssssssssssssss", $assetId, $genericAssetId, $umdns, $assetFullName,
                $manufacturerId, $model, $serialNumber, $internaliventoryNumber, $locationId, $responsiblePers,
                $assetStatusId, $assetUtilizationId, $purchaseDate, $installationDate, $lifeTime, $purchasePrice,
                $currentValue, $warrantyContractId, $agentId, $warrantyContractExp, $warrantyContractNotes, $employeeId,
                $supplierId, $donorId, $serviceManual, $notes, $picture, $lastmodified, $by_user, $URL_Manual,
                $metrologyDocument, $metrologyDate, $metrology);
        }

        $result = $stmt->execute();
        $stmt->close();

        if ($result) {
            return $assetId;
        } else {
            return NULL;
        }
    }


    /**
     * Update asset
     *
     * @param String $assetId
     * @param Int $genericAssetId
     * @param Int $umdns
     * @param String $assetFullName
     * @param String $manufacturerId
     * @param String $model
     * @param String $serialNumber
     * @param String $internaliventoryNumber
     * @param String $locationId
     * @param String $responsiblePers
     * @param Int $assetStatusId
     * @param Int $assetUtilizationId
     * @param String $purchaseDate
     * @param String $installationDate
     * @param String $lifeTime
     * @param Double $purchasePrice
     * @param Double $currentValue
     * @param Int $warrantyContractId
     * @param String $agentId
     * @param String $warrantyContractExp
     * @param String $warrantyContractNotes
     * @param String $employeeId
     * @param String $supplierId
     * @param String $donorId
     * @param String $serviceManual
     * @param String $notes
     * @param String $picture
     * @param String $by_user
     * @param String $URL_Manual
     * @param String $metrologyDocument
     * @param String $metrologyDate
     * @param String $metrology
     * @param String $assetFullName
     *
     * @return Int Number of affected rows
     */
    public function updateAsset($assetId, $genericAssetId, $umdns, $assetFullName, $manufacturerId, $model, $serialNumber,
                                $internaliventoryNumber, $locationId, $responsiblePers, $assetStatusId, $assetUtilizationId, $purchaseDate,
                                $installationDate, $lifeTime, $purchasePrice, $currentValue, $warrantyContractId, $agentId, $warrantyContractExp,
                                $warrantyContractNotes, $employeeId, $supplierId, $donorId, $serviceManual, $notes, $picture,
                                $by_user, $URL_Manual, $metrologyDocument, $metrologyDate, $metrology)
    {

        $lastmodified = RequestHelper::getTimestamp();

        //cast the incoming parameters (all Strings) to its original data type(s)
        $genericAssetId = (int)$genericAssetId;
        $umdns = (int)$umdns;
        $assetStatusId = (int)$assetStatusId;
        $assetUtilizationId = (int)$assetUtilizationId;
        $lifeTime = (int)$lifeTime;
        $purchasePrice = (double)$purchasePrice;
        $currentValue = (double)$currentValue;
        $warrantyContractId = (int)$warrantyContractId;

        $sql = "UPDATE assets SET GenericAssetID = ?, UMDNS = ?, AssetFullName = ?, ManufacturerID = ?, Model = ?, SerialNumber = ?, InternalIventoryNumber = ?, LocationID = ?, ResponsiblePers = ?, AssetStatusID = ?, AssetUtilizationID = ?, PurchaseDate = ?, InstallationDate = ?, Lifetime = ?, PurchasePrice = ?, CurrentValue = ?, WarrantyContractID = ?, AgentID = ?, WarrantyContractExp = ?, WarrantyContractNotes = ?, EmployeeID = ?, SupplierID = ?, DonorID = ?, ServiceManual = ?, Notes = ?, Picture = ?, lastmodified = ?, by_user = ?, URL_Manual = ?, MetrologyDocument = ?, MetrologyDate = ?, Metrology = ? WHERE AssetID = ?";

        if ($stmt = $this->core->dbh->prepare($sql)) {
            //i = int, s = String, d = double, b = blob
            $stmt->bind_param("iisssssssiissiddissssssssssssssss", $genericAssetId, $umdns, $assetFullName,
                $manufacturerId, $model, $serialNumber, $internaliventoryNumber, $locationId, $responsiblePers,
                $assetStatusId, $assetUtilizationId, $purchaseDate, $installationDate, $lifeTime, $purchasePrice,
                $currentValue, $warrantyContractId, $agentId, $warrantyContractExp, $warrantyContractNotes, $employeeId,
                $supplierId, $donorId, $serviceManual, $notes, $picture, $lastmodified, $by_user, $URL_Manual,
                $metrologyDocument, $metrologyDate, $metrology, $assetId);
        }
        $stmt->execute();
        $num_affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $num_affected_rows > 0;

    }

    /**
     * Deletes an asset and its belonging requests, interventions and intervention material and work
     *
     * @param $userdb
     * @param $assetId
     * @return bool
     */
    public function deleteAsset($userdb, $assetId)
    {

        //prevent sql injection
        $assetId = mysqli_real_escape_string($this->core->dbh, $assetId);

        //control variable
        $all_query_ok = true;

        //disable autocommit so commit/rollback is possible
        $this->core->dbh->autocommit(false);

        // select all intervention IDs with the specified AssetID as an array
        if ($stmt = $this->core->dbh->query("SELECT IntervID FROM intervention WHERE AssetID_Visit = '" . $assetId . "'")) {
            $row = $stmt->fetch_row();
            $stmt->close();
        } else {
            $all_query_ok = false;
        }

        //skip deleting intervention work and material if no interventions are in the DB
        //delete from intervention_material and intervention_work
        if (!empty($row)) {
            $oIntervention = new Intervention($userdb);
            foreach ($row as $intervID) {
                $all_query_ok &= $oIntervention->deleteInterventionMaterial($intervID);
                $all_query_ok &= $oIntervention->deleteInterventionWork($intervID);
            }
        }

        //delete from interventions
        $this->core->dbh->query("DELETE FROM intervention WHERE AssetID_Visit = '" . $assetId . "'") ? false : $all_query_ok = false;

        //delete from requests
        $this->core->dbh->query("DELETE FROM request WHERE AssetID = '" . $assetId . "'") ? false : $all_query_ok = false;

        //delete from assets
        $this->core->dbh->query("DELETE FROM assets WHERE AssetID = '" . $assetId . "'") ? false : $all_query_ok = false;

        // commit or rollback
        if ($all_query_ok) {
            $this->core->dbh->commit();
            $this->core->dbh->close();
            return TRUE;
        } else {
            $this->core->dbh->rollback();
            $this->core->dbh->close();
            return FALSE;
        }
    }

    /**
     * Returns true if an asset with a specified AssetID exists.
     *
     * @param $assetId
     * @return bool
     */
    public function checkIfAssetExists($assetId)
    {
        if ($stmt = $this->core->dbh->query("SELECT AssetID FROM assets WHERE AssetID = '" . $assetId . "'")) {
            $result = mysqli_num_rows($stmt) > 0;
            $stmt->close();
            return $result;
        }
    }
}