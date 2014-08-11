<?php 
$I = new ApiTester($scenario);
$I->wantTo('check any route with wrong authentication header values');
$I->haveHttpHeader('X-PublicKey', 'somewrongkey2234');
$I->haveHttpHeader('X-Hash', 'somewronghash3322');
$I->sendGET('database/export');
$I->seeResponseCodeIs(401);
