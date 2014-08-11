<?php
$I = new ApiTester($scenario);

$I->wantTo('get json via database/scheme');
$I->haveHttpHeader('X-PublicKey', '248512b6a66f365a4e42f10ed0c854844767b8ca8eb0f74589953991e9f233b6');
$I->haveHttpHeader('X-Hash', 'e651e0f6450f89d82ab0a34c1d421097a635897f5e719179e49263ff145e6ed9');
$I->sendGET('database/scheme');
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();
$I->seeHttpHeader('Content-Type', 'application/json;charset=utf-8');

// we check not every field, just assets, location and facilities.

//automatic counter for array index
$i = -1;

$I->seeResponseContainsJson(array('assets' => array(++$i => array(
    'Field' => 'AssetID',
    'Type' => 'char(13)',
    'Null' => 'NO',
    'Key' => 'PRI',
    'Default' => NULL,
    'Extra' => ''
))));

$I->seeResponseContainsJson(array('assets' => array(++$i => array(
    'Field' => 'GenericAssetID',
    'Type' => 'int(10)',
    'Null' => 'YES',
    'Key' => 'MUL',
    'Default' => NULL,
    'Extra' => '',
))));

$I->seeResponseContainsJson(array('assets' => array(++$i => array(
    'Field' => 'UMDNS',
    'Type' => 'int(10)',
    'Null' => 'YES',
    'Key' => '',
    'Default' => NULL,
    'Extra' => '',
))));

$I->seeResponseContainsJson(array('assets' => array(++$i => array(
    'Field' => 'AssetFullName',
    'Type' => 'varchar(255)',
    'Null' => 'YES',
    'Key' => '',
    'Default' => NULL,
    'Extra' => '',
))));

$I->seeResponseContainsJson(array('assets' => array(++$i => array(
    'Field' => 'ManufacturerID',
    'Type' => 'char(13)',
    'Null' => 'YES',
    'Key' => 'MUL',
    'Default' => NULL,
    'Extra' => '',
))));

$I->seeResponseContainsJson(array('assets' => array(++$i => array(
    'Field' => 'Model',
    'Type' => 'varchar(255)',
    'Null' => 'YES',
    'Key' => '',
    'Default' => NULL,
    'Extra' => '',
))));

$I->seeResponseContainsJson(array('assets' => array(++$i => array(
    'Field' => 'SerialNumber',
    'Type' => 'varchar(255)',
    'Null' => 'YES',
    'Key' => '',
    'Default' => NULL,
    'Extra' => '',
))));

$I->seeResponseContainsJson(array('assets' => array(++$i => array(
    'Field' => 'InternalIventoryNumber',
    'Type' => 'varchar(255)',
    'Null' => 'YES',
    'Key' => '',
    'Default' => NULL,
    'Extra' => '',
))));

$I->seeResponseContainsJson(array('assets' => array(++$i => array(
    'Field' => 'LocationID',
    'Type' => 'char(13)',
    'Null' => 'YES',
    'Key' => 'MUL',
    'Default' => NULL,
    'Extra' => '',
))));

$I->seeResponseContainsJson(array('assets' => array(++$i => array(
    'Field' => 'ResponsiblePers',
    'Type' => 'varchar(50)',
    'Null' => 'YES',
    'Key' => '',
    'Default' => NULL,
    'Extra' => '',
))));

$I->seeResponseContainsJson(array('assets' => array(++$i => array(
    'Field' => 'AssetStatusID',
    'Type' => 'int(10)',
    'Null' => 'YES',
    'Key' => 'MUL',
    'Default' => NULL,
    'Extra' => '',
))));

$I->seeResponseContainsJson(array('assets' => array(++$i => array(
    'Field' => 'AssetUtilizationID',
    'Type' => 'int(10)',
    'Null' => 'YES',
    'Key' => 'MUL',
    'Default' => NULL,
    'Extra' => '',
))));

$I->seeResponseContainsJson(array('assets' => array(++$i => array(
    'Field' => 'PurchaseDate',
    'Type' => 'date',
    'Null' => 'YES',
    'Key' => '',
    'Default' => NULL,
    'Extra' => '',
))));

$I->seeResponseContainsJson(array('assets' => array(++$i => array(
    'Field' => 'InstallationDate',
    'Type' => 'date',
    'Null' => 'YES',
    'Key' => '',
    'Default' => NULL,
    'Extra' => '',
))));

$I->seeResponseContainsJson(array('assets' => array(++$i => array(
    'Field' => 'Lifetime',
    'Type' => 'int(11)',
    'Null' => 'YES',
    'Key' => '',
    'Default' => NULL,
    'Extra' => '',
))));
$I->seeResponseContainsJson(array('assets' => array(++$i => array(
    'Field' => 'PurchasePrice',
    'Type' => 'double(24,0)',
    'Null' => 'YES',
    'Key' => '',
    'Default' => NULL,
    'Extra' => '',
))));

$I->seeResponseContainsJson(array('assets' => array(++$i => array(
    'Field' => 'CurrentValue',
    'Type' => 'double(24,0)',
    'Null' => 'YES',
    'Key' => '',
    'Default' => NULL,
    'Extra' => '',
))));

$I->seeResponseContainsJson(array('assets' => array(++$i => array(
    'Field' => 'WarrantyContractID',
    'Type' => 'int(10)',
    'Null' => 'YES',
    'Key' => 'MUL',
    'Default' => NULL,
    'Extra' => '',
))));

$I->seeResponseContainsJson(array('assets' => array(++$i => array(
    'Field' => 'AgentID',
    'Type' => 'char(13)',
    'Null' => 'NO',
    'Key' => 'MUL',
    'Default' => NULL,
    'Extra' => '',
))));

$I->seeResponseContainsJson(array('assets' => array(++$i => array(
    'Field' => 'WarrantyContractExp',
    'Type' => 'date',
    'Null' => 'YES',
    'Key' => '',
    'Default' => NULL,
    'Extra' => '',
))));

$I->seeResponseContainsJson(array('assets' => array(++$i => array(
    'Field' => 'WarrantyContractNotes',
    'Type' => 'text',
    'Null' => 'YES',
    'Key' => '',
    'Default' => NULL,
    'Extra' => '',
))));

$I->seeResponseContainsJson(array('assets' => array(++$i => array(
    'Field' => 'EmployeeID',
    'Type' => 'char(13)',
    'Null' => 'NO',
    'Key' => 'MUL',
    'Default' => NULL,
    'Extra' => '',
))));

$I->seeResponseContainsJson(array('assets' => array(++$i => array(
    'Field' => 'SupplierID',
    'Type' => 'char(13)',
    'Null' => 'YES',
    'Key' => 'MUL',
    'Default' => NULL,
    'Extra' => '',
))));

$I->seeResponseContainsJson(array('assets' => array(++$i => array(
    'Field' => 'DonorID',
    'Type' => 'char(13)',
    'Null' => 'YES',
    'Key' => 'MUL',
    'Default' => NULL,
    'Extra' => '',
))));

$I->seeResponseContainsJson(array('assets' => array(++$i => array(
    'Field' => 'ServiceManual',
    'Type' => 'varchar(100)',
    'Null' => 'NO',
    'Key' => '',
    'Default' => NULL,
    'Extra' => '',
))));

$I->seeResponseContainsJson(array('assets' => array(++$i => array(
    'Field' => 'Notes',
    'Type' => 'text',
    'Null' => 'YES',
    'Key' => '',
    'Default' => NULL,
    'Extra' => '',
))));

$I->seeResponseContainsJson(array('assets' => array(++$i => array(
    'Field' => 'Picture',
    'Type' => 'varchar(255)',
    'Null' => 'YES',
    'Key' => '',
    'Default' => NULL,
    'Extra' => '',
))));

$I->seeResponseContainsJson(array('assets' => array(++$i => array(
    'Field' => 'lastmodified',
    'Type' => 'timestamp',
    'Null' => 'YES',
    'Key' => '',
    'Default' => 'CURRENT_TIMESTAMP',
    'Extra' => '',
))));

$I->seeResponseContainsJson(array('assets' => array(++$i => array(
    'Field' => 'by_user',
    'Type' => 'varchar(25)',
    'Null' => 'YES',
    'Key' => '',
    'Default' => NULL,
    'Extra' => '',
))));

$I->seeResponseContainsJson(array('assets' => array(++$i => array(
    'Field' => 'URL_Manual',
    'Type' => 'varchar(255)',
    'Null' => 'YES',
    'Key' => '',
    'Default' => NULL,
    'Extra' => '',
))));

$I->seeResponseContainsJson(array('assets' => array(++$i => array(
    'Field' => 'MetrologyDocument',
    'Type' => 'varchar(255)',
    'Null' => 'YES',
    'Key' => '',
    'Default' => NULL,
    'Extra' => '',
))));

$I->seeResponseContainsJson(array('assets' => array(++$i => array(
    'Field' => 'MetrologyDate',
    'Type' => 'date',
    'Null' => 'YES',
    'Key' => '',
    'Default' => NULL,
    'Extra' => '',
))));

$I->seeResponseContainsJson(array('assets' => array(++$i => array(
    'Field' => 'Metrology',
    'Type' => 'tinyint(1)',
    'Null' => 'YES',
    'Key' => '',
    'Default' => NULL,
    'Extra' => '',
))));

//-- location

$j = -1;

$I->seeResponseContainsJson(array('location' => array(++$j => array(
    'Field' => 'LocationID',
    'Type' => 'char(13)',
    'Null' => 'NO',
    'Key' => 'PRI',
    'Default' => NULL,
    'Extra' => '',
))));

$I->seeResponseContainsJson(array('location' => array(++$j => array(
    'Field' => 'FacilityID',
    'Type' => 'int(10)',
    'Null' => 'YES',
    'Key' => 'MUL',
    'Default' => NULL,
    'Extra' => '',
))));

$I->seeResponseContainsJson(array('location' => array(++$j => array(
    'Field' => 'DeptID',
    'Type' => 'int(10)',
    'Null' => 'YES',
    'Key' => 'MUL',
    'Default' => NULL,
    'Extra' => '',
))));

$I->seeResponseContainsJson(array('location' => array(++$j => array(
    'Field' => 'Roomnb',
    'Type' => 'varchar(10)',
    'Null' => 'YES',
    'Key' => '',
    'Default' => NULL,
    'Extra' => '',
))));

$I->seeResponseContainsJson(array('location' => array(++$j => array(
    'Field' => 'Floor',
    'Type' => 'varchar(10)',
    'Null' => 'YES',
    'Key' => '',
    'Default' => NULL,
    'Extra' => '',
))));

$I->seeResponseContainsJson(array('location' => array(++$j => array(
    'Field' => 'Building',
    'Type' => 'varchar(255)',
    'Null' => 'YES',
    'Key' => '',
    'Default' => NULL,
    'Extra' => '',
))));


$I->seeResponseContainsJson(array('location' => array(++$j => array(
    'Field' => 'NotetoTech',
    'Type' => 'text',
    'Null' => 'YES',
    'Key' => '',
    'Default' => NULL,
    'Extra' => '',
))));

//-- facilities

$k = -1;
$I->seeResponseContainsJson(array('facilities' => array(++$k => array(
    'Field' => 'FacilityID',
    'Type' => 'int(10)',
    'Null' => 'NO',
    'Key' => 'PRI',
    'Default' => NULL,
    'Extra' => 'auto_increment',
))));

$I->seeResponseContainsJson(array('facilities' => array(++$k => array(
    'Field' => 'DistrictID',
    'Type' => 'int(10)',
    'Null' => 'YES',
    'Key' => 'MUL',
    'Default' => NULL,
    'Extra' => '',
))));

$I->seeResponseContainsJson(array('facilities' => array(++$k => array(
    'Field' => 'FacilityName',
    'Type' => 'varchar(255)',
    'Null' => 'YES',
    'Key' => '',
    'Default' => NULL,
    'Extra' => '',
))));