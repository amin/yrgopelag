<?php

declare(strict_types=1);

function getFeaturePath(string $needle, array $haystack = AVAILABLE_FEATURES, array $path = []): ?array
{
    foreach ($haystack as $key => $value) {

        if ($value === $needle) {
            return [$path[0] => [$key => $value]];
        }

        if (is_array($value)) {
            $result = getFeaturePath($needle, $value, [...$path, $key]);
            if ($result !== null) {
                return $result;
            }
        }
    }

    return null;
}
