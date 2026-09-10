<?php
include_once 'librerias.php';// Librerias básicas
// These assume you have the associated AWS keys stored in
// the associated system environment variables
$clientPrivateKey = $_ENV['AWS_CLIENT_SECRET_KEY'];
// These two keys are only needed if the delete file feature is enabled
// or if you are, for example, confirming the file size in a successEndpoint
// handler via S3's SDK, as we are doing in this example.
$serverPublicKey = $_ENV['AWS_SERVER_PUBLIC_KEY'];
$serverPrivateKey = $_ENV['AWS_SERVER_PRIVATE_KEY'];

// The following variables are used when validating the policy document
// sent by the uploader.
$expectedBucketName = $_ENV['S3_BUCKET_NAME'];
$expectedHostName = (isset($_ENV['S3_HOST_NAME']) ? $_ENV['S3_HOST_NAME'] : null); // v4-only
// $expectedMaxSize is the value you set the sizeLimit property of the
// validation option. We assume it is `null` here. If you are performing
// validation, then change this to match the integer value you specified
// otherwise your policy document will be invalid.
// http://docs.fineuploader.com/branch/develop/api/options.html#validation-option
$expectedMaxSize = (isset($_ENV['S3_MAX_FILE_SIZE']) ? $_ENV['S3_MAX_FILE_SIZE'] : null);

include_once 's3-functions.php';
