<?php

use App\Exceptions\SchemaNotValidException;
use Aws\Credentials\Credentials;
use GuzzleHttp\Client;
use GuzzleHttp\Promise;
use GuzzleHttp\Psr7\Request;
use GuzzleHttp\Exception\ServerException;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;
use GuzzleHttp\Psr7\Query;
use Aws\Signature\SignatureV4;

class ApiGatewayConnection
{

    private $schemaValidator;
    private $responseAll = false;

    public function __construct($schemaValidator = null)
    {
        $this->schemaValidator = $schemaValidator;
    }

    /**
     * Send request to amazon gateway
     *
     * @param array $payload
     * @param string $path
     * @param string $method
     * @param array $headers
     *
     * @return \Psr\Http\Message\StreamInterface
     *
     * @throws SchemaNotValidException
     * @throws GuzzleHttp\Exception\ClientException
     * @throws GuzzleHttp\Exception\ServerException
     * @throws Exception
     */
    public function sendRequest($payload, $path, $method, $headers = [])
    {
        global $log;

        $config = AWS;
        $credentials = new Credentials($config['credentials']['key'], $config['credentials']['secret']);
        list($query, $payloadParse) = $this->parseRequest($payload, $method);

        if ($path == STREAM_SUB_DOMAIN) {
            $this->validateRequest(array_get($payload, 'schema'), $payload);
        }

        // Add headers to request, the content-type will be replaced with the passed in the parameters
        $headers = array_merge(['Content-Type' => 'application/json'], $headers);

        $client = new Client(['verify' => ENV === "test" ? false : true]);
        $request = new Request($method, $config['api_gateway'] . $path . $query, $headers, $payloadParse);
        $s4 = new SignatureV4("execute-api", $config['region']);
        $signedrequest = $s4->signRequest($request, $credentials);

        try {
            $response = $client->send($signedrequest);
            return $this->responseAll ? $response : $response->getBody();
        } catch (ClientException $e) { // 4xx errors
            // Get request
            $request = $e->getRequest();
            // Get response
            $response = $e->getResponse();
            $statusCode = $response->getStatusCode();
            // Content to be merged in error log
            $logContent = ['status' => $statusCode, 'response' => $response, 'request' => $request];
        } catch (ServerException $e) { // 5xx errros
            // Get request
            $request = $e->getRequest();
            // Get response
            $response = $e->getResponse();
            $statusCode = $response->getStatusCode();
            // Content to be merged in error log
            $logContent = ['status' => $statusCode, 'response' => $response, 'request' => $request];
        } catch (ConnectException $e) { // Not responses (example: timeout)
            // Get request
            $request = $e->getRequest();
            // Get response
            $response = null;
            $statusCode = '50x';
            // Content to be merged in error log
            $logContent = ['status' => $statusCode, 'response' => $response, 'request' => $request];
        } catch (\Exception $e) {
            // Content to be merged in error log
            $logContent = [];
        }
        // Log error
        $log->error("GUZZLE AWS ERROR: ", array_merge(['exception' => get_class($e), 'error' => $e->getMessage()], $logContent));
        throw $e;
    }

    /**
     * Send async request to amazon gateway
     *
     * @param array $requests
     * @param array $requests
     * @param string $method
     * @param array $headers
     *
     * @return \Psr\Http\Message\StreamInterface
     *
     */
    public function sendAsyncRequests($requests, $headers = [])
    {
        $config = AWS;
        $credentials = new Credentials($config['credentials']['key'], $config['credentials']['secret']);

        $client = new Client(['verify' => ENV === "test" ? false : true]);
        $signedrequests = [];

        foreach ($requests as $key => $request) {
            list($query, $payloadParse) = $this->parseRequest(array_get($request, 'payload'), array_get($request, 'method'));

            // Add headers to request, the content-type will be replaced with the passed in the parameters
            $headers = array_merge(['Content-Type' => 'text/json'], $headers);

            $request = new Request(array_get($request, 'method', 'GET'), $config['api_gateway'] . array_get($request, 'endpoint') . $query, $headers, $payloadParse);
            $s4 = new SignatureV4("execute-api", $config['region']);
            $signedrequest = $s4->signRequest($request, $credentials);

            $signedrequests[$key] = $client->sendAsync($signedrequest);
        }

        return $signedrequests;
    }

    /**
     * Await from async promises
     *
     * @param array $promises
     *
     * @return \Psr\Http\Message\StreamInterface
     *
     */
    public function awaitAsyncRequests($promises, $requests=null, $retries = 0)
    {
        $results = Promise\Utils::settle($promises)->wait();

        // Retry the rejected endpoints until it works or reaches the maximum retries.
        if ($requests && $retries) {
            $attempts = 0;
    
            while ($attempts < $retries) {
                $failedPromises = [];

                foreach ($results as $key => $result) {
                    if ($result['state'] === 'rejected') {
                        $failedPromises[$key] = $this->sendAsyncRequests([$key => $requests[$key]])[$key];
                    }
                }

                if (empty($failedPromises)) {
                    break; // Exit loop if no failed promises
                }

                // Wait for the failed promises to settle
                $results = array_merge($results, Promise\Utils::settle($failedPromises)->wait());

                $attempts++;
            }
        }
        

        return $results;
    }

    public function setResponseAll($status)
    {
        $this->responseAll = $status;
    }

    /**
     * @param $keyValue
     * @param $payload
     * @return bool
     * @throws SchemaNotValidException
     */
    private function validateRequest($keyValue, $payload)
    {
        if ($this->schemaValidator) {
            return  $this->schemaValidator->validate($keyValue, $payload);
        }
        return true;
    }

    /**
     * @param $payload
     * @param $method
     * @return array
     */
    private function parseRequest($payload, $method)
    {
        $query = "";
        $payloadParse = empty($payload) ? null : json_encode($payload);

        if (strtolower($method) == 'get' && !empty($payload)) {
            $query = '?' . Query::build($payload);
            $payloadParse = null;
        }

        return array($query, $payloadParse);
    }
}
