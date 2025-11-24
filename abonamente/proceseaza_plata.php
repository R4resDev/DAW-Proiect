<?php
// abonamente/proceseaza_plata.php
session_start();
require_once '../operatii_db.php'; 

if (!isset($_SESSION['user_id']) || $_SERVER['REQUEST_METHOD'] !== 'POST') {
    header("Location: ../login.php");
    exit();
}

if (isset($_POST['confirma_plata'])) {
    $id_user = $_POST['id_user']; 
    $id_abonament = $_POST['id_abonament'];
    $suma = $_POST['suma'];
    
    if ($id_user != $_SESSION['user_id'] || empty($id_abonament) || !is_numeric($suma)) {
        header("Location: index.php?mesaj=" . urlencode("Eroare tranzacție: Date invalide."));
        exit();
    }
    
    try {
        $date_plata = [
            'id_user' => (int)$id_user,
            'id_abonament' => (int)$id_abonament,
            'suma' => number_format((float)$suma, 2, '.', ''),
            'data_platii' => date('Y-m-d H:i:s')
        ];
        
        $id_plata_noua = OperatiiDB::create('plati', $date_plata);
        
        header("Location: index.php?mesaj=" . urlencode("Plată înregistrată cu succes! ID Tranzacție: $id_plata_noua. Vă mulțumim pentru achiziție.&mesaj_tip=succes"));
        exit();
        
    } catch (Exception $e) {
        header("Location: index.php?mesaj=" . urlencode("Eroare la procesarea plății: " . $e->getMessage()));
        exit();
    }
}
?>