<?php
session_start();
require_once 'operatii_db.php';
require_once 'functions.php';

if (!isset($_SESSION['user_id'])) { header("Location: login.php"); exit(); }

$id_antrenor = $_GET['id'] ?? null;
$antrenor = null;

if ($id_antrenor) {
    $rezultat = OperatiiDB::read('utilizatori', "WHERE id_user = :id AND rol = 'antrenor'", ['id' => $id_antrenor]);
    if (count($rezultat) === 1) {
        $antrenor = $rezultat[0];
    }
}

if (!$antrenor) {
    die("Antrenorul nu a fost găsit.");
}

$pret_antrenor = 150.00;
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Confirmare Plată Antrenor</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f7f6; }
        .container { max-width: 600px; margin: 50px auto; padding: 30px; background: white; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        .pret-total { font-size: 1.5em; color: #28a745; font-weight: bold; margin: 20px 0; text-align: center; border-top: 1px solid #eee; border-bottom: 1px solid #eee; padding: 15px 0; }
        button { background-color: #28a745; color: white; padding: 15px 30px; border: none; border-radius: 6px; cursor: pointer; width: 100%; font-size: 1.1em; font-weight: bold; }
        button:hover { background-color: #218838; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Confirmare Servicii Antrenor</h1>
        <p>Ați ales să lucrați cu: <strong><?php echo e($antrenor['nume']); ?></strong></p>
        <p>Serviciul include monitorizare personalizată și plan de antrenament.</p>

        <div class="pret-total">
            Total de plată: <?php echo number_format($pret_antrenor, 2); ?> RON
        </div>

        <form method="POST" action="proceseaza_plata_antrenor.php">
            <input type="hidden" name="id_antrenor" value="<?php echo $antrenor['id_user']; ?>">
            <input type="hidden" name="suma" value="<?php echo $pret_antrenor; ?>">
            <button type="submit">Confirmă și Plătește</button>
        </form>
        <p style="text-align: center; margin-top: 15px;"><a href="alege_antrenor.php">Anulează</a></p>
    </div>
</body>
</html>
