<?php

declare(strict_types=1);

// echo 'Hello, World';
$pdo = new PDO(
    "mysql:host=" . getenv("DB_HOST") . ";dbname=" . getenv("DB_NAME") . ";charset=utf8mb4",
    getenv("DB_USER"),
    getenv("DB_PASS")
);