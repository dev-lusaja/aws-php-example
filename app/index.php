<?php
require 'vendor/autoload.php';

use Aws\Sdk;
use CloudWatchLog\CloudWatchLogExample;

$config = [
    'profile' => 'default',
    'version' => 'latest',
    'region' => 'us-east-2'
];
$sdk = new Sdk($config);

$cloudWatchLogs = CloudWatchLogExample::getInstance($sdk);
var_dump($cloudWatchLogs->putLogs("first log"));
var_dump($cloudWatchLogs->putLogs("second log"));