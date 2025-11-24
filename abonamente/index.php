<?php
// abonamente/index.php
session_start();
require_once '../operatii_db.php'; 

if (!isset($_SESSION['user_id'])) {
    header("Location: ../login.php");
    exit();
}

$id_user_curent = $_SESSION['user_id'];
$rol_utilizator = $_SESSION['user_rol'] ?? 'client';
$mesaj = $_GET['mesaj'] ?? '';
$clasa_mesaj = strpos($mesaj, 'succes') !== false ? 'success' : 'error';

try {
    $lista_abonamente = OperatiiDB::read('abonamente', 'ORDER BY pret ASC');
    
    $plati_user = OperatiiDB::read('plati', "WHERE id_user = $id_user_curent");

    $abonamente_cumparate = [];
    foreach ($plati_user as $plata) {
        $abonamente_cumparate[$plata['id_abonament']] = true; 
    }

} catch (Exception $e) {
    $lista_abonamente = [];
    $mesaj = "Eroare la baza de date: " . $e->getMessage();
    $abonamente_cumparate = [];
}
?>
<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Lista Abonamente</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f7f6; }
        .container { background: white; padding: 25px; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        h1 { color: #007bff; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #f2f2f2; color: #333; }
        .action-link { margin-right: 10px; text-decoration: none; font-weight: bold; }
        .success { color: #155724; background-color: #d4edda; }
        .error { color: #721c24; background-color: #f8d7da; }
    </style>
</head>
<body>
    <div class="container">
        <h1>Lista Abonamente Disponibile</h1>
        <p><a href="../dashboard.php">← Înapoi la Dashboard</a> | <a href="../logout.php">Deconectare</a></p>

        <?php if ($mesaj): ?>
            <div class="<?php echo $clasa_mesaj; ?>"><?php echo htmlspecialchars($mesaj); ?></div>
        <?php endif; ?>

        <?php if ($rol_utilizator === 'admin'): ?>
            <p><a href="adauga.php" class="action-link" style="color: green;">+ Adaugă Abonament Nou</a></p>
        <?php endif; ?>

        <?php if (count($lista_abonamente) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Tip Abonament</th>
                        <th>Preț (RON)</th>
                        <th>Durată (Zile)</th>
                        <th>Acțiuni</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista_abonamente as $ab): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($ab['id_abonament']); ?></td>
                        <td><?php echo htmlspecialchars($ab['tip']); ?></td>
                        <td><?php echo number_format(htmlspecialchars($ab['pret']), 2) . ' RON'; ?></td>
                        <td><?php echo htmlspecialchars($ab['durata_zile']) . ' zile'; ?></td>
                        <td>
                            <?php 
                            $este_cumparat = isset($abonamente_cumparate[$ab['id_abonament']]);
                            
                            if ($rol_utilizator === 'admin'): ?>
                                <a href="editeaza.php?id=<?php echo htmlspecialchars($ab['id_abonament']); ?>" class="action-link" style="color: orange;">Editează</a>
                                <a href="sterge.php?id=<?php echo htmlspecialchars($ab['id_abonament']); ?>" class="action-link delete-link" style="color: red;"
                                   onclick="return confirm('Ești sigur că vrei să ștergi?');">Șterge</a>
                            
                            <?php elseif ($este_cumparat): ?>
                                <span style="color: green; font-weight: bold;">✅ Cumpărat / În uz</span>
                            
                            <?php else: ?>
                                <a href="cumpara.php?id=<?php echo htmlspecialchars($ab['id_abonament']); ?>" class="action-link" style="color: #007bff;">
                                    Cumpără Abonament
                                </a>
                            <?php endif; ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php else: ?>
            <p>Nu există abonamente înregistrate.</p>
        <?php endif; ?>
    </div>
</body>
</html>