<?php
session_start();
require_once 'operatii_db.php';
require_once 'functions.php';
require_once 'track_visit.php';
require_once 'extern.php';

if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$user_id = $_SESSION['user_id'];
$rol = $_SESSION['user_rol'];
$is_admin = ($rol === 'admin');
$is_antrenor = ($rol === 'antrenor');

$user_data = OperatiiDB::read('utilizatori', "WHERE id_user = :id", ['id' => $user_id])[0];

$nume_antrenor = "";
if ($user_data['id_antrenor_asociat']) {
    $antr = OperatiiDB::read('utilizatori', "WHERE id_user = :id", ['id' => $user_data['id_antrenor_asociat']]);
    $nume_antrenor = $antr[0]['nume'] ?? "";
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Fitness Manager</title>
    <link rel="stylesheet" href="style.css">
</head>
<body>
    <div class="container">
        <header>
            <div>
                <h1>Salut, <?= e($user_data['nume']) ?>!</h1>
                <p>Acces: <strong><?= ucfirst(e($rol)) ?></strong></p>
            </div>
            <a href="logout.php" class="btn btn-danger">Ieșire</a>
        </header>

        <div class="menu">
            <?php if ($is_admin): ?>
                <div class="card admin-card">
                    <h3>Administrare</h3>
                    <a href="abonamente/index.php" class="btn">Gestiune Abonamente</a>
                    <a href="plati/index.php" class="btn">Registru Plăți</a>
                    <a href="export_plati.php" class="btn btn-secondary">Export Date Plăți</a>
                </div>
            <?php elseif ($is_antrenor): ?>
                <div class="card trainer-card">
                    <h3>Panou Antrenor</h3>
                    <a href="antrenor_clienti.php" class="btn">Clienții Mei</a>
                </div>
            <?php else: ?>
                <div class="card">
                    <h3>Abonamente</h3>
                    <a href="abonamente/index.php" class="btn">Cumpără Abonament</a>
                    <a href="plati/index.php" class="btn btn-secondary">Istoric Plăți</a>
                </div>

                <div class="card">
                    <h3>Antrenor Personal</h3>
                    <?php if ($user_data['id_antrenor_asociat']): ?>
                        <p>Ești înscris la: <strong><?= e($nume_antrenor) ?></strong></p>
                        <a href="alege_antrenor.php" class="btn btn-secondary">Schimbă Antrenorul</a>
                    <?php else: ?>
                        <a href="alege_antrenor.php" class="btn">Înscrie-te la un Antrenor</a>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="card">
                <h3>Suport și Statistici</h3>
                <a href="contact.php" class="btn">Contact Email</a>
                <a href="vizualizare_statistici.php" class="btn btn-secondary">Statistici Accesări</a>
            </div>

            <div class="card news-box">
                <?= getFitnessNews() ?>
            </div>
        </div>
    </div>
</body>
</html>