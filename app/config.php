<?php

// aza adino ny configuration base anareo

define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'bngrc');
define('DB_USER', 'postgres');
define('DB_PASS', ' ');

$baseUrl = rtrim(str_replace('\\', '/', dirname(path: $_SERVER['SCRIPT_NAME'] ?? '/')), '/');

if ($baseUrl === '/')
    $baseUrl = '';

define('BASE_URL', $baseUrl);
