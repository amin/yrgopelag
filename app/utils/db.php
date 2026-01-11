<?php

declare(strict_types=1);

function getDb(): PDO
{
    static $pdo = null;

    if ($pdo === null) {
        $dbPath = __DIR__ . '/../../database/yrgopelag.sqlite';


        $pdo = new PDO(
            "sqlite:$dbPath",
            null,
            null,
            [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
            ]
        );


        $pdo->exec('PRAGMA foreign_keys = ON');
    }

    return $pdo;
}
