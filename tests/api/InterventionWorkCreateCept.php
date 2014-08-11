<?php

$I = new ApiTester($scenario);

$I->wantTo('create new intervention work via POST');

$I->haveHttpHeader('Content-Type', 'application/json;charset=utf-8');
$I->haveHttpHeader('X-Hash', 'f6ddc80ba5123475c102ba654f5092e130df09bcec335e503db7757468cb7428');
$I->haveHttpHeader('X-PublicKey', '248512b6a66f365a4e42f10ed0c854844767b8ca8eb0f74589953991e9f233b6');

//no whitespaces in json
$I->sendPOST('intervention/work', '{"IntervID":"532c5b53aab95","Action":"yesitis","Date_action":"2014-01-01","Time":20}');

$I->seeResponseCodeIs(201);
$I->seeResponseIsJson();
$I->seeResponseContainsJson(array("error" => false));
$I->seeResponseContainsJson(array("message" => "Intervention work created"));

$I->seeInDatabase('intervention_work', array("IntervID" => "532c5b53aab95", "Action" => "yesitis"));