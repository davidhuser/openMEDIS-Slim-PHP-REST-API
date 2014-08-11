<?php

//has to be called first (thus 1LoginCept.php) because of the private keys after sql file import.

$I = new ApiTester($scenario);
$I->wantTo('login via POST');
$I->haveHttpHeader('Content-Type', 'application/json;charset=utf-8');
$I->haveHttpHeader('Accept','application/json;charset=utf-8');
$I->sendPOST('login', json_encode(array('username' => 'demo', 'password' => 'demo')));
$I->seeResponseCodeIs(200);
$I->seeResponseIsJson();
$I->seeResponseContainsJson(array(
    "error" => false,
    "login_id" => 123,
    "username" => "demo",
    "group_id" => 3,
    "locale" => "en",
    "public_key" => "248512b6a66f365a4e42f10ed0c854844767b8ca8eb0f74589953991e9f233b6",
    "access_level" => " AND (facilities.FacilityID = 11 OR facilities.FacilityID = 14 OR facilities.FacilityID = 13 OR facilities.FacilityID = 17 OR facilities.FacilityID = 16 OR facilities.FacilityID = 15 OR facilities.FacilityID = 12)"
));