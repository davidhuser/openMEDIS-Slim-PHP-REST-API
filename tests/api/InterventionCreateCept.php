<?php

$I = new ApiTester($scenario);

$I->wantTo('create new intervention via POST');

$I->haveHttpHeader('Content-Type', 'application/json;charset=utf-8');
//no whitespaces in content hash!
$I->haveHttpHeader('X-Hash', '34756c567620857fac3539130db7c4e7d52162c7c349baeb82f635cf7003716a');
$I->haveHttpHeader('X-PublicKey', '248512b6a66f365a4e42f10ed0c854844767b8ca8eb0f74589953991e9f233b6');

//no whitespaces in json
$I->sendPOST('asset/4b6342489a963/532c5ad50d547/intervention', '{"Date":"2014-01-01","EmployeeID":"4d64045c4a525","AssetStatusID":1,"FaildPart":"Cable","FailurCategID":2,"FailureCauseID":1,"Interv_desc":"cables fixed","Comments":"repeat","RespEng":"4c8fcac9f06fa","TotalWork":1,"TotalCosts":2}');

$I->seeResponseCodeIs(201);
$I->seeResponseIsJson();
$I->seeResponseContainsJson(array("error" => false));
$I->seeResponseContainsJson(array("message" => "Intervention added"));

$I->seeInDatabase('intervention', array('AssetID_Visit'=> '4b6342489a963', "Request_id" => '532c5ad50d547', "EmployeeID" => "4d64045c4a525"));