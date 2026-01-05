<?php
session_start();
require_once 'operatii_db.php';
if ($_SESSION['user_rol'] !== 'admin') { exit(); }

$plati = OperatiiDB::read('plati', "ORDER BY data_platii DESC");

header('Content-Type: text/csv');
header('Content-Disposition: attachment; filename="raport_plati_fitness.csv"');

$output = fopen('php://output', 'w');
fputcsv($output, ['ID Plata', 'ID User', 'Suma', 'Data']);

foreach ($plati as $p) {
    fputcsv($output, [$p['id_plata'], $p['id_user'], $p['suma'], $p['data_platii']]);
}
fclose($output);
exit();