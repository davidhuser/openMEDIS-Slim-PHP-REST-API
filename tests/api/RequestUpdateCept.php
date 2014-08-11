<?php
$I = new ApiTester($scenario);

$I->wantTo('update an existing request via PUT');

$I->haveHttpHeader('Content-Type', 'application/json;charset=utf-8');
// no whitespaces in content hash!
$I->haveHttpHeader('X-Hash', '517c64ce66ec1129c94024f9cccab3762776e8c4eb9642543237abebc8c0ce98');
$I->haveHttpHeader('X-PublicKey', '248512b6a66f365a4e42f10ed0c854844767b8ca8eb0f74589953991e9f233b6');

// no whitespaces in json
$I->sendPUT('asset/4b674c6d9e9ff/532c5b331baf3', '{"Request_date":"2014-02-14","Request_desc":"blub","Request_contact_name":"democontact","Request_note":"somenotes","Request_st_id":"1","VisiTpID":"4"}');

$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();

$I->seeInDatabase('request', array("AssetID" => "4b674c6d9e9ff", "Request_id" => "532c5b331baf3", "Request_contact_name" => "democontact"));
