<?php
session_start();

// detruire toutes les variables de session
$_SESSION = array();

// detruire la session
session_destroy();

// rediriger vers la page de connexion
header('Location: login.php');
exit(); 