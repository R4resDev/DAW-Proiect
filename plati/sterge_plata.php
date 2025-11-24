<?php
// plati/sterge_plata.php
session_start();
require_once '../operatii_db.php'; 

if (!isset($_SESSION['user_id']) || $_SESSION['user_rol'] !== 'admin' || $_SERVER['REQUEST_METHOD'] !== 'GET') {
    header("Location: ../login.php");
    exit();
}

$id_plata = $_GET['id'] ?? null;

if ($id_plata) {
    try {
        $conditie_sql = "id_plata = ?";
        $valori_conditie = [$id_plata];
        
        OperatiiDB::delete('plati', $conditie_sql, $valori_conditie);

        header("Location: index.php?mesaj=" . urlencode("Plata cu ID $id_plata a fost ștearsă (anulată) cu succes.&mesaj_tip=succes"));
        exit();

    } catch (Exception $e) {
        $mesaj_eroare = "Eroare la ștergere: " . $e->getMessage();
        header("Location: index.php?mesaj=" . urlencode($mesaj_eroare));
        exit();
    }
} else {
    header("Location: index.php?mesaj=" . urlencode("ID-ul plății lipsește."));
    exit();
}
?>