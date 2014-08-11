<?php
$I = new ApiTester($scenario);

$I->wantTo('update an existing intervention via PUT');

$I->haveHttpHeader('Content-Type', 'application/json;charset=utf-8');
// no whitespaces in content hash!
$I->haveHttpHeader('X-Hash', '8fa6f078389516253c2ca6ebdf4b37209721bd64eab99242ef51a9368b957d9e');
$I->haveHttpHeader('X-PublicKey', '248512b6a66f365a4e42f10ed0c854844767b8ca8eb0f74589953991e9f233b6');

// no whitespaces in json
$I->sendPUT('asset/4b67517f4462a/52fddba460044/532c5b53aab95', '{"Date":"2014-01-01","EmployeeID":"4d64045c4a525","AssetID_Visit":"4b67517f4462a","AssetStatusID":1,"FaildPart":"Cable Red","FailurCategID":2,"FailureCauseID":1,"Interv_desc":"blub","Comments":"yes","RespEng":"4c8fcac9f06fa","TotalWork":1,"TotalCosts":2}');

$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();
$I->seeResponseContainsJson(array("error" => false));
$I->seeResponseContainsJson(array("message" => "Intervention updated"));

$I->seeInDatabase('intervention', array("IntervID" => "532c5b53aab95", "AssetID_Visit" =>"4b67517f4462a", "Date" => "2014-01-01","EmployeeID" => "4d64045c4a525", "RespEng" => "4c8fcac9f06fa"));