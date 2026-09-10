<?php

include_once APP . 'Services/Connections/ApiGatewayConnection.php';

function updateProtocol($brandId, $redirect)
{
    if ($_SERVER['REQUEST_METHOD'] == 'POST' && isset($_POST['action'])) {
        if ($_POST['action'] == 'update') {
            addOrUpdateService($brandId);
            header("Location: /" . $redirect);
            exit;
        } else {
            header("Content-Type: text/html; charset=UTF-8");
            header("Status: 403 Forbidden", true, 403);
            exit;
        }
    }
}

function getActiveProtocolServices($brandId)
{
    $endPoint = "brands/{$brandId}/protocols";
    $gateway = new ApiGatewayConnection();
    $response = $gateway->sendRequest(null, HOTELINKING_ENDPOINT . $endPoint, 'GET');
    $services = json_decode($response, true) ?? [];

    if (!empty($services)) {
        $services = array_combine(array_column($services, 'service'), $services);
    }

    return $services;
}

function getProtocolServices($brandId)
{
    $endPoint = "protocols/services";
    $key = md5($endPoint . $brandId);
    $service = [
        'id'        => null,
        'brand_id'  => $brandId,
        'service'   => null,
        'treatment' => 'formal'
    ];

    if ($cache = getFromCache($key)) {
        return $cache->get();
    }

    $services = [];
    $gateway = new ApiGatewayConnection();
    $response = $gateway->sendRequest(null, HOTELINKING_ENDPOINT . $endPoint, 'GET');
    $responseArray = json_decode($response, true);

    if (!empty($responseArray)) {
        foreach ($responseArray as $item) {
            $service['service'] = $item;
            $services[$item] = $service;
        }

        setToCache($key, $services, 3600);
    }

    return $services;
}

function addOrUpdateService($brandId)
{
    if (!isset($_POST['services']) || !is_array($_POST['services'])) {
        return;
    }

    $cacheKey = md5("brands/{$brandId}/protocols");
    $gateway = new ApiGatewayConnection();

    foreach ($_POST['services'] as $key => $service) {
        $endPoint = 'protocols';
        $serviceId = (int)$service['id'];
        $method = null;
        $payload = null;

        if ($serviceId) {
            // Actualizar
            $method = 'PUT';
            $endPoint .= "/{$serviceId}";
            $payload = [
                'treatment' => isset($service['active']) ? 'informal' : 'formal'
            ];
        } else {
            // Añadir
            $method = 'POST';
            $payload = [
                'brand_id'  => $brandId,
                'service'   => $key,
                'treatment' => isset($service['active']) ? 'informal' : 'formal'
            ];
        }

        if ($method) {
            $gateway->sendRequest($payload, HOTELINKING_ENDPOINT . $endPoint, $method);
            deleteCacheByKey($cacheKey);
        }
    }
}

function getBrandProtocols($brandId)
{
    $key = md5("brands/{$brandId}/protocols");

    if ($cache = getFromCache($key)) {
        return $cache->get();
    }

    $parentId = $_SESSION['hotel']['parent_id'] ?? null;
    $getActiveProtocolServices = getActiveProtocolServices($brandId);
    $parentProtocols = ($parentId && $parentId != $brandId) ? getActiveProtocolServices($parentId) : null;
    $services = getProtocolServices($brandId);
    $protocols = array_merge($services, $getActiveProtocolServices);

    if ($parentProtocols) {
        foreach ($protocols as $key => $protocol) {
            if (!$protocol['id']) {
                $protocols[$key] = $parentProtocols[$key];
            }
        }
    }

    $protocols['portal']['dbsufix'] = ($protocols['portal']['treatment'] == 'informal') ? '_informal' : "";
    $protocols['emails']['dbsufix'] = ($protocols['emails']['treatment'] == 'informal') ? '_informal' : "";

    setToCache($key, $protocols, 31536000);

    return $protocols;
}
