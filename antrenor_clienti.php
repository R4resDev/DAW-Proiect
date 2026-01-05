<?php
// antrenor_clienti.php
session_start();
require_once 'operatii_db.php';
require_once 'functions.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_rol'] !== 'antrenor') { header("Location: login.php"); exit(); }

$antrenor_id = $_SESSION['user_id'];

// Preluăm clienții care au acest antrenor asociat
$clienti = OperatiiDB::read('utilizatori', 
    "WHERE id_antrenor_asociat = :id_a", 
    ['id_a' => $antrenor_id]
);
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Clienții Mei</title>
    <style>
        body { font-family: 'Segoe UI', Arial, sans-serif; background-color: #f4f7f6; margin: 20px; }
        .container { max-width: 900px; margin: 20px auto; padding: 30px; background: white; border-radius: 12px; box-shadow: 0 4px 12px rgba(0,0,0,0.1); }
        h1 { color: #28a745; border-bottom: 2px solid #28a745; padding-bottom: 10px; margin-bottom: 20px; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 15px; text-align: left; }
        th { background-color: #e6f7e9; color: #333; font-weight: bold; }
        tr:nth-child(even) { background-color: #f9f9f9; }
        tr:hover { background-color: #f1f1f1; }
        .no-data { text-align: center; color: #666; padding: 20px; }
        .back-link { text-decoration: none; color: #007bff; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h1>📋 Lista Clienților Înscriși</h1>
        <p><a href="dashboard.php" class="back-link">← Înapoi la Dashboard</a></p>
        
        <?php if (count($clienti) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>Nume Client</th>
                        <th>Email de Contact</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($clienti as $c): ?>
                        <tr>
                            <td><strong><?php echo e($c['nume']); ?></strong></td>
                            <td><?php echo e($c['email']); ?></td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <p style="margin-top: 20px;">Total clienți activi: <strong><?php echo count($clienti); ?></strong></p>
        <?php else: ?>
            <div class="no-data">
                <p>Momentan nu ai niciun client înscris la antrenamentele tale.</p>
            </div>
        <?php endif; ?>
    </div>
</body>
</html>