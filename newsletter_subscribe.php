<?php
session_start();
require_once 'operatii_db.php'; 
require_once 'functions.php';

if (!isset($_SESSION['user_id']) || !verify_csrf_token($_GET['token'] ?? '')) {
    die("Eroare de securitate sau sesiune expirată.");
}

$email = $_SESSION['user_email'];

try {
    $exist = OperatiiDB::read('newsletter', "WHERE email = :e", ['e' => $email]);
    if (count($exist) == 0) {
        OperatiiDB::create('newsletter', ['email' => $email, 'data_inscriere' => date('Y-m-d H:i:s')]);
    }
} catch (Exception $e) {}

header("Location: dashboard.php");
exit();