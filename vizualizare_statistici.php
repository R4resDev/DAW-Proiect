<?php
session_start();
require_once 'operatii_db.php';
require_once 'functions.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit();
}

$is_admin = ($_SESSION['user_rol'] === 'admin');
$selected_user_id = $_GET['user_id'] ?? null;

if (!$is_admin) {
    $selected_user_id = $_SESSION['user_id'];
}

$utilizatori = [];
if ($is_admin) {
    $utilizatori = OperatiiDB::read('utilizatori', "ORDER BY nume ASC");
}

$where_clause = "";
$params = [];

if ($selected_user_id) {
    $where_clause = "WHERE id_user = :uid";
    $params = ['uid' => $selected_user_id];
    $titlu_statistici = "Statistici pentru utilizatorul selectat";
} else {
    $titlu_statistici = "Statistici Globale Site";
}

$toate_vizitele = OperatiiDB::read('vizite', $where_clause, $params);
$total_accesari = count($toate_vizitele);

$logari_query = $where_clause ? $where_clause . " AND pagina_accesata = 'LOGIN_SUCCESS'" : "WHERE pagina_accesata = 'LOGIN_SUCCESS'";
$logari = OperatiiDB::read('vizite', $logari_query, $params);
$nr_logari = count($logari);

$grafic_query = $where_clause ? $where_clause . " AND data_accesarii >= DATE_SUB(NOW(), INTERVAL 7 DAY)" : "WHERE data_accesarii >= DATE_SUB(NOW(), INTERVAL 7 DAY)";
$statistici_zile = OperatiiDB::read('vizite', $grafic_query . " ORDER BY data_accesarii ASC", $params);

$date_grafic = [];
foreach ($statistici_zile as $v) {
    $zi = date('d-m', strtotime($v['data_accesarii']));
    $date_grafic[$zi] = ($date_grafic[$zi] ?? 0) + 1;
}

$labels = json_encode(array_keys($date_grafic));
$valori = json_encode(array_values($date_grafic));
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Statistici - Fitness Manager</title>
    <link rel="stylesheet" href="style.css">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <div class="container">
        <h1>Statistici 📊</h1>

        <?php if ($is_admin): ?>
        <div class="card" style="margin-bottom: 20px;">
            <form method="GET" action="">
                <label>Selectează Utilizator pentru detalii:</label>
                <select name="user_id" onchange="this.form.submit()">
                    <option value="">-- Toate statisticile globale --</option>
                    <?php foreach ($utilizatori as $u): ?>
                        <option value="<?= $u['id_user'] ?>" <?= $selected_user_id == $u['id_user'] ? 'selected' : '' ?>>
                            <?= e($u['nume']) ?> (<?= e($u['email']) ?>)
                        </option>
                    <?php endforeach; ?>
                </select>
                <noscript><button type="submit">Filtrează</button></noscript>
            </form>
        </div>
        <?php endif; ?>

        <h3><?= $titlu_statistici ?></h3>
        <div class="menu" style="display: flex; gap: 20px; margin-bottom: 30px;">
            <div class="card" style="flex: 1; text-align: center; padding: 20px; background: #f4f4f4;">
                <h2 style="font-size: 2em;"><?= $total_accesari ?></h2>
                <p>Accesări</p>
            </div>
            <div class="card" style="flex: 1; text-align: center; padding: 20px; background: #e8f5e9;">
                <h2 style="font-size: 2em; color: #2e7d32;"><?= $nr_logari ?></h2>
                <p>Logări Reușite</p>
            </div>
        </div>

        <div class="card">
            <canvas id="viziteChart"></canvas>
        </div>

        <div style="margin-top: 20px;">
            <a href="dashboard.php" class="btn">Înapoi</a>
        </div>
    </div>

    <script>
        const ctx = document.getElementById('viziteChart').getContext('2d');
        new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?= $labels ?>,
                datasets: [{
                    label: 'Activitate',
                    data: <?= $valori ?>,
                    borderColor: '#4CAF50',
                    backgroundColor: 'rgba(76, 175, 80, 0.1)',
                    fill: true
                }]
            },
            options: { responsive: true }
        });
    </script>
</body>
</html>
