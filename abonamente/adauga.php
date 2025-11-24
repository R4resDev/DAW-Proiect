<?php
// abonamente/adauga.php
session_start();
require_once '../operatii_db.php'; 

if (!isset($_SESSION['user_id']) || $_SESSION['user_rol'] !== 'admin') {
    header("Location: ../login.php");
    exit();
}

$mesaj = '';
$tip = $pret = $durata = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $tip = trim($_POST['tip']);
    $pret = trim($_POST['pret']);
    $durata = trim($_POST['durata']);
    
    if (empty($tip) || !is_numeric($pret) || !is_numeric($durata) || $pret <= 0 || $durata <= 0) {
        $mesaj = "Toate câmpurile trebuie completate corect.";
    } else {
        try {
            $date_inserare = [
                'tip' => $tip,
                'pret' => number_format((float)$pret, 2, '.', ''), 
                'durata_zile' => (int)$durata
            ];
            
            $id_nou = OperatiiDB::create('abonamente', $date_inserare);
            
            header("Location: index.php?mesaj=" . urlencode("Abonamentul '$tip' a fost adăugat cu succes (ID: $id_nou).&mesaj_tip=succes"));
            exit();
            
        } catch (Exception $e) {
            $mesaj = "Eroare la adăugare: Verificați dacă tipul abonamentului există deja. Eroare DB: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Adaugă Abonament</title>
    <style> /* CSS */
        body { font-family: Arial, sans-serif; }
        .form-container { max-width: 500px; margin: 50px auto; padding: 20px; background: white; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        input[type="text"], input[type="number"] { width: 100%; padding: 10px; margin: 8px 0 15px 0; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box; }
        button { background-color: #28a745; color: white; padding: 12px 20px; border: none; border-radius: 6px; cursor: pointer; width: 100%; }
        .error { color: red; padding: 10px; border-radius: 5px; margin-bottom: 15px; border: 1px solid red;}
    </style>
</head>
<body>
    <div class="form-container">
        <h1>Adaugă Abonament Nou</h1>
        <p><a href="index.php">← Înapoi la Listă</a></p>

        <?php if ($mesaj): ?>
            <div class="error"><?php echo htmlspecialchars($mesaj); ?></div>
        <?php endif; ?>

        <form method="POST" action="adauga.php">
            <label for="tip">Tip Abonament:</label>
            <input type="text" id="tip" name="tip" value="<?php echo htmlspecialchars($tip); ?>" required>

            <label for="pret">Preț (RON):</label>
            <input type="number" step="0.01" id="pret" name="pret" value="<?php echo htmlspecialchars($pret); ?>" required>

            <label for="durata">Durată (Zile):</label>
            <input type="number" id="durata" name="durata" value="<?php echo htmlspecialchars($durata); ?>" required>

            <button type="submit">Adaugă Abonament</button>
        </form>
    </div>
</body>
</html>