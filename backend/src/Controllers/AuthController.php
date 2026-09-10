<?php

namespace App\Controllers;

use Psr\Container\ContainerInterface;
use Slim\Routing\RouteContext;
use Aws\CognitoIdentityProvider\Exception\CognitoIdentityProviderException;

class ChallengeException {
    private $challengeName;
    private $session;

    public function __construct($challengeName, $session) {
        $this->challengeName = $challengeName;
        $this->session = $session;
    }

    public function getChallengeName() {
        return $this->challengeName;
    }

    public function getSession() {
        return $this->session;
    }
}
class AuthController
{
    // Constants to replace CognitoClient constants
    const CHALLENGE_NEW_PASSWORD_REQUIRED = 'NEW_PASSWORD_REQUIRED';
    const CHALLENGE_SMS_MFA = 'SMS_MFA';
    const CHALLENGE_SOFTWARE_TOKEN_MFA = 'SOFTWARE_TOKEN_MFA';

    protected $container;
    protected $response;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public function __invoke($request, $response)
    {
        if ($request->getMethod() === 'GET') {
            $this->response = $response;
            return $this->redirectToAuth();
        }
    }

    private function redirectToAuth($args=[], $flash=[], $response=null)
    {
        $view = $this->container->get('view');
        $view->addData([
            'title' => 'Hotelinking - Login'
        ]);

        if (!empty($flash)) {
            $view->addData([
                'flash' => $flash
            ]);
        }

        $res = $response ? $response : $this->response;
        $html = $view->render('auth::login', $args);
        $res->getBody()->write($html);
        return $res;
    }

    private function setFlash($status, $msgNumber)
    {
        require RUTA_DIR . LANG . $_SESSION['userLang'] . '/feedback.php';

        $message = 'msg' . $msgNumber;

        $flash = $this->container->get('flash');
        $flash->addMessage($status, [$$message]);
    }

    /**
     * Compute the secret hash for Cognito authentication
     *
     * @param string $username The username (email)
     * @param string $clientId The Cognito app client ID
     * @param string $clientSecret The Cognito app client secret
     * @return string The computed secret hash
     */
    private function cognitoSecretHash($username, $clientId, $clientSecret)
    {
        $message = $username . $clientId;
        $hash = hash_hmac('sha256', $message, $clientSecret, true);
        return base64_encode($hash);
    }

    /**
     * Handle challenge exceptions from Cognito
     */
    private function handleChallengeException($e, $body, $response, $router)
    {
        $challenge = $e->getChallengeName();

        if ($challenge === self::CHALLENGE_SMS_MFA || $challenge === self::CHALLENGE_SOFTWARE_TOKEN_MFA || $challenge === self::CHALLENGE_NEW_PASSWORD_REQUIRED) {
            return $this->redirectToAuth([
                'challenge' => $e->getChallengeName(),
                "email"     => $body['email'],
                "password"  => $body['password'],
                "session"   => $e->getSession()
            ], [], $response);
        }

        global $log;
        $log->error("Cognito challenge not supported", ["challenge" => $challenge, "exception" => $e]);
        $this->setFlash("errors", 4103);
        return $response->withHeader('Location', $router->urlFor('login'))->withStatus(302);
    }

    private function getUserAttribute($userInfo, $attributeToRetrieve)
    {
        foreach ($userInfo['UserAttributes'] as $attribute) {
            if (array_get($attribute, 'Name') === $attributeToRetrieve) {
                return array_get($attribute, 'Value');
            }
        }

        return null;
    }

    private function checkUserOnHotelinkingDatabase($cognitoClient, $config, $body, $response, $router)
    {
        global $log;
        include_once LIB . 'loguearHotel.php';

        $userInfo = $cognitoClient->adminGetUser([
            'UserPoolId' => $config['user_pool_id'],
            'Username'   => $body['email']
        ]);

        $accountId = $_SESSION['accountAccess'] = $this->getUserAttribute($userInfo, "custom:account_id");
        $brandIds = explode(",", $this->getUserAttribute($userInfo, "custom:brand_id") ?? "");
        $mfaRequiredCustomAttribute = $this->getUserAttribute($userInfo, "custom:mfa_required");

        if ($mfaRequiredCustomAttribute === "true" && !isset($userInfo['UserMFASettingList'])) {
            try {
                return $this->redirectToSetupMfa ($cognitoClient, $body, $router, $response);
            } catch (\Exception $e) {
                $log->error("Error on AssociateSoftwareToken, the MFA setup by authenticator app won't be available", [$e]);
                $this->setFlash("errors", 4103);
                $response->withHeader('Location', $routeParser->urlFor('login'));
            }


        } else {
            $userRole = json_decode(base64_decode(explode(".", $_SESSION['cognitoAuth']['IdToken'])[1]), true)["cognito:groups"][0];

            if ($userRole === "SUPER_ADMINS") {
                $_SESSION['private'] = true;
                header('Location: private/private-invitar-hotel');
                exit();
            }

            $identified = consultaBDLoginHotel($body['email']);

            if ($identified['error'] == '200') {
                if ($userRole === "BRAND_ADMINS" && !empty($brandIds)) {
                    $_SESSION['brandsAccess'] = array_map(function ($brandId)  {
                        return \getBrandById($brandId)['hotel_id'];
                    }, $brandIds);

                    $resultLogin = loguearHotel($_SESSION['brandsAccess'][0]);

                    if (count($_SESSION['brandsAccess']) === 1) {
                        unset($_SESSION['brandsAccess']);
                    }
                } else if ($userRole === "ACCOUNT_ADMINS" && $accountId) {
                    $brand = getBrandById($accountId);
                    $resultLogin = loguearCadena($brand['chain_id']);
                } else if ($identified['type'] == 'staff') {
                    $resultLogin = loguearStaff($identified['id']);
                    if(!$resultLogin){
                        $this->setFlash("errors", 4076);
                        $response->withHeader('Location', $routeParser->urlFor('login'));
                    }
                }
                if ($resultLogin) {
                    header('Location: ' . SECURE_BASE_PATH . $resultLogin['defaultPage'] . '/');
                    exit();
                }
            } else {
                $this->setFlash("errors", 4029);
                $response->withHeader('Location', $routeParser->urlFor('login'));
            }
        }
    }

    private function redirectToSetupMfa ($cognitoClient, $body, $router, $response, $flash = [])
    {
        global $log;
        try {
            $association = $cognitoClient->AssociateSoftwareToken([
                "AccessToken" => $_SESSION['cognitoAuth']['AccessToken']
            ]);

            return $this->redirectToAuth(
                [
                    'challenge' => "MFA_SETUP",
                    "email"     =>  array_get($body, 'email'),
                    "accessToken" => $_SESSION['cognitoAuth']['AccessToken'],
                    "associateSoftwareCode" => array_get($association, "SecretCode")
                ],
                $flash, $response
            );
        } catch (\Exception $e) {
            $log->error("Error on AssociateSoftwareToken, the MFA setup by authenticator app won't be available", [$e]);
            $this->setFlash("errors", 4103);
            return $response->withHeader('Location', $router->urlFor('login'))->withStatus(302);
        }
    }

    private function recoverAccount($cognitoClient, $config, $body, $response, $router)
    {
        global $log;
        try {
            $cognitoClient->forgotPassword([
                "ClientId"      => $config['app_client_id'],
                "SecretHash"    => $this->cognitoSecretHash(array_get($body, 'email'), $config['app_client_id'], $config['app_client_secret']),
                "Username"      => array_get($body, 'email'),
            ]);

            return $this->redirectToAuth(
                [
                    "challenge" => "RECOVER_ACCOUNT",
                    "email"     => array_get($body, 'email'),
                ], [], $response
            );
        } catch (CognitoIdentityProviderException $e) {
            $errorCode = $e->getAwsErrorCode();

            if ($errorCode === "UserNotFoundException") {
                $this->setFlash("errors", 4104);
            } else if ($errorCode === "LimitExceededException") {
                $this->setFlash("errors", 4106);
            } else if ($errorCode === "NotAuthorizedException") {
                $this->setFlash("errors", 4107);
            } else {
                $log->error("Unhandled error on forgot password", [$e]);
                $this->setFlash("errors", 4103);
            }
            return $response->withHeader('Location', $router->urlFor('login'))->withStatus(302);
        } catch (\Exception $e) {
            $log->error("Undhandled error on forgot password", [$e]);
            $this->setFlash("errors", 4103);
            return $response->withHeader('Location', $router->urlFor('login'))->withStatus(302);
        }
    }

    private function handleRespondToAuthChallenge($challenge, $body, $cognitoClient, $config, $response, $router)
    {
        global $log;
        try {
            // Use AWS SDK directly to respond to auth challenge
            $result = $cognitoClient->respondToAuthChallenge([
                'ChallengeName' => $challenge,
                'ClientId' => $config['app_client_id'],
                'ChallengeResponses' => [
                    $challenge . "_CODE" => $body['code'],
                    "USERNAME" => $body['email'],
                    "SECRET_HASH" => $this->cognitoSecretHash($body['email'], $config['app_client_id'], $config['app_client_secret'])
                ],
                'Session' => array_get($body, 'session')
            ]);

            $_SESSION['cognitoAuth'] = $result['AuthenticationResult'];
            return $this->checkUserOnHotelinkingDatabase($cognitoClient, $config, $body, $response, $router);
        } catch (CognitoIdentityProviderException $e) {
            $errorCode = $e->getAwsErrorCode();

            if ($errorCode === "CodeMismatchException") {
                require RUTA_DIR . LANG . $_SESSION['userLang'] . '/feedback.php';
                return $this->redirectToAuth(
                    [
                        'challenge' => $challenge,
                        "email"     => $body['email'],
                        "password"  => $body['password'],
                        "session"   => $body['session']
                    ],
                    [
                        "errors" => [
                            [$msg4105]
                        ]
                    ],
                    $response
                );
            } else if ($errorCode === "ExpiredCodeException") {
                require RUTA_DIR . LANG . $_SESSION['userLang'] . '/feedback.php';
                return $this->redirectToAuth(
                    [
                        'challenge' => $challenge,
                        "email"     => $body['email'],
                        "password"  => $body['password'],
                        "session"   => $body['session']
                    ],
                    [
                        "errors" => [
                            [$msg4110]
                        ]
                    ],
                        $response
                );
            } else {
                $log->error("Unhandled error on $challenge", [$e]);
                $this->setFlash("errors", 4103);
            }
            return $response->withHeader('Location', $router->urlFor('login'))->withStatus(302);

        } catch (\Exception $e) {
            $log->error("Unhandled error on $challenge", [$e]);
            $this->setFlash("errors", 4103);
            return $response->withHeader('Location', $router->urlFor('login'))->withStatus(302);
        }
    }

    private function handleCodeVerification($challenge, $cognitoException, $body, $response, $router)
    {
        global $log;

        $errorCode = $cognitoException->getAwsErrorCode();

        if ($errorCode === "CodeMismatchException") {
            require RUTA_DIR . LANG . $_SESSION['userLang'] . '/feedback.php';
            return $this->redirectToAuth(
                [
                    'challenge' => $challenge,
                    "email"     =>  array_get($body, 'email'),
                ],
                [
                    "errors" => [
                        [$msg4105]
                    ]
                ],
                $response
            );
        } else if ($errorCode === "ExpiredCodeException") {
            $this->setFlash("errors", 4108);
        } else if ($errorCode === "LimitExceededException") {
            $this->setFlash("errors", 4106);
        } else {
            $log->error("Unhandled error on handle code verification in $challenge", [$e]);
            $this->setFlash("errors", 4103);
        }
        return $response->withHeader('Location', $router->urlFor('login'))->withStatus(302);
    }

    private function setMfaPreferences($type, $cognitoClient, $config, $body, $response, $router)
    {
        global $log;
        try {
            $mfaToEnable = $type === "SMS" ? "SMSMfaSettings" : "SoftwareTokenMfaSettings";
            $cognitoClient->adminSetUserMFAPreference([
                "UserPoolId" => $config['user_pool_id'],
                "Username"   => $body['email'],
                "$mfaToEnable" => [
                    'Enabled' => true,
                    'PreferredMfa' => true
                ]
            ]);

            $this->setFlash("success", 2043);
            return $response->withHeader('Location', $router->urlFor('login'))->withStatus(302);

        } catch (\Exception $e) {
            $log->error("Unhandled error setting up MFA preferences on MFA_SETUP process", [$e]);
            $this->setFlash("errors", 4103);
            return $response->withHeader('Location', $router->urlFor('login'))->withStatus(302);
        }
    }

    public function login($request, $response)
    {
        global $log;

        $routeParser = RouteContext::fromRequest($request)->getRouteParser();

        //validate user/password
        if ($request->getMethod() === 'POST') {
            $errors = $request->getAttribute('errors');
            //if errors
            if ($errors) {
                $flash = $this->container->get('flash');
                foreach ($errors as $msg) {
                    $flash->addMessage('errors', $msg);
                }
                return $response->withHeader('Location', $routeParser->urlFor('login'))->withStatus(302);
            } else {
                $config = AWS_SUITE;
                $aws = new \Aws\Sdk($config);
                $cognitoClient = $aws->createCognitoIdentityProvider();

                include_once MODEL . 'hotel-loginModel.php';
                $body = $request->getParsedBody();
                if (array_get($body, 'emailToRecover')) {
                    // If the user requests to recover his account, we will send him a code in the indicated email, after which he will have to validate it to change his password.
                    return $this->recoverAccount($cognitoClient, $config, $body, $response, $routeParser);
                } else if (array_get($body, 'challenge') === self::CHALLENGE_NEW_PASSWORD_REQUIRED) {
                    // When we have registered a user, we force them to change the temporary password we have generated for them.
                    try {
                        // Respond to new password required challenge using AWS SDK directly
                        $result = $cognitoClient->respondToAuthChallenge([
                            'ChallengeName' => self::CHALLENGE_NEW_PASSWORD_REQUIRED,
                            'ClientId' => $config['app_client_id'],
                            'ChallengeResponses' => [
                                'USERNAME' => array_get($body, 'email'),
                                'NEW_PASSWORD' => array_get($body, 'password'),
                                'SECRET_HASH' => $this->cognitoSecretHash(array_get($body, 'email'), $config['app_client_id'], $config['app_client_secret'])
                            ],
                            'Session' => array_get($body, 'session')
                        ]);

                        $_SESSION['cognitoAuth'] = $result['AuthenticationResult'];
                        return $this->checkUserOnHotelinkingDatabase($cognitoClient, $config, $body, $response, $routeParser);
                    } catch (CognitoIdentityProviderException $e) {
                        $errorCode = $e->getAwsErrorCode();

                        if ($errorCode === 'InvalidPasswordException') {
                            require RUTA_DIR . LANG . $_SESSION['userLang'] . '/feedback.php';
                            return $this->redirectToAuth(
                                [
                                    'challenge' => self::CHALLENGE_NEW_PASSWORD_REQUIRED,
                                    "email"     => $body['email'],
                                    "password"  => $body['password'],
                                    "session"   => $body['session']
                                ],
                                [
                                    "errors" => [
                                        [$msg4031]
                                    ]
                                ],
                                $response
                            );
                        } else if ($errorCode === 'NotAuthorizedException' || $errorCode === 'UserNotFoundException') {
                            $this->setFlash("errors", 4029);
                            return $response->withHeader('Location', $routeParser->urlFor('login'))->withStatus(302);
                        } else if (strpos($e->getMessage(), 'ChallengeName') !== false) {
                            // This is similar to the old ChallengeException
                            return $this->handleChallengeException($e, $body, $response, $routeParser);
                        } else {
                            $log->error("Unhandled error changing password", [$e]);
                            $this->setFlash("errors", 4103);
                            return $response->withHeader('Location', $routeParser->urlFor('login'))->withStatus(302);
                        }
                    } catch (\Exception $e) {
                        $log->error("Unhandled error changing password", [$e]);
                        $this->setFlash("errors", 4103);
                        return $response->withHeader('Location', $routeParser->urlFor('login'))->withStatus(302);
                    }
                } else if (array_get($body, "challenge") === self::CHALLENGE_SMS_MFA || array_get($body, "challenge") === self::CHALLENGE_SOFTWARE_TOKEN_MFA) {
                    // Whether you have the MFA activated by SMS or Application, we check that the code is correct before logging in.
                    return $this->handleRespondToAuthChallenge($body["challenge"], $body, $cognitoClient, $config, $response, $routeParser);
                } else if (array_get($body, 'challenge') === "RECOVER_ACCOUNT") {
                    // When the user requests to recover his account, an e-mail with a code is sent to him. This code is necessary to change the password.
                    try {
                        $cognitoClient->confirmForgotPassword([
                            "ClientId"          => $config['app_client_id'],
                            "SecretHash"        => $this->cognitoSecretHash(array_get($body, 'email'), $config['app_client_id'], $config['app_client_secret']),//$client->cognitoSecretHash(array_get($body, 'email')),
                            "Username"          => array_get($body, 'email'),
                            "Password"          => array_get($body, 'password'),
                            "ConfirmationCode"  => array_get($body, 'code'),
                        ]);
                        $this->setFlash("success", 2042);
                        return $response->withHeader('Location', $routeParser->urlFor('login'))->withStatus(302);
                    } catch (CognitoIdentityProviderException $e) {
                        return $this->handleCodeVerification("RECOVER_ACCOUNT", $e, $body, $response, $routeParser);
                    } catch (\Exception $e) {
                        $log->error("Unhandled error recovering account", [$e]);
                        $this->setFlash("errors", 4103);
                        return $response->withHeader('Location', $routeParser->urlFor('login'))->withStatus(302);
                    }
                } else if (array_get($body, 'challenge') === "MFA_SETUP") {
                    // We validate that the MFA method chosen by the user is correct.
                    $mfaType = array_get($body, "mfa_type");
                    $username = array_get($body, 'email');
                    $accesToken = $_SESSION['cognitoAuth']['AccessToken'];

                    if ($mfaType === "sms") {
                        try {
                            // We delete the phone number saved in Cognito because if it was already saved, the invitation code will not be sent again.
                            $cognitoClient->adminDeleteUserAttributes([
                                "Username" => $username,
                                "UserAttributeNames" => ["phone_number"],
                                "UserPoolId" => $config['user_pool_id']
                            ]);

                            $cognitoClient->adminUpdateUserAttributes([
                                "Username" => $username,
                                "UserAttributes" => [
                                    [
                                        'Name'  => 'phone_number',
                                        'Value' => array_get($body, 'phone_code') . array_get($body, 'phone_number'),
                                    ]

                                ],
                                "UserPoolId" => $config['user_pool_id']
                            ]);

                            return $this->redirectToAuth(
                                [
                                    'challenge' => "MFA_SETUP_VERIFY_SMS",
                                    "email"     =>  array_get($body, 'email'),
                                    "accessToken" => $accesToken
                                ], [], $response
                            );
                        } catch (CognitoIdentityProviderException $e) {
                            $errorCode = $e->getAwsErrorCode();

                            if ($errorCode === "InvalidParameterException") {
                                require RUTA_DIR . LANG . $_SESSION['userLang'] . '/feedback.php';
                                return $this->redirectToSetupMfa($cognitoClient, $body, $routeParser, $response, ["errors" => [[$msg4109]]]);
                            } else {
                                $log->error("Unhandled error on forgot password", [$e]);
                                $this->setFlash("errors", 4103);
                            }
                            return $response->withHeader('Location', $routeParser->urlFor('login'))->withStatus(302);
                        } catch (\Exception $e) {
                            $log->error("Unhandled error updating phone number on MFA_SETUP process", [$e]);
                            $this->setFlash("errors", 4103);
                            return $response->withHeader('Location', $routeParser->urlFor('login'))->withStatus(302);
                        }
                    } else if ($mfaType === "authenticator") {
                        $code = array_get($body, 'code');
                        try {
                            $cognitoClient->VerifySoftwareToken([
                                "AccessToken" => $accesToken,
                                "UserCode"  => $code
                            ]);
                        } catch (CognitoIdentityProviderException $e) {
                            $errorCode = $e->getAwsErrorCode();

                            if ($errorCode === "CodeMismatchException" || $errorCode === "EnableSoftwareTokenMFAException" || $errorCode === "InvalidParameterException") {
                                require RUTA_DIR . LANG . $_SESSION['userLang'] . '/feedback.php';
                                return $this->redirectToSetupMfa($cognitoClient, $body, $routeParser, $response, ["errors" => [[$msg4114]]]);
                            } else {
                                $log->error("Unhandled error verifying software token on MFA_SETUP process", [$e]);
                                $this->setFlash("errors", 4103);
                            }
                            return $response->withHeader('Location', $routeParser->urlFor('login'))->withStatus(302);
                        } catch (\InvalidArgumentException $e) {
                            require RUTA_DIR . LANG . $_SESSION['userLang'] . '/feedback.php';
                            return $this->redirectToSetupMfa($cognitoClient, $body, $routeParser, $response, ["errors" => [[$msg4114]]]);
                        } catch (\Exception $e) {
                            $log->error("Unhandled error verifying software token on MFA_SETUP process", [$e]);
                            $this->setFlash("errors", 4103);
                            return $response->withHeader('Location', $routeParser->urlFor('login'))->withStatus(302);
                        }

                        return $this->setMfaPreferences($mfaType, $cognitoClient, $config, $body, $response, $routeParser);
                    } else {
                        $log->error("Unhandled MFA type for setup", [$mfaType]);
                        $this->setFlash("errors", 4103);
                        return $response->withHeader('Location', $routeParser->urlFor('login'))->withStatus(302);
                    }
                } else if (array_get($body, 'challenge') === "MFA_SETUP_VERIFY_SMS") {
                    try {
                        $cognitoClient->verifyUserAttribute(
                            [
                                "AccessToken" => $_SESSION['cognitoAuth']['AccessToken'],
                                "AttributeName" => "phone_number",
                                "Code"  => $body['code'],
                            ]
                        );
                    } catch (CognitoIdentityProviderException $e) {
                        return $this->handleCodeVerification("MFA_SETUP_VERIFY_SMS", $e, $body, $response, $routeParser);
                    } catch (\Exception $e) {
                        $log->error("Unhandled error verifying the phone number on MFA_SETUP process", [$e]);
                        $this->setFlash("errors", 4103);
                        return $response->withHeader('Location', $routeParser->urlFor('login'))->withStatus(302);
                    }

                    return $this->setMfaPreferences("SMS", $cognitoClient, $config, $body, $response, $routeParser);
                } else {
                    try {
                        $authResult = $this->authenticateUser($cognitoClient, $config, $body['email'], trim($body['password']));

                        $challengeName = array_get($authResult, 'ChallengeName');
                        if ($challengeName) {
                            $session = array_get($authResult, 'Session');

                            $challengeException = new ChallengeException($challengeName, $session);
                            return $this->handleChallengeException($challengeException, $body, $response, $routeParser);
                        }

                        $_SESSION['cognitoAuth'] = $authResult['AuthenticationResult'];
                        return $this->checkUserOnHotelinkingDatabase($cognitoClient, $config, $body, $response, $routeParser);
                    } catch (CognitoIdentityProviderException $e) {
                        $errorCode = $e->getAwsErrorCode();

                        if ($errorCode === 'NotAuthorizedException') {
                            $this->setFlash("errors", 4029);
                        } else if ($errorCode === 'UserNotFoundException') {
                            $this->setFlash("errors", 4104);
                        } else if ($errorCode === 'PasswordResetRequiredException') {
                            return $this->recoverAccount($cognitoClient, $config, $body, $response, $routeParser);
                        } else {
                            $log->error("Unhandled error on Cognito Login", [$e]);
                            $this->setFlash("errors", 4103);
                        }
                    } catch (\Exception $e) {
                        $log->error("Unhandled error on Cognito Login", [$e]);
                        $this->setFlash("errors", 4103);
                    }
                    return $response->withHeader('Location', $routeParser->urlFor('login'))->withStatus(302);
                }
            }
        }
    }

    private function authenticateUser($cognitoClient, $config, $email, $password)
    {
        return $cognitoClient->adminInitiateAuth([
            'UserPoolId' => $config['user_pool_id'],
            'ClientId' => $config['app_client_id'],
            'AuthFlow' => 'ADMIN_NO_SRP_AUTH',
            'AuthParameters' => [
                'USERNAME' => $email,
                'PASSWORD' => $password,
                'SECRET_HASH' => $this->cognitoSecretHash($email, $config['app_client_id'], $config['app_client_secret']),
            ],
        ]);
    }

    public function logout($request, $response)
    {
        if ($request->getMethod() === 'GET') {
            $view = $this->container->get('view');
            $view->addData([
                'title' => 'Hotelinking - Logout',
            ]);

            // We recover the private session if it was present and the request is not made from the private
            $restorePrivateSession = false;
            if (!empty($_SESSION['private']) && !str_contains($request->getHeader('referer')[0], 'private')) {
                $restorePrivateSession = true;
                $restoreLang = $_SESSION['userLang'];
            }
            session_destroy();
            if (isset($_COOKIE['PHPSESSID'])) {
                unset($_COOKIE['PHPSESSID']);
                setcookie('PHPSESSID', '', time() - 3600, '/', '', true, true);
            }

            if ($restorePrivateSession) {
                session_start();
                $_SESSION['private'] = true;
                $_SESSION['userLang'] = $restoreLang;
            }
            $html = $view->render('auth::logout');
            $response->getBody()->write($html);
            return $response;
        }
    }
}
