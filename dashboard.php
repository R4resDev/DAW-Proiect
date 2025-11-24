<?php
// dashboard.php
session_start();

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$nume_utilizator = $_SESSION['user_nume'] ?? 'Utilizator';
$rol_utilizator = $_SESSION['user_rol'] ?? 'Necunoscut';
$nl_mesaj = $_GET['nl_mesaj'] ?? ''; 

$is_admin = ($rol_utilizator === 'admin');
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Dashboard - Fitness Manager</title>
    <style> /* CSS */
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f7f6; color: #333; margin: 0; padding: 0; }
        .container { max-width: 1000px; margin: 50px auto; padding: 30px; background: #fff; border-radius: 12px; box-shadow: 0 6px 20px rgba(0, 0, 0, 0.1); }
        header { border-bottom: 2px solid #007bff; padding-bottom: 20px; margin-bottom: 30px; display: flex; justify-content: space-between; align-items: center; }
        header h1 { margin: 0; color: #007bff; font-size: 28px; }
        .user-info { font-size: 16px; color: #555; }
        .logout-btn { background-color: #dc3545; color: white; padding: 8px 15px; text-decoration: none; border-radius: 5px; }
        .menu { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; }
        .menu-item { background: #f8f9fa; border: 1px solid #ddd; padding: 25px; border-radius: 8px; text-align: center; transition: transform 0.2s; }
        .menu-item:hover { transform: translateY(-5px); box-shadow: 0 0 15px rgba(0, 0, 0, 0.1); }
        .menu-item a { text-decoration: none; font-size: 18px; color: #007bff; font-weight: 600; display: block; }
        .rol-admin { color: #28a745; font-weight: bold; }
        .highlight { background: #ffe0b2 !important; border-color: #ffcc80 !important; }
        .success { color: #155724; background-color: #d4edda; border: 1px solid #c3e6cb; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        .error { color: #721c24; background-color: #f8d7da; border: 1px solid #f5c6cb; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
    </style>
</head>
<body>
    <div class="container">
        <header>
            <h1>Panou de Control (Dashboard)</h1>
            <div class="user-info">
                Bine ai venit, <strong><?php echo htmlspecialchars($nume_utilizator); ?></strong>! 
                (Rol: <span class="<?php echo ($is_admin ? 'rol-admin' : 'rol-client'); ?>"><?php echo htmlspecialchars(ucfirst($rol_utilizator)); ?></span>)
                <a href="logout.php" class="logout-btn">Deconectare</a>
            </div>
        </header>
        
        <?php if ($nl_mesaj): ?>
            <div class="<?php echo (strpos($nl_mesaj, 'succes') !== false ? 'success' : 'error'); ?>"
                 style="padding: 10px; border-radius: 5px; margin-bottom: 20px; font-weight: bold; border: 1px solid;">
                <?php echo htmlspecialchars(urldecode($nl_mesaj)); ?>
            </div>
        <?php endif; ?>


        <h2>Funcționalități</h2>
        
        <div class="menu">
            
            <?php if ($is_admin): ?>
                
                <div class="menu-item highlight">
                    <a href="abonamente/index.php">🛠️ Gestiune Abonamente</a>
                    <p>Adaugă, editează și șterge tipuri de abonamente.</p>
                </div>
                
                <div class="menu-item highlight">
                    <a href="plati/index.php">📈 Registrul Plăților</a>
                    <p>Vizualizează toate tranzacțiile din sistem.</p>
                </div>

                <div class="menu-item highlight">
                    <a href="newsletter/index.php">📋 Lista Abonați Newsletter</a>
                    <p>Vizualizează și gestionează lista de emailuri înscrise.</p>
                </div>
                
            <?php else: ?>
            
                <div class="menu-item">
                    <a href="abonamente/index.php">🎟️ Vizualizare & Cumpărare Abonamente</a>
                    <p>Alege și cumpără un abonament nou.</p>
                </div>

                <div class="menu-item">
                    <a href="plati/index.php">🧾 Istoric Plăți</a>
                    <p>Vizualizează tranzacțiile tale anterioare.</p>
                </div>
                
                <div class="menu-item">
                    <a href="newsletter_subscribe.php">📧 Abonează-te la Newsletter</a>
                    <p>Primește oferte și noutăți direct pe email (Înscriere instantanee).</p>
                </div>
                
            <?php endif; ?>

        </div>
    </div>
</body>
</html>