<?php
$I = new ApiTester($scenario);

$I->wantTo('create new asset with a missing parameter via POST');

$I->haveHttpHeader('Content-Type', 'application/json;charset=utf-8');
// no whitespaces in content hash!
$I->haveHttpHeader('X-Hash', '1ed208ed3103ce3e2d238cadca560a68be860773e7b8a4f5feab3ee0e30b4156');
$I->haveHttpHeader('X-PublicKey', '248512b6a66f365a4e42f10ed0c854844767b8ca8eb0f74589953991e9f233b6');

// no whitespaces in json
// Missing: SerialNumber field
$I->sendPOST('asset', '{"GenericAssetID":12636,"UMDNS":12636,"AssetFullName":"Dash","ManufacturerID":"4c44276c3c2c0","Model":"4000 ","InternalIventoryNumber":"01382928","LocationID":"4b6b4f5120321","ResponsiblePers":null,"AssetStatusID":"1","AssetUtilizationID":"1","PurchaseDate":null,"InstallationDate":null,"Lifetime":10,"PurchasePrice":131174,"CurrentValue":"104939","WarrantyContractID":"1","AgentID":"4c90677ca7db7","WarrantyContractExp":null,"WarrantyContractNotes":null,"EmployeeID":"4cb6b8bbb9df6","SupplierID":"4b595a7124c8b","DonorID":"4c9066fc81b53","ServiceManual":"","Notes":null,"Picture":"gen_images/12636.jpg","by_user":"demo","URL_Manual":null,"MetrologyDocument":null,"MetrologyDate":null,"Metrology":"0"}');

$I->seeResponseCodeIs(400);
$I->seeResponseIsJson();
$I->seeResponseContainsJson(array(
    "error" => true,
    "message" => "Required field SerialNumber missing, empty or null"
));