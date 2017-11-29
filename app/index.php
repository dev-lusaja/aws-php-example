<?php
require 'vendor/autoload.php';
use Aws\Sdk;

$credentials = new Aws\Credentials\Credentials('AKIAIVFQMFY4Z3HI2XRA', 'fCY060Q75oZqwYnny7DVP5q+fMt7rHHuhf2SggLN');

$config = [
    'version'       => 'latest',
    'region'        =>  'us-east-2',
    'credentials'   =>  $credentials
];
$sdk = new Sdk($config);
$client = $sdk->createCloudWatchLogs();
echo '<hr><pre>';
echo 'File :: ' . __FILE__ . ' (Line ' . __LINE__ . ')' . PHP_EOL;
var_dump($client->createLogGroup([
    'logGroupName' => 'test_1', // REQUIRED
    'tags' => ['test' => '1'],
]));
echo '</pre><hr>';
exit();