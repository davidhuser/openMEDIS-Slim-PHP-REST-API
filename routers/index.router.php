<?php
/**
 * GET route to render REST Api Specification in templates folder. For new API versions use different html file.
 *
 */
$app->get('/', function () use ($app) {
    $app->render('api_v1.html');
});

