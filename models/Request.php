<?php

namespace models;

use lib\Core;
use lib\LogHelper;
use lib\RequestHelper;

/**
 * Model class for requests
 *
 */
class Request {

    protected $core;

    function __construct($userdb)
    {
        $this->core = Core::getInstanceWithUserDB($userdb);
    }

    /**
     * Add new request to an asset
     *
     * @param String $assetid
     * @param String $date
     * @param String $desc
     * @param Int $st_id
     * @param String $contact_name
     * @param String $note
     * @param String $by_user
     * @param Int $visitpid
     *
     * @return String $requestId
     */
    public function addRequest($assetid, $date, $desc, $st_id, $contact_name, $note, $by_user, $visitpid){

        $requestId = uniqid();

        $lastmodified = RequestHelper::getTimestamp();

        $query = "INSERT INTO request (Request_id, Request_date, Request_desc, AssetID, Request_st_id, Request_contact_name,
        Request_note, lastmodified, by_user, VisiTpID) VALUES (?,?,?,?,?,?,?,?,?,?)";

        if ($stmt = $this->core->dbh->prepare($query)) {
            $stmt->bind_param("ssssissssi", $requestId, $date, $desc, $assetid, $st_id, $contact_name, $note, $lastmodified, $by_user, $visitpid);
        }

        $result = $stmt->execute();
        $stmt->close();

        if ($result) {
            return $requestId;
        } else {
            return NULL;
        }
    }

    /**
     * Update existing request of an asset
     *
     * @param String $requestId
     * @param String $assetid
     * @param String $date
     * @param String $desc
     * @param Int $st_id
     * @param String $contact_name
     * @param String $note
     * @param String $by_user
     * @param Int $visitpid
     *
     * @return Boolean $num_affected_rows Returns true if rows have been altered
     */
    public function updateRequest($requestId, $assetid, $date, $desc, $st_id, $contact_name, $note, $by_user, $visitpid){

        $lastmodified = RequestHelper::getTimestamp();

        $query ="UPDATE request SET Request_date = ?, Request_desc = ?, AssetID = ?, Request_st_id = ?, Request_contact_name = ?, Request_note = ?, lastmodified = ?, by_user = ?, VisiTpID = ? WHERE Request_id = ?";

        if ($stmt = $this->core->dbh->prepare($query)) {
            //i = int, s = String, d = double, b = blob
            $stmt->bind_param("sssissssis", $date, $desc, $assetid, $st_id, $contact_name, $note, $lastmodified, $by_user, $visitpid, $requestId);
        }
        $stmt->execute();
        $num_affected_rows = $stmt->affected_rows;
        $stmt->close();
        return $num_affected_rows > 0;
    }

    /**
     * deletes a request with a requestID on a users DB.
     *
     * @param $userdb
     * @param $requestId
     * @return bool
     */
    public function deleteRequest($userdb, $requestId)
    {
        $username = RequestHelper::readUsernameFromRequest();
        //control variable
        $all_query_ok = true;

        //disable autocommit so commit/rollback is possible
        $this->core->dbh->autocommit(false);

        // select all intervention IDs with the specified RequestID as an array
        if ($stmt = $this->core->dbh->query("SELECT IntervID FROM intervention WHERE Request_id = '" . $requestId . "'")) {
            $row = $stmt->fetch_row();
            $stmt->close();
        } else {
            $all_query_ok = false;
        }
        if(!$all_query_ok) LogHelper::write("select intervid failed", $username);

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
        $this->core->dbh->query("DELETE FROM intervention WHERE Request_id = '" . $requestId . "'") ? false : $all_query_ok = false;
        if(!$all_query_ok) LogHelper::write("delete intervention failed", $username);

        //delete from requests
        $this->core->dbh->query("DELETE FROM request WHERE Request_id = '" . $requestId . "'") ? false : $all_query_ok = false;
        if(!$all_query_ok) LogHelper::write("delete request failed", $username);

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
     * Returns true if a request with a specified RequestID exists.
     *
     * @param $requestId
     * @return bool
     */
    public function checkIfRequestExists($requestId)
    {
        if ($stmt = $this->core->dbh->query("SELECT * FROM request WHERE Request_id = '" . $requestId . "'")) {
            $result = mysqli_num_rows($stmt) > 0;
            $stmt->close();
            return $result;
        }
    }

} 