<?php

namespace App\Services\Uploader\Abstract;

use Aws\Result;
use Aws\S3\Exception\S3Exception;
use Aws\S3\S3Client;

abstract class AbstractAwsS3Uploader
{
    public function __construct(
        private readonly string $region,
        private readonly string $key,
        private readonly string $secret,
        private readonly string $bucketName,
    ) {
    }

    public function uploadFileOnS3(string $content, string $fileName): string
    {
        $mimeType = 'application/octet-stream';
        $s3Client = $this->getS3Client();

        $result = $s3Client->putObject([
            'Bucket' => $this->bucketName,
            'Key' => $fileName,
            'Body' => $content,
            'ContentType' => $mimeType,
        ]);

        return $result["ObjectURL"];
    }

    public function getFileOnS3(string $fileName): Result
    {
        $s3Client = $this->getS3Client();

        return $s3Client->getObject([
            'Bucket' => $this->bucketName,
            'Key' => $fileName,
        ]);
    }

    public function fileExistsOnS3(string $fileName): bool
    {
        $s3Client = $this->getS3Client();

        try {
            $s3Client->headObject([
                'Bucket' => $this->bucketName,
                'Key' => $fileName,
            ]);
            return true;
        } catch (S3Exception $e) {
            return false;
        }
    }

    private function getS3Client(): S3Client
    {
        $config = [
            'version' => 'latest',
            'region' => $this->region,
            'credentials' => [
                'key' => $this->key,
                'secret' => $this->secret,
            ],
        ];


        if ($_ENV['APP_ENV'] === 'dev') {
            $config['endpoint'] =$_ENV['AWS_LOCAL_ENDPOINT'];
            $config['use_path_style_endpoint'] = true;
        }

        return new S3Client($config);
    }
}
