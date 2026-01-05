<?php
require_once 'operatii_db.php';

$ip = $_SERVER['REMOTE_ADDR'];
$pagina = $_SERVER['REQUEST_URI'];
$user_id = $_SESSION['user_id'] ?? null;

OperatiiDB::create('vizite', [
    'ip_vizitator' => $ip,
    'pagina_accesata' => $pagina,
    'id_user' => $user_id,
    'data_accesarii' => date('Y-m-d H:i:s')
]);
?>
