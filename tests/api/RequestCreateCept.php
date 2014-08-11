<?php 
$I = new ApiTester($scenario);
$I->wantTo('create a request on a asset via POST');

$I->haveHttpHeader('Content-Type', 'application/json;charset=utf-8');
//no whitespaces in content hash!
$I->haveHttpHeader('X-Hash', '9fdcd3d292ce958b3d14c803aba077c965e54ebb1b991fee0d01038c9e8951c5');
$I->haveHttpHeader('X-PublicKey', '248512b6a66f365a4e42f10ed0c854844767b8ca8eb0f74589953991e9f233b6');

$I->sendPOST('asset/4b674c6d9e9ff/request', '{"Request_date":"2014-02-14","Request_desc":"blob","Request_contact_name":"Nicola Keller","Request_note":"somenotes","Request_st_id":"1","VisiTpID":"4"}');

$I->seeResponseCodeIs(201);
$I->seeResponseIsJson();

//we don't test request_id response because its everytime different (PHP's uniqid())
$I->seeResponseContainsJson(array("error" => false));

$I->seeResponseContainsJson(array("message" => "Request added"));

$I->seeInDatabase('request', array("AssetID" => "4b674c6d9e9ff", "Request_contact_name" => "Nicola Keller"));