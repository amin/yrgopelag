<?php

declare(strict_types=1);

function _fetchFromCentralbank(string $endpoint): array
{
    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => $_ENV['CENTRALBANK_API_URL'] . $endpoint,
        CURLOPT_RETURNTRANSFER => true
    ]);

    $data = curl_exec($ch);

    return json_decode($data, true) ?? [];
}

function _postToCentralbank(string $endpoint, array $params): array
{
    $ch = curl_init();

    curl_setopt_array($ch, [
        CURLOPT_URL => $_ENV['CENTRALBANK_API_URL'] . $endpoint,
        CURLOPT_RETURNTRANSFER => true,
        CURLOPT_HTTPHEADER => ['Content-Type: application/json'],
        CURLOPT_POST => true,
        CURLOPT_POSTFIELDS => json_encode($params),
    ]);

    $data = curl_exec($ch);

    return json_decode($data, true) ?? [];
}

function getIslands(): array
{
    return _fetchFromCentralbank('islands');
}

function listIslandProperties(): array
{
    return _postToCentralbank(
        'islandFeatures',
        [
            'user' => $_ENV['CENTRALBANK_USER'],
            'api_key' => $_ENV['CENTRALBANK_API_KEY']
        ]
    );
}

function updateIslandProperties(...$args): array
{
    $properties = listIslandProperties();

    foreach (array_keys($args) as $arg) {
        $properties['island'][$arg] = $args[$arg];
    }

    $properties = array_merge(
        $properties['island'],
        [
            'user' => $_ENV['CENTRALBANK_USER'],
            'api_key' => $_ENV['CENTRALBANK_API_KEY'],
            'features' => array_values($properties['features'])
        ]
    );

    return _postToCentralbank(
        'islands',
        $properties
    );
}


function getAccountBalance(): array
{
    return _postToCentralbank(
        'accountInfo',
        [
            'user' => $_ENV['CENTRALBANK_USER'],
            "api_key" => $_ENV['CENTRALBANK_API_KEY']
        ]
    );
}

function createTransferCode(?string $user = null, ?string $api_key = null, ?int $amount = 0): array
{

    return _postToCentralbank(
        'withdraw',
        [
            'user' => $user ?? $_ENV['CENTRALBANK_USER'],
            "api_key" => $api_key ?? $_ENV['CENTRALBANK_API_KEY'],
            "amount" => $amount
        ]
    );
}

function depositTransferCode(string $transferCode): array
{
    return _postToCentralbank(
        'deposit',
        [
            'user' => $_ENV['CENTRALBANK_USER'],
            'transferCode' => $transferCode
        ]
    );
}
