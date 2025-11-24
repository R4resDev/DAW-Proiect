<?php
// newsletter_subscribe.php
session_start();
require_once 'operatii_db.php'; 

if (!isset($_SESSION['user_id']) || !isset($_SESSION['user_email'])) {
    header("Location: login.php");
    exit();
}

$email_nl = $_SESSION['user_email'];
$mesaj = '';

try {
    $email_curat = htmlspecialchars($email_nl, ENT_QUOTES, 'UTF-8');
    $exist_email = OperatiiDB::read('newsletter', "WHERE email = '$email_curat'");

    if (count($exist_email) > 0) {
        $mesaj = "Sunteți deja abonat la newsletter.";
    } else {
        $valori = [
            'email' => $email_nl,
            'data_inscriere' => date('Y-m-d H:i:s')
        ];
        
        OperatiiDB::create('newsletter', $valori); 
        $mesaj = "V-ați abonat cu succes la newsletter!";
        $mesaj .= "&mesaj_tip=succes"; 
    }
} catch (Exception $e) {
    $mesaj = "Eroare la înregistrare în baza de date.";
}

header("Location: dashboard.php?nl_mesaj=" . urlencode($mesaj));
exit();
?>