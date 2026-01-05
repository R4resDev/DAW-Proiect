<?php
session_start();
require_once 'operatii_db.php';

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: login.php");
    exit();
}

$id_user = $_SESSION['user_id'];
$id_antrenor = $_POST['id_antrenor'];
$suma = $_POST['suma'];

try {
    // 1. Înregistrăm plata în tabelul 'plati'
    // id_abonament va fi NULL deoarece este o plată pentru antrenor
    OperatiiDB::create('plati', [
        'id_user' => $id_user,
        'id_abonament' => null, 
        'suma' => $suma,
        'data_platii' => date('Y-m-d H:i:s')
    ]);

    // 2. Actualizăm antrenorul asociat utilizatorului
    OperatiiDB::update('utilizatori', 
        ['id_antrenor_asociat' => $id_antrenor], 
        "id_user = :id_u", 
        ['id_u' => $id_user]
    );

    header("Location: dashboard.php?mesaj=" . urlencode("Plată acceptată! Te-ai înscris la antrenor cu succes."));
    exit();

} catch (Exception $e) {
    die("Eroare la procesarea plății: " . $e->getMessage());
}