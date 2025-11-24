<?php
// abonamente/sterge.php
session_start();
require_once '../operatii_db.php'; 

if (!isset($_SESSION['user_id']) || $_SESSION['user_rol'] !== 'admin' || $_SERVER['REQUEST_METHOD'] !== 'GET') {
    header("Location: ../login.php");
    exit();
}

$id_abonament = $_GET['id'] ?? null;

if ($id_abonament) {
    try {
        $conditie_sql = "id_abonament = ?";
        $valori_conditie = [$id_abonament];
        
        OperatiiDB::delete('abonamente', $conditie_sql, $valori_conditie);

        header("Location: index.php?mesaj=" . urlencode("Abonamentul a fost șters cu succes.&mesaj_tip=succes"));
        exit();

    } catch (Exception $e) {
        $mesaj_eroare = "Eroare la ștergere! Asigurați-vă că nu există plăți asociate acestui abonament în tabela 'plati'.";
        header("Location: index.php?mesaj=" . urlencode($mesaj_eroare));
        exit();
    }
} else {
    header("Location: index.php?mesaj=" . urlencode("ID-ul abonamentului lipsește."));
    exit();
}
?>