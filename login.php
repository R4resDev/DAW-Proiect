<?php
// login.php
session_start();
require_once 'operatii_db.php'; 
require_once 'functions.php';

if (isset($_SESSION['user_id'])) { header("Location: dashboard.php"); exit(); }

$site_key = "6LdBJCYsAAAAAG_NpGjyxwSL84G2EFUo2Ulzf_7e";
$secret_key = "6LdBJCYsAAAAADqiSYvSfzowdGkAJ7Woxgxk0vtu";
$eroare = ''; 
$mesaj = $_GET['mesaj'] ?? '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Verificare reCAPTCHA
    $captcha = $_POST['g-recaptcha-response'] ?? '';
    $verify = file_get_contents("https://www.google.com/recaptcha/api/siteverify?secret=$secret_key&response=$captcha");
    $captcha_success = json_decode($verify);

    if (!$captcha_success->success) {
        $eroare = "Te rugăm să bifezi reCAPTCHA!";
    } else {
        $email = trim($_POST['email']);
        $user = OperatiiDB::read('utilizatori', "WHERE email = :e", ['e' => $email]);
        if (count($user) === 1 && password_verify($_POST['parola'], $user[0]['parola'])) {
            $_SESSION['user_id'] = $user[0]['id_user'];
            $_SESSION['user_rol'] = $user[0]['rol'];
            $_SESSION['user_nume'] = $user[0]['nume'];
            $_SESSION['user_email'] = $user[0]['email'];

            // Înregistrăm logarea legată de ID-ul utilizatorului
            OperatiiDB::create('vizite', [
                'ip_vizitator' => $_SERVER['REMOTE_ADDR'],
                'pagina_accesata' => 'LOGIN_SUCCESS', 
                'id_user' => $_SESSION['user_id'], // Adăugăm ID-ul pentru raportare exactă
                'data_accesarii' => date('Y-m-d H:i:s')
            ]);

            header("Location: dashboard.php");
            exit();
        } else {
            $eroare = "Email sau parolă greșită!";
        }
    }
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Autentificare</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <div class="container" style="max-width: 400px; margin-top: 100px;">
        <h1>Autentificare 🔑</h1>
        
        <?php if($eroare) echo "<p class='badge' style='background:red; width:100%; box-sizing:border-box;'>".e($eroare)."</p>"; ?>
        <?php if($mesaj) echo "<p class='badge' style='background:green; width:100%; box-sizing:border-box;'>".e($mesaj)."</p>"; ?>
        
        <form method="POST">
            <input type="email" name="email" placeholder="Email" required>
            <input type="password" name="parola" placeholder="Parolă" required>
            
            <div class="g-recaptcha" data-sitekey="<?php echo $site_key; ?>" style="margin-bottom: 15px;"></div>
            
            <button type="submit" class="btn">Loghează-te</button>
        </form>
        <p style="text-align:center">Nu ai cont? <a href="register.php">Creează cont nou</a></p>
    </div>
</body>
</html>