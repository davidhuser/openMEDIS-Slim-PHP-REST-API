<?php 
$I = new ApiTester($scenario);
$I->wantTo('Get a 404 when I want to delete an asset that does not exist');

$I->haveHttpHeader('Content-Type', 'application/json;charset=utf-8');
$I->haveHttpHeader('X-Hash', 'e651e0f6450f89d82ab0a34c1d421097a635897f5e719179e49263ff145e6ed9');
$I->haveHttpHeader('X-PublicKey', '248512b6a66f365a4e42f10ed0c854844767b8ca8eb0f74589953991e9f233b6');

$I->sendDELETE('asset/xyz123456', '');

$I->seeResponseCodeIs(404);