<?php
// abonamente/cumpara.php
session_start();
require_once '../operatii_db.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$id_user = $_SESSION['user_id'];
$id_abonament = $_GET['id'] ?? null;
$abonament = null;
$mesaj_eroare = '';

if (!$id_abonament) {
    header("Location: index.php?mesaj=" . urlencode("Eroare: Nu a fost specificat ID-ul abonamentului."));
    exit();
}

try {
    $rezultat = OperatiiDB::read('abonamente', "WHERE id_abonament = $id_abonament");
    
    if (count($rezultat) === 1) {
        $abonament = $rezultat[0];
    } else {
        $mesaj_eroare = "Abonamentul selectat nu există.";
    }
} catch (Exception $e) {
    $mesaj_eroare = "Eroare la preluarea datelor.";
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Confirmare Cumpărare</title>
    <style>
        body { font-family: Arial, sans-serif; }
        .container { max-width: 600px; margin: 50px auto; padding: 30px; background: white; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        .pret-total { font-size: 1.5em; color: #28a745; font-weight: bold; margin: 20px 0; text-align: center; }
        button { background-color: #28a745; color: white; padding: 15px 30px; border: none; border-radius: 6px; cursor: pointer; width: 100%; }
        .error { color: red; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Confirmare Cumpărare Abonament</h1>
        <p><a href="index.php">← Înapoi la Lista Abonamente</a></p>

        <?php if ($mesaj_eroare): ?>
            <div class="error"><?php echo htmlspecialchars($mesaj_eroare); ?></div>
        <?php elseif ($abonament): ?>
            <p>Tip Abonament: <strong><?php echo htmlspecialchars($abonament['tip']); ?></strong></p>
            <p>Durată: <strong><?php echo htmlspecialchars($abonament['durata_zile']); ?> zile</strong></p>

            <div class="pret-total">
                Total de plată: <?php echo number_format(htmlspecialchars($abonament['pret']), 2) . ' RON'; ?>
            </div>

            <form method="POST" action="proceseaza_plata.php">
                <input type="hidden" name="id_abonament" value="<?php echo htmlspecialchars($abonament['id_abonament']); ?>">
                <input type="hidden" name="suma" value="<?php echo htmlspecialchars($abonament['pret']); ?>">
                <input type="hidden" name="id_user" value="<?php echo htmlspecialchars($id_user); ?>">

                <button type="submit" name="confirma_plata">Confirmă Plata (Simulare)</button>
            </form>

        <?php endif; ?>
    </div>
</body>
</html>