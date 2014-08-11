<?php
$I = new ApiTester($scenario);
$I->wantTo('check login via POST with wrong credentials');
$I->haveHttpHeader('Content-Type', 'application/json;charset=utf-8');
$I->haveHttpHeader('Accept','application/json;charset=utf-8');
$I->sendPOST('login', json_encode(array('username' => 'demo', 'password' => 'somewrongpassword1234')));
$I->seeResponseCodeIs(401);
$I->seeResponseIsJson();
$I->seeResponseContainsJson(array(
    'error' => true,
    'message' => "Login failed. Incorrect credentials"
));