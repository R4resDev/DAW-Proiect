<?php
// newsletter/sterge.php
session_start();
require_once '../operatii_db.php'; 

// 1. Verifică rolul (doar Admin) și Metoda (doar GET pentru simplitate linkului)
if (!isset($_SESSION['user_id']) || $_SESSION['user_rol'] !== 'admin' || $_SERVER['REQUEST_METHOD'] !== 'GET') {
    header("Location: ../login.php");
    exit();
}

$id_inscriere = $_GET['id'] ?? null;

if ($id_inscriere) {
    try {
        $conditie_sql = "id_inscriere = ?";
        $valori_conditie = [$id_inscriere];
        
        OperatiiDB::delete('newsletter', $conditie_sql, $valori_conditie);

        header("Location: index.php?mesaj=" . urlencode("Abonatul cu ID $id_inscriere a fost șters cu succes.&mesaj_tip=succes"));
        exit();

    } catch (Exception $e) {
        $mesaj_eroare = "Eroare la ștergere: " . $e->getMessage();
        header("Location: index.php?mesaj=" . urlencode($mesaj_eroare));
        exit();
    }
} else {
    header("Location: index.php?mesaj=" . urlencode("ID-ul abonatului lipsește."));
    exit();
}
?>