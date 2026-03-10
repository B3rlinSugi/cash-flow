<?php
require_once __DIR__ . '/../config/database.php';
startSession();
$_SESSION = [];
session_destroy();
header('Location: /login.php');
exit;
