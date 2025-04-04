<?php

namespace App\Services\Uploader;

use App\Services\Uploader\Abstract\AbstractAwsS3Uploader;

class DataAwsS3Uploader extends AbstractAwsS3Uploader
{
    public function __construct()
    {
        parent::__construct(
            $_ENV['AWS_REGION'],
            $_ENV['AWS_ACCESS_KEY_ID'],
            $_ENV['AWS_SECRET_ACCESS_KEY'],
            $_ENV['AWS_BUCKET_NAME']
        );
    }
}
