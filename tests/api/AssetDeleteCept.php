<?php 
$I = new ApiTester($scenario);
$I->wantTo('Delete an asset and all its request, interventions and intervention material/work');

$I->haveHttpHeader('Content-Type', 'application/json;charset=utf-8');
$I->haveHttpHeader('X-Hash', 'e651e0f6450f89d82ab0a34c1d421097a635897f5e719179e49263ff145e6ed9');
$I->haveHttpHeader('X-PublicKey', '248512b6a66f365a4e42f10ed0c854844767b8ca8eb0f74589953991e9f233b6');

$I->sendDELETE('asset/4fc629ab08525', '');

$I->seeResponseCodeIs(200);

/**
asset id: 4fc629ab08525
request id: 53d6410fae2a1
intervid: 53d64168daea0
time: 15
amount: 5
unitprice: 20
 */

$I->dontSeeInDatabase('assets', array('AssetID' => '4fc629ab08525'));
$I->dontSeeInDatabase('request', array('AssetID' => '4fc629ab08525'));
$I->dontSeeInDatabase('request', array('Request_id' => '53d6410fae2a1'));
$I->dontSeeInDatabase('intervention', array('AssetID_Visit' => '4b595adc32bbf'));
$I->dontSeeInDatabase('intervention', array('IntervID' => '53d64168daea0'));
$I->dontSeeInDatabase('intervention_work', array('IntervID' => '53d64168daea0'));
$I->dontSeeInDatabase('intervention_material', array('IntervID' => '53d64168daea0'));
