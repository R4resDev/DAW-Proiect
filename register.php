<?php
// register.php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
require_once 'operatii_db.php'; 

$eroare = '';
$nume = $email = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nume = trim($_POST['nume'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $parola = $_POST['parola'] ?? '';

    if (empty($nume) || empty($email) || empty($parola) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $eroare = "Vă rugăm completați toate câmpurile corect.";
    } else {
        try {
            $exist_email = OperatiiDB::read('utilizatori', "WHERE email = '$email'");
            if (count($exist_email) > 0) {
                $eroare = "Acest email este deja înregistrat. Încercați să vă autentificați.";
            } else {
                $date_inserare = [
                    'nume' => $nume,
                    'email' => $email,
                    'parola' => $parola, 
                    'rol' => 'client'
                ];
                
                OperatiiDB::create('utilizatori', $date_inserare);
                
                header("Location: login.php?mesaj=" . urlencode("Contul a fost creat cu succes! Vă rugăm să vă autentificați."));
                exit();
            }

        } catch (Exception $e) {
            $eroare = "A apărut o eroare la baza de date: " . $e->getMessage();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Înregistrare Cont Nou</title>
    <style> /* CSS */
        body { font-family: 'Segoe UI', sans-serif; background-color: #e9ebee; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .container { background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); width: 350px; }
        .error { color: #d9534f; background-color: #f2dede; border: 1px solid #ebccd1; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        input { width: 100%; padding: 12px; margin-bottom: 20px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box; }
        button { background-color: #5cb85c; color: white; padding: 12px 20px; border: none; border-radius: 6px; cursor: pointer; width: 100%; }
    </style>
</head>
<body>
    <div class="container">
        <h2>Înregistrare Cont Nou 📝</h2>
        <?php if ($eroare): ?>
            <p class="error"><?php echo htmlspecialchars($eroare); ?></p>
        <?php endif; ?>
        <form method="POST" action="register.php">
            <label for="nume">Nume Complet</label>
            <input type="text" id="nume" name="nume" required value="<?php echo htmlspecialchars($nume); ?>">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required value="<?php echo htmlspecialchars($email); ?>">
            <label for="parola">Parolă</label>
            <input type="password" id="parola" name="parola" required>
            <button type="submit">Înregistrare</button>
        </form>
        <p style="text-align: center; margin-top: 15px;"><a href="login.php">Ai deja cont? Autentifică-te.</a></p>
    </div>
</body>
</html>