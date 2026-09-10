<?php

use App\Exceptions\SchemaNotValidException;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use JsonSchema\Constraints\Factory;
use JsonSchema\SchemaStorage;
use JsonSchema\Validator;

class JsonSchemaValidator
{
    /**
     * If the $payload is validated against the $schema pattern this functions will return true, otherwise:
     * 1)if no schema pattern is passed throw schema error
     * 2)if validation fails: delete stored schema from cache and throw schema error
     * @param $schema
     * @param $payload
     * @return bool
     * @throws SchemaNotValidException
     */
    public function validate($schema, $payload)
    {
        $schemaName = array_get($payload, 'schema');

        if (!$schema) {
            throw new SchemaNotValidException("Schema $schemaName not found");
        }

        $jsonSchemaObject = json_decode($schema);

        $schemaStorage = new SchemaStorage();
        $jsonValidator = new Validator(new Factory($schemaStorage));
        $jsonToValidateObject = json_decode(json_encode($payload));

        $jsonValidator->validate($jsonToValidateObject, $jsonSchemaObject);

        if (!$jsonValidator->isValid()) {
            global $log;
            $keyName = str_replace("/", "-", $schemaName) . '- schema_name';
            deleteCacheByKey($keyName);
            $log->error('Schema not valid', ['valid' => $jsonValidator->isValid(), 'error' => $jsonValidator->getErrors(), 'payload' => $payload, 'schema' => $schema]);
            throw new SchemaNotValidException("Schema $schemaName not valid");
        } 

        return true;
    }
}
