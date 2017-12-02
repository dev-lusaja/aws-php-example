<?php
namespace CloudWatchLog;

use Aws\Sdk;

class CloudWatchLogExample
{

    /**
     * @var \Aws\CloudWatchLogs\CloudWatchLogsClient|null
     */
    private $client = null;

    /**
     * @var null
     */
    private $nextSequenceToken = null;

    /**
     * @var string
     */
    private $logGroupName = 'logGroup_example';

    /**
     * @var array
     */
    private $tags = ['example' => 'logGroup'];

    /**
     * @var string
     */
    private $logStream = '' ;

    /**
     * @var null
     */
    private static $instance = null;

    /**
     * @param Sdk $sdk
     * @return CloudWatchLogExample
     */
    static function getInstance(Sdk $sdk){
        if (self::$instance == null){
            self::$instance = new self($sdk);
        }
        return self::$instance;
    }

    /**
     * CloudWatchLogExample constructor.
     * @param Sdk $sdk
     */
    public function __construct(Sdk $sdk)
    {
        try {
            $this->client = $sdk->createCloudWatchLogs();
            $this->client->createLogGroup([
                'logGroupName' => $this->logGroupName,
                'tags' => $this->tags,
            ]);

            $this->logStream = date("Y/m/d/") . '[$LATEST]' . md5(date("H:i:s:u"));

            $this->client->createLogStream([
                'logGroupName' => $this->logGroupName,
                'logStreamName' => $this->logStream,
            ]);
        } catch (\Exception $e){
            var_dump($e->getMessage());
        }
    }

    /**
     * @param String $message
     * @return \Aws\Result
     */
    public function putLogs(String $message){
        try{
            $config = [
                'logEvents' => [
                    [
                        'message' => $message,
                        'timestamp' => round(microtime(true) * 1000),
                    ],
                ],
                'logGroupName' => $this->logGroupName,
                'logStreamName' => $this->logStream
            ];

            if ($this->nextSequenceToken){
                array_push($config, ['sequenceToken' => $this->nextSequenceToken]);
            }

            $response = $this->client->putLogEvents($config);
            $this->nextSequenceToken = $response->get('nextSequenceToken');
            return $response;
        } catch (\Exception $e){
            var_dump($e->getMessage());
        }
    }
}