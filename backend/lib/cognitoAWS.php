<?php

use Aws\Sdk;
use pmill\AwsCognito\CognitoClient;
use pmill\AwsCognito\Exception\CognitoResponseException;
use pmill\AwsCognito\Exception\UserNotFoundException;

class Cognito extends CognitoClient
{
    private $password;
    private $username;
    private $brand_uuid;
    private $config;
    private $log;
    private $cognitoClient;

    public function __construct($email, $password, $brand_uuid)
    {
        global $log;
        $this->log = $log;
        $this->config = AWS;
        $aws = new Sdk($this->config);
        $this->cognitoClient = $aws->createCognitoIdentityProvider();
        parent::__construct($this->cognitoClient);

        $this->username = $email;
        $this->password = $password;
        $this->brand_uuid = $brand_uuid;
        $this->setAppClientId($this->config['app_client_id']);
        $this->setAppClientSecret($this->config['app_client_secret']);
        $this->setRegion($this->config['region']);
        $this->setUserPoolId($this->config['user_pool_id']);
    }

    public function setCognitoUserIfNotExists()
    {
        $has_group = false;

        try {
            $user = $this->getUser($this->username);
            foreach ($this->getUserGroups($user['Username']) as $groups) {
                if (isset($groups['Groups']) && $groups['Groups'] == $this->config['group']) {
                    $has_group = true;
                    break;
                }
            }

            if (!$has_group) {
                $this->addUserToGroup($user['Username'], $this->config['group']);
            }

        } catch (UserNotFoundException $e) {
            $this->setCognitoUser();
        } catch (Exception $e) {
            $this->log->error('Cognito error: ' . $e->getMessage());
        }
    }

    public function setCognitoUser()
    {
        try {
            $sub = $this->registerUser($this->username, $this->password, [
                'custom:brand_uuid' => $this->brand_uuid
            ]);

            $this->addUserToGroup($sub, $this->config['group']);
        } catch (Exception $e) {
            $this->log->error('Cognito error: ' . $e->getMessage());
        }
    }

    /**
     * @param string $username
     * @return AwsResult
     * @throws UserNotFoundException
     * @throws CognitoResponseException
     */
    public function getUser($username)
    {
        try {
            $response = $this->cognitoClient->adminGetUser([
                'Username'   => $username,
                'UserPoolId' => $this->userPoolId,
            ])->toArray();

            return $response;

        } catch (Exception $e) {
            throw CognitoResponseException::createFromCognitoException($e);
        }
    }

    /**
     * @param string $username
     * @return Result
     * @throws UserNotFoundException
     * @throws CognitoResponseException
     */
    public function getUserGroups($username)
    {
        try {
            $response = $this->cognitoClient->adminListGroupsForUser([
                'Username'   => $username,
                'UserPoolId' => $this->userPoolId,
            ])->toArray();

            return $response;

        } catch (Exception $e) {
            throw CognitoResponseException::createFromCognitoException($e);
        }
    }
}
