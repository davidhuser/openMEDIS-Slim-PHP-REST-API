<?php

$I = new ApiTester($scenario);

$I->wantTo('create new intervention material via POST');

$I->haveHttpHeader('Content-Type', 'application/json;charset=utf-8');
//no whitespaces in content hash!
$I->haveHttpHeader('X-Hash', '604f063497b262f82851998915b85ad16502c8f1a4aa85e7b2fac4f04038bc0f');
$I->haveHttpHeader('X-PublicKey', '248512b6a66f365a4e42f10ed0c854844767b8ca8eb0f74589953991e9f233b6');

//no whitespaces in json
$I->sendPOST('intervention/material', '{"Description":"test","Amount":10,"PartNumber":"abc","UnitPrice":5.0,"IntervID":"532c5b53aab95"}');

$I->seeResponseCodeIs(201);
$I->seeResponseIsJson();
$I->seeResponseContainsJson(array("error" => false));
$I->seeResponseContainsJson(array("message" => "Intervention material created"));

$I->seeInDatabase('intervention_material', array("IntervID" => "532c5b53aab95", "Description" => "test", "Amount" => 10));