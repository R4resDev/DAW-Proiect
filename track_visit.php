<?php
// track_visit.php
require_once 'operatii_db.php';
// session_start(); // Asigură-te că sesiunea e pornită înainte de includere

$ip = $_SERVER['REMOTE_ADDR'];
$pagina = $_SERVER['REQUEST_URI'];
$user_id = $_SESSION['user_id'] ?? null; // Luăm ID-ul dacă este logat

OperatiiDB::create('vizite', [
    'ip_vizitator' => $ip,
    'pagina_accesata' => $pagina,
    'id_user' => $user_id, // Salvăm ID-ul utilizatorului logat
    'data_accesarii' => date('Y-m-d H:i:s')
]);
?>