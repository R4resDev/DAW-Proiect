<?php
session_start();
require_once 'operatii_db.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_rol'] !== 'admin') {
    die("Acces neautorizat! Doar administratorii pot genera acest raport.");
}

try {
    $plati = OperatiiDB::read('plati', "ORDER BY data_platii DESC");
} catch (Exception $e) {
    die("Eroare la extragerea datelor: " . $e->getMessage());
}

header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="Raport_Plati_Fitness_' . date('Y-m-d') . '.csv"');

$output = fopen('php://output', 'w');

fputcsv($output, ['ID Plata', 'ID Utilizator', 'Suma (RON)', 'Data Tranzactie']);

foreach ($plati as $p) {
    fputcsv($output, [
        $p['id_plata'],
        $p['id_user'],
        $p['suma'],
        $p['data_platii']
    ]);
}

fclose($output);
exit();
?>
