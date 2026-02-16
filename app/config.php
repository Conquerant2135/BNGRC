<?php

// aza adino ny configuration base anareo

define('DB_HOST', '127.0.0.1');
define('DB_NAME', 'db_s2_ETU004367');
define('DB_USER', 'ETU004367');
define('DB_PASS', 'uSbxQVRP');
//  define('DB_USER', 'root');
//  define('DB_PASS', '');
define('DB_CHARSET' , 'utf8mb4');

$baseUrl = rtrim(str_replace('\\', '/', dirname(path: $_SERVER['SCRIPT_NAME'] ?? '/')), '/');

if ($baseUrl !== '' && $baseUrl[0] !== '/') {
    $baseUrl = '/' . $baseUrl;
}

if ($baseUrl === '/')
    $baseUrl = '';

define('BASE_URL', $baseUrl);
