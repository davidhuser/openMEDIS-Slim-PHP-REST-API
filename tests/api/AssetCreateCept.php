<?php

$I = new ApiTester($scenario);

$I->wantTo('create new asset via POST');

$I->haveHttpHeader('Content-Type', 'application/json;charset=utf-8');
//no whitespaces in content hash!
$I->haveHttpHeader('X-Hash', '6fb6b38ce5277f1fdfce13e8aed07ccc5421c67c01d0580894c3f067503138ae');
$I->haveHttpHeader('X-PublicKey', '248512b6a66f365a4e42f10ed0c854844767b8ca8eb0f74589953991e9f233b6');

//no whitespaces in json
$I->sendPOST('asset', '{"GenericAssetID":12636,"UMDNS":12636,"AssetFullName":"Dash","ManufacturerID":"4c44276c3c2c0","Model":"4000 ","SerialNumber":"SD008484463GA","InternalIventoryNumber":"01382928","LocationID":"4b6b4f5120321","ResponsiblePers":null,"AssetStatusID":"1","AssetUtilizationID":"1","PurchaseDate":null,"InstallationDate":null,"Lifetime":10,"PurchasePrice":131174,"CurrentValue":"104939","WarrantyContractID":"1","AgentID":"4c90677ca7db7","WarrantyContractExp":null,"WarrantyContractNotes":null,"EmployeeID":"4cb6b8bbb9df6","SupplierID":"4b595a7124c8b","DonorID":"4c9066fc81b53","ServiceManual":"","Notes":null,"Picture":"gen_images/12636.jpg","by_user":"demo","URL_Manual":null,"MetrologyDocument":null,"MetrologyDate":null,"Metrology":"0"}');

$I->seeResponseCodeIs(201);
$I->seeResponseIsJson();
$I->seeResponseContainsJson(array(
    "error" => false,
    "message" => "Asset created"
));

$I->seeInDatabase('assets', array('GenericAssetID'=> '12636', "AssetFullName" => 'Dash'));