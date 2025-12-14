<?php

declare(strict_types=1);

function _fetchFromCentralbank(string $endpoint): array
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $_ENV['CENTRALBANK_API_URL'] . $endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $data = curl_exec($ch);

    return json_decode($data, true) ?? [];
}

function _postToCentralbank(string $endpoint, array $params): array
{
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, $_ENV['CENTRALBANK_API_URL'] . $endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));

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

function updateIslandProperties(...$props): array
{
    $properties = listIslandProperties();
    $features = [];

    foreach ($props as $key => $value) {
        $properties['island'][$key] = $value;
    }

    foreach ($properties['features'] as $f) {
        $features[$f['activity']][$f['tier']] = $f['feature'];
    }

    $postData = array_merge(
        $properties['island'],
        [
            'user' => $_ENV['CENTRALBANK_USER'],
            'api_key' => $_ENV['CENTRALBANK_API_KEY'],
            'features' => array_replace_recursive($features, $props['features'] ?? [])
        ]
    );

    return _postToCentralbank('islands', $postData);
}


function getAccountBalance(): array
{
    return _postToCentralbank(
        'accountInfo',
        [
            'user' => $_ENV['CENTRALBANK_USER'],
            'api_key' => $_ENV['CENTRALBANK_API_KEY']
        ]
    );
}

function createTransferCode(?string $user = null, ?string $api_key = null, ?int $amount = 0): array
{

    return _postToCentralbank(
        'withdraw',
        [
            'user' => $user ?? $_ENV['CENTRALBANK_USER'],
            'api_key' => $api_key ?? $_ENV['CENTRALBANK_API_KEY'],
            'amount' => $amount
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
