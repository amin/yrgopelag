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

function _postToCentralbank(string $endpoint, array $params, string $errorMessage = "Something went wrong"): array
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $_ENV['CENTRALBANK_API_URL'] . $endpoint);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($params));

    $data = json_decode(curl_exec($ch), true);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    if ($httpCode !== 200 || ($data['status'] ?? null) === 'error') {
        throw new Exception($errorMessage, $httpCode);
    }

    return $data ?? [];
}

function getIslands(): array
{
    return _fetchFromCentralbank('islands');
}

function getIslandProperties(): array
{
    return _postToCentralbank(
        'islandFeatures',
        [
            'user' => $_ENV['CENTRALBANK_USER'],
            'api_key' => $_ENV['CENTRALBANK_API_KEY']
        ]
    );
}


function setIslandProperties(...$props): array
{
    $properties = getIslandProperties();
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

function getAccountBalance(?string $user = null, ?string $api_key = null): array
{
    return _postToCentralbank(
        'accountInfo',
        [
            'user' => $user ?? $_ENV['CENTRALBANK_USER'],
            'api_key' => $api_key ?? $_ENV['CENTRALBANK_API_KEY'],
        ],
        "Coudld not get account balance"
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
        ],
        "Could not create transfer code - check your account details or balance"
    );
}

function depositTransferCode(string $transferCode): array
{
    return _postToCentralbank(
        'deposit',
        [
            'user' => $_ENV['CENTRALBANK_USER'],
            'transferCode' => $transferCode
        ],
        "Could not deposit transfer code"
    );
}

function createReceipt(string $guestName, string $arrivalDate, string $departureDate, array $features = []): array
{
    return _postToCentralbank(
        'receipt',
        [
            'user' => $_ENV['CENTRALBANK_USER'],
            'api_key' => $_ENV['CENTRALBANK_API_KEY'],
            'guest_name' => $guestName,
            'arrival_date' => $arrivalDate,
            'departure_date' => $departureDate,
            'features_used' => $features,
            'star_rating' => 5
        ],
        "A receipt for a stay for your user has already been registered on these dates. Please try another date."
    );
}
