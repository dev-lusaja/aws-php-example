<?php
require '/app/vendor/autoload.php';
use Aws\Sdk;

try {
    $aws = parse_ini_file('aws.ini');
    $key = $aws['aws_access_key_id'];
    $secret = $aws['aws_secret_access_key'];
    $credentials = new Aws\Credentials\Credentials($key, $secret);

    $config = [
        'version' => 'latest',
        'region' => 'us-east-2',
        'credentials' => $credentials
    ];
    $sdk = new Sdk($config);
    $snsClient = $sdk->createSns();
    /**
     * @var \Aws\Result $response
     */
    $response = $snsClient->createTopic(['Name' => 'topic_test']);
    $topicArn = $response->get('TopicArn');
    $response = $snsClient->publish([
        'Message' => json_encode(['metadata' => 'test test']), // REQUIRED
        'MessageStructure' => '<string>',
        'Subject' => 'Test',
        'TopicArn' => $topicArn,
    ]);
    echo 'Message ID: ' . $response->get('MessageId') . PHP_EOL;
} catch (Exception $e){
    echo '<hr><pre>';
    echo 'File :: ' . __FILE__ . ' (Line ' . __LINE__ . ')' . PHP_EOL;
    var_dump($e->getMessage());
    echo '</pre><hr>';
    exit();
}
