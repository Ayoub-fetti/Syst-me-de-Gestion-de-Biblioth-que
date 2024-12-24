<?php
// Detection auto du protocole (http/https)
$protocol = isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on' ? 'https://' : 'http://';

// Construction de la base_url
define('BASE_URL', $protocol . $_SERVER['HTTP_HOST'] . dirname($_SERVER['PHP_SELF']));
?> 