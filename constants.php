<?php
// Detection auto du protocole (http/https)
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';

// Construction de la base_url
$projectPath = dirname($_SERVER['PHP_SELF']);
$basePath = dirname($projectPath);
define('BASE_URL', $protocol . $_SERVER['HTTP_HOST'] . $basePath);
?> 