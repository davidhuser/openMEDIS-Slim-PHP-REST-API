<?php

namespace models;

use lib\ConfigHelper;
use lib\Core;

/**
 * DbExport class to call database specific actions
 *
 */
class DbExport
{

    protected $core;

    function __construct($userdb)
    {
        $this->core = Core::getInstanceWithUserDB($userdb);
    }

    /**
     * Read relevant tables for mobile scope and query database based on the access level of the incoming request to
     * make a copy of the MySQL database.
     *
     * @param String $access_level
     * @return mixed Returns database content array or false on bad query
     */
    function readRelevantTables($access_level)
    {
        $finalArray = Array();

        //copy table to keep original table from config
        $configTable = ConfigHelper::read('db.mysql_tables');

        $accessLevelTables = array('assets', 'request', 'intervention', 'location', 'facilities');
        $cutConfigTable = array_diff($configTable, $accessLevelTables);

        //convert access level to empty string if empty because we can't append NULL to a query
        if (!isset($access_level)) {
            $access_level = '';
        }

        //select only assets which are visible for the user defined in access_level field
        if ($result = $this->core->dbh->query('SELECT assets.AssetID, assets.GenericAssetID, assets.UMDNS,
            assets.AssetFullName, assets.ManufacturerID, assets.Model, assets.SerialNumber,
            assets.InternalIventoryNumber, assets.LocationID, assets.ResponsiblePers, assets.AssetStatusID,
            assets.AssetUtilizationID, assets.PurchaseDate, assets.InstallationDate, assets.Lifetime,
            assets.PurchasePrice, assets.CurrentValue, assets.WarrantyContractID, assets.AgentID,
            assets.WarrantyContractExp, assets.WarrantyContractNotes, assets.EmployeeID, assets.SupplierID,
            assets.DonorID, assets.ServiceManual, assets.Notes, assets.Picture, assets.lastmodified, assets.by_user, assets.URL_Manual,
            assets.MetrologyDocument, assets.MetrologyDate, assets.Metrology
            FROM assets, location, facilities, districts, province, countries
            WHERE location.LocationID = assets.LocationID
            AND facilities.FacilityID = location.FacilityID
            AND facilities.DistrictID = districts.DistrictID
            AND districts.ProvinceID = province.ProvinceID
            AND countries.CountryID = province.CountryID ' . $access_level)
        ) {
            $rows = Array();
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $finalArray['assets'] = $rows;
        }

        //select only requests which are visible for the user defined in access_level field
        if ($result = $this->core->dbh->query('SELECT request.Request_id, request.Request_date, request.Request_desc,
        request.Request_desc_eg, request.AssetID, request.Request_st_id, request.Request_contact_name,
        request.Request_note, request.lastmodified, request.by_user, request.VisiTpID
        FROM assets, request, location, facilities, districts, province, countries
        WHERE location.LocationID = assets.LocationID
        AND facilities.FacilityID = location.FacilityID
        AND facilities.DistrictID = districts.DistrictID
        AND districts.ProvinceID = province.ProvinceID
        AND countries.CountryID = province.CountryID
        AND request.AssetID = assets.AssetID ' . $access_level)
        ) {
            $rows = Array();
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $finalArray['request'] = $rows;
        }

        //select only interventions which are visible for the user defined in access_level field
        if ($result = $this->core->dbh->query('SELECT intervention.IntervID, intervention.Date, intervention.EmployeeID,
        intervention.AssetStatusID, intervention.AssetID_Visit, intervention.Request_id, intervention.FaildPart,
        intervention.FailurCategID, intervention.FailureCauseID, intervention.Interv_desc, intervention.Interv_desc_eg, intervention.Comments,
        intervention.RespEng, intervention.TotalWork, intervention.TotalCosts, intervention.lastmodified, intervention.by_user
        FROM assets, request, intervention, location, facilities, districts, province, countries
        WHERE location.LocationID = assets.LocationID
        AND facilities.FacilityID = location.FacilityID
        AND facilities.DistrictID = districts.DistrictID
        AND districts.ProvinceID = province.ProvinceID
        AND countries.CountryID = province.CountryID
        AND request.AssetID = assets.AssetID
        AND intervention.Request_id = request.Request_id ' . $access_level)
        ) {
            $rows = Array();
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $finalArray['intervention'] = $rows;
        }

        //select only location which are visible for the user defined in access_level field
        if ($result = $this->core->dbh->query('SELECT location.LocationID, location.FacilityID, location.DeptID,
            location.Roomnb, location.Floor, location.Building, location.NotetoTech
            FROM location, facilities, districts, province, countries
            WHERE facilities.FacilityID = location.FacilityID
            AND facilities.DistrictID = districts.DistrictID
            AND districts.ProvinceID = province.ProvinceID
            AND countries.CountryID = province.CountryID ' . $access_level)
        ) {
            $rows = Array();
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $finalArray['location'] = $rows;
        }

        //select only location which are visible for the user defined in access_level field
        if ($result = $this->core->dbh->query('SELECT facilities.FacilityID, facilities.DistrictID, facilities.FacilityName
            FROM facilities, districts, province, countries
            WHERE facilities.DistrictID = districts.DistrictID
            AND districts.ProvinceID = province.ProvinceID
            AND countries.CountryID = province.CountryID ' . $access_level)
        ) {
            $rows = Array();
            while ($row = $result->fetch_assoc()) {
                $rows[] = $row;
            }
            $finalArray['facilities'] = $rows;
        }

        foreach ($cutConfigTable as $table) {
            if ($result = $this->core->dbh->query('SELECT * FROM ' . $table)) {

                $rows = Array();
                while ($row = $result->fetch_assoc()) {
                    $rows[] = $row;
                }
                $finalArray[$table] = $rows;
                $result->close();
            }
        }
        if (!empty($finalArray)) {
            return $finalArray;
        } else {
            return false;
        }
    }

    /**
     * Read relevant tables' meta information
     *
     * @param String $db_name DefaultDB of request's user
     * @return mixed Returns database content array or false on bad query
     */
    function readRelevantTablesMetaInformation($db_name)
    {
        $tables = ConfigHelper::read('db.mysql_tables');
        $finalArray = Array();

        foreach ($tables as $table) {
            $query = 'SHOW COLUMNS FROM `' . $table . '` FROM `' . $db_name . '`';
            if ($result = $this->core->dbh->query($query)) {
                $rows = Array();
                while ($row = $result->fetch_assoc()) {
                    $rows[] = $row;
                }
                $finalArray[$table] = $rows;
            }
        }
        if (!empty($finalArray)) {
            return $finalArray;
        } else {
            return false;
        }
    }
}