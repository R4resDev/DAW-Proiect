<?php
session_start();
require_once 'functions.php';
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Contact - Fitness Manager</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
</head>
<body>
    <div class="container" style="max-width: 600px;">
        <h1>Formular Contact</h1>
        <p>Te rugăm să ne transmiți informațiile de mai jos:</p>
        
        <form action="verify_recaptcha.php" method="post">
            <label>Nume:</label>
            <input type="text" name="name" required>

            <label>Email:</label>
            <input type="email" name="email" required>

            <label>Telefon:</label>
            <input type="text" name="phone" required>

            <label>Mesaj:</label>
            <textarea name="content" rows="5" required></textarea>
            
            <div class="g-recaptcha" data-sitekey="6LdBJCYsAAAAAG_NpGjyxwSL84G2EFUo2Ulzf_7e" style="margin-bottom: 20px;"></div>

            <input type="submit" name="submit" value="Trimite Mesaj" class="btn">
            <a href="dashboard.php" class="btn btn-secondary">Înapoi</a>
        </form>
    </div>
</body>
</html>
