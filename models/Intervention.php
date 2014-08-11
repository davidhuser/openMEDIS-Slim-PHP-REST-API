<?php
namespace models;

use lib\Core;
use lib\LogHelper;
use lib\RequestHelper;

/**
 * Model class for Interventions
 *
 */
class Intervention
{

    protected $core;

    function __construct($userdb)
    {
        $this->core = Core::getInstanceWithUserDB($userdb);
    }

    /**
     * Add intervention
     *
     * @param String $date
     * @param String $employeeId
     * @param String $assetId
     * @param Int $assetStatusId
     * @param String $requestId
     * @param String $faildpart
     * @param Int $failurCategId
     * @param Int $failureCauseId
     * @param String $intervDesc
     * @param Int $comments
     * @param Int $respEng
     * @param Double $totalWork
     * @param Double $totalCosts
     * @param String $by_user
     *
     * @return mixed String created InterventionID or NULL
     */
    public function addIntervention($date, $employeeId, $assetId, $assetStatusId, $requestId, $faildpart,
                                    $failurCategId, $failureCauseId, $intervDesc, $comments, $respEng,
                                    $totalWork, $totalCosts, $by_user)
    {

        $intervID = uniqid();
        $lastmodified = RequestHelper::getTimestamp();

        $sql = "INSERT INTO intervention (IntervID, Date, EmployeeID, AssetID_Visit, AssetStatusID, Request_id, FaildPart, FailurCategID,
        FailureCauseID, Interv_desc, Comments, RespEng, TotalWork, TotalCosts, lastmodified, by_user)
        VALUES (?,?,?,?,?,?,?,?,?,?,?,?,?,?,?,?)";

        if ($stmt = $this->core->dbh->prepare($sql)) {
            $stmt->bind_param("ssssissiisssddss", $intervID, $date, $employeeId, $assetId, $assetStatusId, $requestId, $faildpart,
                $failurCategId, $failureCauseId, $intervDesc, $comments, $respEng, $totalWork, $totalCosts,
                $lastmodified, $by_user);
        }

        $result = $stmt->execute();
        $stmt->close();

        if ($result) {
            return $intervID;
        } else {
            return NULL;
        }
    }

    /**
     * Update intervention
     *
     * @param String $intervId
     * @param String $date
     * @param String $employeeId
     * @param String $assetId
     * @param Int $assetStatusId
     * @param String $requestId
     * @param String $faildpart
     * @param Int $failurCategId
     * @param Int $failureCauseId
     * @param String $intervDesc
     * @param Int $comments
     * @param Int $respEng
     * @param Double $totalWork
     * @param Double $totalCosts
     * @param String $by_user
     *
     * @return Boolean True if affected rows > 0
     */
    public function updateIntervention($intervId, $date, $employeeId, $assetId, $assetStatusId, $requestId, $faildpart,
                                       $failurCategId, $failureCauseId, $intervDesc, $comments, $respEng,
                                       $totalWork, $totalCosts, $by_user)
    {

        $lastmodified = RequestHelper::getTimestamp();

        $query = "UPDATE intervention SET Date = ?, EmployeeID = ?, AssetID_Visit = ?, AssetStatusID = ?, Request_id = ?, FaildPart = ?,
        FailurCategID = ?, FailureCauseID = ?, Interv_desc = ?, Comments = ?, RespEng = ?, TotalWork = ?, TotalCosts = ?,
        lastmodified = ?, by_user = ? WHERE IntervID = ?";

        if ($stmt = $this->core->dbh->prepare($query)) {
            //i = int, s = String, d = double, b = blob
            $stmt->bind_param("sssissiisssddsss", $date, $employeeId, $assetId, $assetStatusId, $requestId, $faildpart,
                $failurCategId, $failureCauseId, $intervDesc, $comments, $respEng, $totalWork, $totalCosts,
                $lastmodified, $by_user, $intervId);
        }
        $stmt->execute();
        $num_affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $num_affected_rows > 0;
    }

    /**
     * Add material to intervention
     *
     * @param $desc
     * @param $amount
     * @param $partNumber
     * @param $unitPrice
     * @param $intervID
     * @param $by_user
     * @return null|string
     */
    public function addMaterial($desc, $amount, $partNumber, $unitPrice, $intervID, $by_user)
    {
        //generate an materialId because the field is not AUTO_INCREMENT
        $materialId = uniqid();

        $lastmodified = RequestHelper::getTimestamp();

        $sql = "INSERT INTO intervention_material (MaterialID, Description, Amount, PartNumber, UnitPrice, lastmodified, by_user, IntervId) VALUES (?,?,?,?,?,?,?,?)";

        if ($stmt = $this->core->dbh->prepare($sql)) {
            $stmt->bind_param("ssdsdsss", $materialId, $desc, $amount, $partNumber, $unitPrice, $lastmodified, $by_user, $intervID);
        }

        $result = $stmt->execute();
        $stmt->close();

        if ($result) {
            return $materialId;
        } else {
            return NULL;
        }
    }

    /**
     * Add work
     *
     * @param $intervID
     * @param $action
     * @param $date_action
     * @param $time
     * @param $by_user
     * @return null|string
     */
    public function addWork($intervID, $action, $date_action, $time, $by_user)
    {
        //generate an actionId because the field is not AUTO_INCREMENT
        $actionId = uniqid();

        $lastmodified = RequestHelper::getTimestamp();

        $sql = "INSERT INTO intervention_work (ActionID, IntervID, Action, Date_action, Time, lastmodified, by_user) VALUES (?,?,?,?,?,?,?)";

        if ($stmt = $this->core->dbh->prepare($sql)) {
            $stmt->bind_param("ssssiss", $actionId, $intervID, $action, $date_action, $time, $lastmodified, $by_user);
        }

        $result = $stmt->execute();
        $stmt->close();

        if ($result) {
            return $actionId;
        } else {
            return NULL;
        }
    }

    /**
     * Get the intervention type of a request
     *
     * @param $requestId
     * @return mixed String or false
     */
    public function getInterventionType($requestId)
    {
        $query = 'SELECT visit_type.VisiTp FROM visit_type, request WHERE visit_type.VisiTpID = request.VisiTpID AND request.Request_id = "' . $requestId . '"';
        if ($result = $this->core->dbh->query($query)) {
            $row = $result->fetch_row();
            $result->close();
            return $row[0];
        } else {
            return FALSE;
        }
    }

    /**
     * Get the totalWork of an intervention
     *
     * @param $intervID
     * @return mixed String or false
     */
    public function getTotalWork($intervID)
    {
        $query = 'SELECT TotalWork FROM intervention WHERE IntervID = "' . $intervID . '"';
        if ($result = $this->core->dbh->query($query)) {
            $row = $result->fetch_row();
            $result->close();
            return $row[0];
        } else {
            return FALSE;
        }
    }

    /**
     * Get the TotalCost of an intervention
     *
     * @param $intervID
     * @return mixed String or false
     */
    public function getTotalCosts($intervID)
    {
        $query = 'SELECT TotalCosts FROM intervention WHERE IntervID = "' . $intervID . '"';
        if ($result = $this->core->dbh->query($query)) {
            $row = $result->fetch_row();
            $result->close();
            return $row[0];
        } else {
            return FALSE;
        }
    }

    /**
     * Update intervention material with totalCosts
     *
     * @param $intervId
     * @param $totalCosts
     * @param $by_user
     * @return bool
     */
    public function updateInterventionMaterial($intervId, $totalCosts, $by_user)
    {

        $lastmodified = RequestHelper::getTimestamp();

        $query = "UPDATE intervention SET TotalCosts = ?, lastmodified = ?, by_user = ? WHERE IntervID = ?";

        if ($stmt = $this->core->dbh->prepare($query)) {
            //i = int, s = String, d = double, b = blob
            $stmt->bind_param("dsss", $totalCosts, $lastmodified, $by_user, $intervId);
        }
        $stmt->execute();
        $num_affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $num_affected_rows > 0;
    }

    /**
     * Update intervention work with totalWork
     *
     * @param $intervId
     * @param $totalWork
     * @param $by_user
     * @return bool
     */
    public function updateInterventionWork($intervId, $totalWork, $by_user)
    {

        $lastmodified = RequestHelper::getTimestamp();

        $query = "UPDATE intervention SET TotalWork = ?, lastmodified = ?, by_user = ? WHERE IntervID = ?";

        if ($stmt = $this->core->dbh->prepare($query)) {
            //i = int, s = String, d = double, b = blob
            $stmt->bind_param("dsss", $totalWork, $lastmodified, $by_user, $intervId);
        }
        $stmt->execute();
        $num_affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $num_affected_rows > 0;
    }

    /**
     * Deletes intervention with a specified Intervention ID on a users DB
     *
     * @param $userdb
     * @param $intervId
     * @return bool
     */
    public function deleteIntervention($userdb, $intervId)
    {
        $username = RequestHelper::readUsernameFromRequest();
        //control variable
        $all_query_ok = true;

        //disable autocommit so commit/rollback is possible
        $this->core->dbh->autocommit(false);

        // select all intervention IDs with the specified RequestID as an array
        if ($stmt = $this->core->dbh->query("SELECT IntervID FROM intervention WHERE IntervID = '" . $intervId . "'")) {
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
        $this->core->dbh->query("DELETE FROM intervention WHERE IntervID = '" . $intervId . "'") ? false : $all_query_ok = false;

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
     * Deletes Intervention Work with a specified Intervention ID
     *
     * @param $intervId
     * @return bool
     */
    public function deleteInterventionWork($intervId)
    {
        if ($stmt = $this->core->dbh->prepare("DELETE FROM intervention_work WHERE IntervID = ?")) {
            $stmt->bind_param('s', $intervId);
        }
        $result = $stmt->execute();
        $stmt->close();
        if ($result) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Deletes Intervention Material with a specified Intervention ID
     *
     * @param $intervId
     * @return bool
     */
    public function deleteInterventionMaterial($intervId)
    {
        if ($stmt = $this->core->dbh->prepare("DELETE FROM intervention_material WHERE IntervID = ?")) {
            $stmt->bind_param('s', $intervId);
        }
        $result = $stmt->execute();
        $stmt->close();
        if ($result) {
            return TRUE;
        } else {
            return FALSE;
        }
    }

    /**
     * Returns true if an intervention with a specified Intervention ID exists.
     *
     * @param $intervId
     * @return bool
     */
    public function checkIfInterventionExists($intervId)
    {
        if ($stmt = $this->core->dbh->query("SELECT * FROM intervention WHERE IntervID = '" . $intervId . "'")) {
            $result = mysqli_num_rows($stmt) > 0;
            $stmt->close();
            return $result;
        }
    }
}