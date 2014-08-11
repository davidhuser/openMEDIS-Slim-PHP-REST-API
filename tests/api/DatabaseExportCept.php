<?php 
$I = new ApiTester($scenario);

$I->wantTo('Test the database/export endpoint via GET');
$I->haveHttpHeader('X-PublicKey', '248512b6a66f365a4e42f10ed0c854844767b8ca8eb0f74589953991e9f233b6');
$I->haveHttpHeader('X-Hash', 'e651e0f6450f89d82ab0a34c1d421097a635897f5e719179e49263ff145e6ed9');
$I->sendGET('database/export');
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();

$I->seeResponseContainsJson(array('assets' => array(0 => array(
    'AssetID' => '4b67517f4462a',
    'GenericAssetID' => '12636',
    'UMDNS' => '12636',
    'AssetFullName' => 'Dash',
    'ManufacturerID' => '4c44276c3c2c0',
    'Model' => '4000 ',
    'SerialNumber' => 'SD008484463GA',
    'InternalIventoryNumber' => '01382928',
    'LocationID' => '4b6b4f5120321',
    'ResponsiblePers' => NULL,
    'AssetStatusID' => '1',
    'AssetUtilizationID' => '1',
    'PurchaseDate' => NULL,
    'InstallationDate' => NULL,
    'Lifetime' => '10',
    'PurchasePrice' => '131174',
    'CurrentValue' => '104939',
    'WarrantyContractID' => '1',
    'AgentID' => '4c90677ca7db7',
    'WarrantyContractExp' => NULL,
    'WarrantyContractNotes' => NULL,
    'EmployeeID' => '4cb6b8bbb9df6',
    'SupplierID' => '4b595a7124c8b',
    'DonorID' => '4c9066fc81b53',
    'Notes' => NULL,
    'Picture' => 'gen_images/12636.jpg',
    'lastmodified' => '2010-02-05 01:47:18',
    'by_user' => 'demo',
    'URL_Manual' => NULL,
    'MetrologyDocument' => NULL,
    'MetrologyDate' => NULL,
    'Metrology' => '0'
))));

$I->seeResponseContainsJson(array('location' => array(0 => array(
    'LocationID' => '4b6b4f5120321',
    'FacilityID' => '11',
    'DeptID' => '2',
    'Roomnb' => '',
    'Floor' => '',
    'Building' => '',
    'NotetoTech' => '-'
))));

$I->seeResponseContainsJson(array('facilities' => array(0 => array(
    'FacilityID' => '11',
    'DistrictID' => '10001',
    'FacilityName' => 'Central Hosptial'
))));

$I->seeResponseContainsJson(array('facilities' => array(0 => array(
    'FacilityID' => '11',
    'DistrictID' => '10001',
    'FacilityName' => 'Central Hosptial'
))));

$I->seeResponseContainsJson(array('contacttype' => array(0 => array(
    'ContactTypeID' => '1',
    'ContactTypeName' => 'Manufacturer'
))));

$I->seeResponseContainsJson(array('contact' => array(0 => array(
    'ContactID' => '4ac3718e8d251',
    'ContactTypeID' => '3',
    'ContactName' => 'Nespecificat',
    'ContactPersonName' => NULL,
    'ContactTitle' => NULL,
    'Address' => NULL,
    'City' => NULL,
    'PostalCode' => NULL,
    'CountryID' => '99999',
    'PhoneNumber' => NULL,
    'FaxNumber' => NULL,
    'Website' => '',
    'Services' => NULL
))));

$I->seeResponseContainsJson(array('donors' => array(0 => array(
    'DonorID' => '0',
    'ContactID' => '4ac3718e8d251'
))));

$I->seeResponseContainsJson(array('agents' => array(0 => array(
    'AgentID' => '0',
    'ContactID' => '4ac3718e8d251'
))));

$I->seeResponseContainsJson(array('suppliers' => array(0 => array(
    'SupplierID' => '4b59581a8bb8f',
    'ContactID' => '4b59581a8abf1'
))));

$I->seeResponseContainsJson(array('manufactures' => array(0 => array(
    'ManufacturerID' => '4b0be58d2806b',
    'ContactID' => '4b0be58d2612c'
))));

$I->seeResponseContainsJson(array('consumables' => array(0 => array(
    'ConsumableID' => '1',
    'Name' => 'Sensor debit',
    'ManufacturerID' => '4c62506a58149',
    'PartNumber' => '121',
    'PackageQty' => '1',
    'SupplierID' => '4c36d8223e3cf',
    'UnitPrice' => '1',
    'Notes' => '1',
    'lastmodified' => '2012-01-17 14:15:11',
    'by_user' => 'demo',
    'TypeCons' => NULL
))));

$I->seeResponseContainsJson(array('consumables_linked' => array(0 => array(
    'Consumable_linkedID' => '1',
    'ConsumableID' => '1',
    'AssetID' => '4f154ff62df0e',
    'AnnualConsumption' => '100',
    'Notes' => 'Order 2 month before'
))));

$I->seeResponseContainsJson(array('employees' => array(0 => array(
    'EmployeeID' => '0',
    'LoginID' => '3',
    'FirstName' => 'admin',
    'LastName' => 'admin',
    'Position' => NULL,
    'TechnicianYN' => '1',
    'LocationID' => '4b0be83d5d1d7',
    'WorkPhone' => NULL,
    'HandPhone' => NULL,
    'Email' => NULL,
    'Fax' => NULL,
    'Accesslevel' => ' AND (facilities.FacilityID=12)'
))));

$I->seeResponseContainsJson(array('department' => array(3 => array(
    'DeptID' => '4',
    'DepartmentDesc' => 'Allergology'
))));

$I->seeResponseContainsJson(array('essential_equipment' => array(0 => array(
    'EssentialEquipmentID' => '1',
    'FacilityID' => '11',
    'GenericAssetID' => '10134',
    'MinimalQuantity' => '2',
    'Notes' => NULL
))));

$I->seeResponseContainsJson(array('assetgenericname' => array(0 => array(
    'GenericAssetID' => '10134',
    'GenericAssetCode' => '10134',
    'GenericAssetName' => 'Anaesthesia Units',
    'AssetCategoryID' => '5',
    'GenericPicture' => 'gen_images/10134.jpg'
))));

$I->seeResponseContainsJson(array('assetutilization' => array(0 => array(
    'AssetUtilizationID' => '1',
    'AssetUtilizationDesc' => 'Normal'
))));

$I->seeResponseContainsJson(array('assetstatus' => array(0 => array(
    'AssetStatusID' => '1',
    'AssetStatusDesc' => 'Fully functional'
))));

$I->seeResponseContainsJson(array('assetcategory' => array(0 => array(
    'AssetCategoryID' => '1',
    'AssetCategoryNr' => '1',
    'AssetCategoryName' => 'Dental'
))));

$I->seeResponseContainsJson(array('intervention' => array(0 => array(
    'IntervID' => '532c5b53aab95',
    'Date' => '2014-03-21',
    'EmployeeID' => '4d64045c4a525',
    'AssetStatusID' => '1',
    'AssetID_Visit' => '4b67517f4462a',
    'Request_id' => '52fddba460044',
    'FaildPart' => 'Cable',
    'FailurCategID' => '2',
    'FailureCauseID' => '0',
    'Interv_desc' => 'Description Intervention',
    'Interv_desc_eg' => '',
    'Comments' => 'Comments',
    'RespEng' => '4c8fcac9f06fa',
    'TotalWork' => '0',
    'TotalCosts' => '0',
    'lastmodified' => '2014-03-21 16:59:22',
    'by_user' => 'demo'
))));

$I->seeResponseContainsJson(array('request' => array(0 => array(
    'Request_id' => '52fddba460044',
    'Request_date' => '2014-02-14',
    'Request_desc' => 'Repair',
    'Request_desc_eg' => 'Repair',
    'AssetID' => '4b67517f4462a',
    'Request_st_id' => '1',
    'Request_contact_name' => 'Agata Correia',
    'Request_note' => 'Notes',
    'lastmodified' => '2014-02-14 10:02:53',
    'by_user' => 'demo',
    'VisiTpID' => '1'
))));

$I->seeResponseContainsJson(array('request_st' => array(0 => array(
    'Request_st_id' => '1',
    'Request_st_desc' => 'Open'
))));

$I->seeResponseContainsJson(array('warrantycontract' => array(0 => array(
    'WarrantyContractID' => '1',
    'WarrantyContract' => 'Warranty'
))));

$I->seeResponseContainsJson(array('intervention_material' => array(0 => array(
    'MaterialID' => '52f1f98af0a4e',
    'Description' => 'вимаваиіваи',
    'Amount' => '0',
    'PartNumber' => 'апмиіавпиіап',
    'UnitPrice' => '444',
    'lastmodified' => '2014-02-05 09:43:01',
    'by_user' => 'admin',
    'IntervID' => '52f1f91f2cea3'
))));

$I->seeResponseContainsJson(array('intervention_work' => array(0 => array(
    'ActionID' => '52f1f9780e501',
    'IntervID' => '52f1f91f2cea3',
    'Action' => 'fsgnbsfgsfg',
    'Date_action' => '2014-02-05',
    'Time' => '25',
    'lastmodified' => '2014-02-05 09:42:44',
    'by_user' => 'admin'
))));

$I->seeResponseContainsJson(array('visit_type' => array(0 => array(
    'VisiTpID' => '1',
    'VisiTp' => 'Repair',
    'Url' => 'interv_maint_repair.php'
))));








