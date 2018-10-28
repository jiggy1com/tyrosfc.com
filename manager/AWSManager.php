<?php

class AWSManager
{

    function __construct()
    {

    }

    function uploadFile($obj){

        $client = new \Aws\S3\S3Client([
            'version' => 'latest',
            'region' => Config::REGION,
            'credentials' => [
                'key' => Config::KEY,
                'secret' => Config::SECRET
            ]
        ]);

        $params = [
            'Bucket' => Config::BUCKET,
            'Key' => $obj->filename,
            'SourceFile' => $obj->filepath,
            'MetaData' => []
        ];

        $result = $client->putObject($params);

        $client->waitUntil('ObjectExists', [
            'Bucket' => Config::BUCKET,
            'Key' => $obj->filename
        ]);

        return $result;

    }

}