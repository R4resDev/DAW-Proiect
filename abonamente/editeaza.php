<?php
// abonamente/editeaza.php
session_start();
require_once '../operatii_db.php'; 

if (!isset($_SESSION['user_id']) || $_SESSION['user_rol'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$id_abonament = $_GET['id'] ?? null;
$abonament = null;
$mesaj = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id_abonament = $_POST['id_abonament'];
    $tip_nou = trim($_POST['tip']);
    $pret_nou = trim($_POST['pret']);
    $durata_noua = trim($_POST['durata']);
    
    if (empty($tip_nou) || !is_numeric($pret_nou) || !is_numeric($durata_noua)) {
        $mesaj = "Toate câmpurile trebuie completate corect.";
    } else {
        try {
            $date_modificate = [
                'tip' => $tip_nou,
                'pret' => number_format((float)$pret_nou, 2, '.', ''),
                'durata_zile' => (int)$durata_noua
            ];
            
            $conditie_sql = "id_abonament = :id";
            $valori_conditie = ['id' => $id_abonament];

            OperatiiDB::update('abonamente', $date_modificate, $conditie_sql, $valori_conditie);
            
            header("Location: index.php?mesaj=" . urlencode("Abonamentul '$tip_nou' a fost modificat cu succes.&mesaj_tip=succes"));
            exit();
            
        } catch (Exception $e) {
            $mesaj = "Eroare la salvare: Verificați dacă noul 'Tip' este deja folosit.";
        }
    }
} 

if ($id_abonament) {
    try {
        $rezultat = OperatiiDB::read('abonamente', "WHERE id_abonament = $id_abonament");
        
        if (count($rezultat) === 1) {
            $abonament = $rezultat[0];
        } else {
            $mesaj = "Abonamentul nu a fost găsit.";
        }
    } catch (Exception $e) {
        $mesaj = "Eroare la preluarea datelor.";
    }
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Editează Abonament</title>
    <style> /* CSS */
        body { font-family: Arial, sans-serif; }
        .form-container { max-width: 500px; margin: 50px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        input[type="text"], input[type="number"] { width: 100%; padding: 10px; margin: 8px 0 15px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { background-color: orange; color: white; padding: 12px 20px; border: none; border-radius: 6px; cursor: pointer; width: 100%; }
        .error { color: red; padding: 10px; border-radius: 5px; margin-bottom: 15px; border: 1px solid red; }
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Editează Abonament (UPDATE)</h1>
        <p><a href="index.php">← Înapoi la Listă</a></p>

        <?php if ($mesaj): ?>
            <div class="error"><?php echo htmlspecialchars($mesaj); ?></div>
        <?php endif; ?>

        <?php if ($abonament): ?>
            <form method="POST" action="editeaza.php">
                <input type="hidden" name="id_abonament" value="<?php echo htmlspecialchars($abonament['id_abonament']); ?>">

                <label for="tip">Tip Abonament:</label>
                <input type="text" id="tip" name="tip" value="<?php echo htmlspecialchars($_POST['tip'] ?? $abonament['tip']); ?>" required>

                <label for="pret">Preț (RON):</label>
                <input type="number" step="0.01" id="pret" name="pret" value="<?php echo htmlspecialchars($_POST['pret'] ?? $abonament['pret']); ?>" required>

                <label for="durata">Durată (Zile):</label>
                <input type="number" id="durata" name="durata" value="<?php echo htmlspecialchars($_POST['durata'] ?? $abonament['durata_zile']); ?>" required>

                <button type="submit">Salvează Modificările</button>
            </form>
        <?php endif; ?>
    </div>
</body>
</html>