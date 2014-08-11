<?php
$I = new ApiTester($scenario);

$I->wantTo('update an existing asset via PUT');

$I->haveHttpHeader('Content-Type', 'application/json;charset=utf-8');
// no whitespaces in content hash!
$I->haveHttpHeader('X-Hash', '5bb14dac8d49806a4f10bc24aff299b33c1f229eed775e7784b3e7a2888b593f');
$I->haveHttpHeader('X-PublicKey', '248512b6a66f365a4e42f10ed0c854844767b8ca8eb0f74589953991e9f233b6');

// no whitespaces in json
$I->sendPUT('asset/4b0beb98ce160', '{"AgentID":"99999","AssetFullName":"TAPPETO T2100ee","AssetStatusID":"1","AssetUtilizationID":"1","CurrentValue":"null","DonorID":"4cf67d984d94f","EmployeeID":"4d64045c4a525","GenericAssetID":"18427","InstallationDate":"2014-7-19","InternalIventoryNumber":"1234567890","Lifetime":"14","LocationID":"4d1481c1a54c5","ManufacturerID":"4d6f38daa55e4","Metrology":"0","MetrologyDate":"2014-7-19","MetrologyDocument":"null","Model":"T2100","Notes":"null","Picture":"null","PurchaseDate":"2013-6-20","PurchasePrice":"10000","ResponsiblePers":"null","SerialNumber":"SAS2345678WA","ServiceManual":"null","SupplierID":"4ce525f47e550","UMDNS":"14141","URL_Manual":"null","WarrantyContractExp":"2014-6-30","WarrantyContractID":"1","WarrantyContractNotes":"null","by_user":"null","lastmodified":"null"}');

$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();

$I->seeInDatabase('assets', array('AgentID'=> '99999', "AssetFullName" => 'TAPPETO T2100ee', "AssetID" => "4b0beb98ce160"));