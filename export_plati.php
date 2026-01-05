<?php
// export_plati.php
session_start();
require_once 'operatii_db.php';

// Protecție: Doar administratorul poate exporta datele financiare
if (!isset($_SESSION['user_id']) || $_SESSION['user_rol'] !== 'admin') {
    die("Acces neautorizat! Doar administratorii pot genera acest raport.");
}

// Preluăm datele din tabela de plăți
try {
    $plati = OperatiiDB::read('plati', "ORDER BY data_platii DESC");
} catch (Exception $e) {
    die("Eroare la extragerea datelor: " . $e->getMessage());
}

// Setăm headerele HTTP pentru a forța browserul să descarce fișierul ca Excel/CSV
header('Content-Type: text/csv; charset=utf-8');
header('Content-Disposition: attachment; filename="Raport_Plati_Fitness_' . date('Y-m-d') . '.csv"');

// Deschidem fluxul de ieșire
$output = fopen('php://output', 'w');

// Scriem capul de tabel (Numele coloanelor)
fputcsv($output, ['ID Plata', 'ID Utilizator', 'Suma (RON)', 'Data Tranzactie']);

// Scriem datele propriu-zise
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