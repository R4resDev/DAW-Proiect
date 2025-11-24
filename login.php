<?php
// login.php
session_start();
if (isset($_SESSION['user_id'])) {
    header("Location: dashboard.php");
    exit();
}
require_once 'operatii_db.php'; 

$eroare = '';
$email = $parola_introdusa = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $parola_introdusa = $_POST['parola'] ?? '';
    if (empty($email) || empty($parola_introdusa)) {
        $eroare = "Vă rugăm introduceți atât email-ul, cât și parola.";
    } else {
        try {
            $email_curat = htmlspecialchars($email, ENT_QUOTES, 'UTF-8');
            $conditie_sql = "WHERE email = '$email_curat'";
            $user_data = OperatiiDB::read('utilizatori', $conditie_sql);
            
            if (count($user_data) === 1) {
                $user = $user_data[0];
                
                if ($user['parola'] === $parola_introdusa) {
                    $_SESSION['user_id'] = $user['id_user'];
                    $_SESSION['user_rol'] = $user['rol'];
                    $_SESSION['user_nume'] = $user['nume'];
                    $_SESSION['user_email'] = $user['email'];
                    
                    header("Location: dashboard.php");
                    exit();
                } else {
                    $eroare = "Parolă incorectă.";
                }
            } else {
                $eroare = "Acest email nu este înregistrat.";
            }

        } catch (Exception $e) {
            $eroare = "A apărut o eroare la baza de date.";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Autentificare - Fitness Manager</title>
    <style> /* CSS */
        body { font-family: 'Segoe UI', sans-serif; background-color: #e9ebee; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .login-container { background: #fff; padding: 30px; border-radius: 10px; box-shadow: 0 4px 12px rgba(0, 0, 0, 0.15); width: 350px; }
        .error { color: #d9534f; background-color: #f2dede; border: 1px solid #ebccd1; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        input[type="email"], input[type="password"] { width: 100%; padding: 12px; margin-bottom: 20px; border: 1px solid #ccc; border-radius: 6px; box-sizing: border-box; }
        button { background-color: #007bff; color: white; padding: 12px 20px; border: none; border-radius: 6px; cursor: pointer; width: 100%; }
        .register-link { text-align: center; margin-top: 15px; }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Intră în cont 🔑</h2>
        <?php if ($eroare): ?>
            <p class="error"><?php echo $eroare; ?></p>
        <?php endif; ?>
        <form method="POST" action="login.php">
            <label for="email">Email</label>
            <input type="email" id="email" name="email" required placeholder="exemplu@fitness.ro" value="<?php echo htmlspecialchars($email); ?>">
            <label for="parola">Parolă</label>
            <input type="password" id="parola" name="parola" required placeholder="••••••••">
            <button type="submit">Autentificare</button>
        </form>
        <div class="register-link"><a href="register.php">Nu ai cont? Înregistrează-te.</a></div>
    </div>
</body>
</html>