<?php

declare(strict_types=1);

function flashErrors(): array
{
    $errors = $_SESSION['errors'] ?? [];
    unset($_SESSION['errors']);
    return $errors;
}

function flashReceipt(): ?array
{
    $receipt = $_SESSION['receipt'] ?? null;
    unset($_SESSION['receipt']);
    return $receipt;
}
