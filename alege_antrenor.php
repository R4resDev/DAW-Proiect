<?php
// alege_antrenor.php
session_start();
require_once 'operatii_db.php';
require_once 'functions.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_rol'] !== 'client') { header("Location: login.php"); exit(); }

$user_id = $_SESSION['user_id'];
$date_user = OperatiiDB::read('utilizatori', "WHERE id_user = :id", ['id' => $user_id]);
$id_antr_actual = $date_user[0]['id_antrenor_asociat'] ?? null;

$antrenori = OperatiiDB::read('utilizatori', "WHERE rol = 'antrenor'");
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Alege Antrenor</title>
    <style>
        body { font-family: 'Segoe UI', sans-serif; background-color: #f4f7f6; margin: 20px; }
        .container { max-width: 900px; margin: auto; background: white; padding: 25px; border-radius: 12px; box-shadow: 0 4px 15px rgba(0,0,0,0.1); }
        .grid { display: grid; grid-template-columns: repeat(auto-fit, minmax(280px, 1fr)); gap: 20px; margin-top: 25px; }
        .card { border: 1px solid #ddd; padding: 25px; border-radius: 10px; text-align: center; background: #fff; transition: 0.3s; border-top: 4px solid #007bff; }
        .card:hover { transform: translateY(-5px); box-shadow: 0 8px 16px rgba(0,0,0,0.1); }
        .card.active { border-top-color: #28a745; background-color: #f0fff4; }
        .card h3 { margin-bottom: 10px; color: #333; }
        .price { font-size: 1.4em; font-weight: bold; color: #28a745; margin: 15px 0; }
        .btn { display: inline-block; padding: 12px 25px; background: #007bff; color: white; text-decoration: none; border-radius: 6px; font-weight: bold; }
        .btn-disabled { background: #6c757d; cursor: default; }
        .status { color: #28a745; font-weight: bold; margin-top: 10px; display: block; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Alege Antrenorul Tău Personal 🏋️‍♂️</h1>
        <p><a href="dashboard.php" style="text-decoration: none; color: #007bff;">← Înapoi la Dashboard</a></p>
        
        <div class="grid">
            <?php foreach ($antrenori as $a): ?>
                <?php $este_antrenorul_meu = ($a['id_user'] == $id_antr_actual); ?>
                <div class="card <?php echo $este_antrenorul_meu ? 'active' : ''; ?>">
                    <h3><?php echo e($a['nume']); ?></h3>
                    <p>Sesiuni de antrenament individuale și plan alimentar inclus.</p>
                    <div class="price">150.00 RON</div>
                    
                    <?php if ($este_antrenorul_meu): ?>
                        <span class="status">✅ Antrenorul tău curent</span>
                        <span class="btn btn-disabled">Deja înscris</span>
                    <?php else: ?>
                        <a href="cumpara_antrenor.php?id=<?php echo $a['id_user']; ?>" class="btn">Înscrie-te & Plătește</a>
                    <?php endif; ?>
                </div>
            <?php endforeach; ?>
        </div>
    </div>
</body>
</html>