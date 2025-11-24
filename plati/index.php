<?php
// plati/index.php
session_start();
require_once '../operatii_db.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$este_admin = ($_SESSION['user_rol'] === 'admin');
$user_id = $_SESSION['user_id'];
$mesaj = '';

// 1. Preluarea Datelor de Referință (Lookup Tables)
$lista_utilizatori = [];
$lista_abonamente = [];

try {
    $utilizatori_raw = OperatiiDB::read('utilizatori', '');
    foreach ($utilizatori_raw as $u) {
        $lista_utilizatori[$u['id_user']] = $u['nume'];
    }

    $abonamente_raw = OperatiiDB::read('abonamente', '');
    foreach ($abonamente_raw as $a) {
        $lista_abonamente[$a['id_abonament']] = $a['tip'];
    }

    // 2. Preluarea Plăților
    $conditie_sql_plati = "ORDER BY data_platii DESC";
    
    if (!$este_admin) {
        $conditie_sql_plati = "WHERE id_user = $user_id " . $conditie_sql_plati;
        $titlu_pagina = "Istoric Plăți Personale";
    } else {
        $titlu_pagina = "Registrul Plăților (Admin)";
    }

    $lista_plati = OperatiiDB::read('plati', $conditie_sql_plati);

} catch (Exception $e) {
    $lista_plati = [];
    $mesaj = "Eroare la baza de date: " . $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title><?php echo htmlspecialchars($titlu_pagina); ?></title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f7f6; }
        .container { max-width: 1000px; margin: 20px auto; padding: 25px; background: white; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        h1 { color: #28a745; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #e6f7e9; color: #333; }
        .error { color: #721c24; background-color: #f8d7da; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        .admin-view th:nth-child(2), .admin-view td:nth-child(2) { display: none; }
    </style>
</head>
<body>
    <div class="container">
        <h1><?php echo htmlspecialchars($titlu_pagina); ?></h1>
        <p><a href="../dashboard.php">← Înapoi la Dashboard</a> | <a href="../logout.php">Deconectare</a></p>

        <?php if ($mesaj): ?>
            <div class="error"><?php echo htmlspecialchars($mesaj); ?></div>
        <?php endif; ?>

        <?php if (count($lista_plati) > 0): ?>
            <table class="<?php echo $este_admin ? 'admin-view' : 'client-view'; ?>">
                <thead>
                    <tr>
                        <th>ID Plată</th>
                        <th class="client-cell" style="<?php echo $este_admin ? '' : 'display: none;'; ?>">Client</th> 
                        <th>Abonament Cumpărat</th>
                        <th>Suma Plătită</th>
                        <th>Data Tranzacției</th>
                        <?php if ($este_admin): ?><th>Acțiuni</th><?php endif; ?>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista_plati as $plata): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($plata['id_plata']); ?></td>
                        <td class="client-cell" style="<?php echo $este_admin ? '' : 'display: none;'; ?>">
                            <?php echo htmlspecialchars($lista_utilizatori[$plata['id_user']] ?? 'N/A'); ?>
                        </td>
                        <td>
                            <?php echo htmlspecialchars($lista_abonamente[$plata['id_abonament']] ?? 'Abonament Șters'); ?>
                        </td>
                        <td><?php echo number_format(htmlspecialchars($plata['suma']), 2) . ' RON'; ?></td>
                        <td><?php echo htmlspecialchars($plata['data_platii']); ?></td>
                        <?php if ($este_admin): ?>
                            <td>
                                <a href="sterge_plata.php?id=<?php echo htmlspecialchars($plata['id_plata']); ?>" 
                                   style="color: red; font-weight: bold;"
                                   onclick="return confirm('Ești sigur că vrei să ștergi această plată din registru?');">Șterge</a>
                            </td>
                        <?php endif; ?>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nu există plăți înregistrate (încă).</p>
        <?php endif; ?>
    </div>
</body>
</html>