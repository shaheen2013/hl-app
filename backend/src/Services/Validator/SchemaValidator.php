<?php

class SchemaValidator 
{
    private $validator;
    private $connection;
    private $schemaTable;

    public function __construct($validator, $connection, $schemaTable)
    {
        $this->validator = $validator;
        $this->connection = $connection;
        $this->schemaTable = $schemaTable;
    }

    /**
     * This function will recover the schema pattern thanks to the connection used in the constructor passing the appropriate
     * values:
     * 1)$schemaTable: table where is stored all the schemas
     * 2)$primaryKeyName: Key name that will be used to found the specific schema
     * 3)$primaryKeyValue: Primary key Value that will be used to found the specific schema
     * $)$schemaColumn: Values from the search that we want to recover
     *
     * then will use the function validate from the instance given into the constructor, passing the schema pattern ($schema)
     * and the object that we want to validate ($payload). It will return true if is valid or throw SchemaNotValidException in
     * case it isn't.
     *
     * @param $primaryKeyValue
     * @param $payload
     * @param string $primaryKeyName
     * @return bool
     */
    public function validate($primaryKeyValue, $payload, $primaryKeyName = 'id')
    {
        $schema = $this->connection->sendRequest($this->schemaTable, $primaryKeyValue, $primaryKeyName);
        return $this->validator->validate($schema, $payload);
    }
}
