<?php

require_once __DIR__ . "/../vendor/autoload.php";

$app = new \SlimApi\Application();

try {
    $app->runSlimApi();
} catch (\Exception $e) {
    echo 'Issue faced running the api:' . $e->getMessage() . PHP_EOL;
}
