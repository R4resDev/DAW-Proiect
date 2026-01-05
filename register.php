<?php
// register.php
session_start();
require_once 'operatii_db.php'; 
require_once 'functions.php';

if (isset($_SESSION['user_id'])) { header("Location: dashboard.php"); exit(); }

$site_key = "6LdBJCYsAAAAAG_NpGjyxwSL84G2EFUo2Ulzf_7e";
$secret_key = "6LdBJCYsAAAAADqiSYvSfzowdGkAJ7Woxgxk0vtu";
$eroare = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $captcha = $_POST['g-recaptcha-response'] ?? '';
    $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret_key&response=$captcha");
    $captcha_success = json_decode($verify);

    if (!$captcha_success->success) {
        $eroare = "Bifează reCAPTCHA!";
    } else {
        $email = trim($_POST['email']);
        $exist = OperatiiDB::read('utilizatori', "WHERE email = :e", ['e' => $email]);
        
        if (count($exist) > 0) {
            $eroare = "Email deja înregistrat.";
        } else {
            $hash = password_hash($_POST['parola'], PASSWORD_BCRYPT);
            OperatiiDB::create('utilizatori', [
                'nume' => trim($_POST['nume']),
                'email' => $email,
                'parola' => $hash,
                'rol' => 'client'
            ]);
            header("Location: login.php?mesaj=" . urlencode("Cont creat!"));
            exit();
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <title>Înregistrare - Tema 3</title>
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <style>
        body { font-family: sans-serif; background: #f4f7f6; display: flex; justify-content: center; align-items: center; min-height: 100vh; margin: 0; }
        .box { background: #fff; padding: 30px; border-radius: 8px; width: 350px; box-shadow: 0 4px 10px rgba(0,0,0,0.1); }
        input { width: 100%; padding: 10px; margin: 10px 0; box-sizing: border-box; }
        button { width: 100%; padding: 10px; background: #5cb85c; color: #fff; border: none; cursor: pointer; }
    </style>
</head>
<body>
    <div class="box">
        <h2>Creează Cont 📝</h2>
        <?php if($eroare) echo "<p style='color:red'>".e($eroare)."</p>"; ?>
        <form method="POST">
            <input type="text" name="nume" placeholder="Nume Complet" required>
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="parola" placeholder="Parolă" required>
            <div class="g-recaptcha" data-sitekey="<?php echo $site_key; ?>"></div>
            <button type="submit">Înregistrare</button>
        </form>
    </div>
</body>
</html>