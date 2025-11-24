<?php
// newsletter/index.php
session_start();
require_once '../operatii_db.php'; 

if (!isset($_SESSION['user_id']) || $_SESSION['user_rol'] !== 'admin') {
    header("Location: ../dashboard.php");
    exit();
}

$mesaj = $_GET['mesaj'] ?? '';
$clasa_mesaj = strpos($mesaj, 'succes') !== false ? 'success' : 'error';


try {
    $lista_abonati = OperatiiDB::read('newsletter', 'ORDER BY data_inscriere DESC');
} catch (Exception $e) {
    $lista_abonati = [];
    $mesaj = "Eroare la baza de date: Nu s-au putut prelua abonații.";
}
?>

<!DOCTYPE html>
<html lang="ro">
<head>
    <meta charset="UTF-8">
    <title>Lista Abonați Newsletter</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; background-color: #f4f7f6; }
        .container { max-width: 900px; margin: 20px auto; padding: 25px; background: white; border-radius: 8px; box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1); }
        h1 { color: #5cb85c; }
        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
        th, td { border: 1px solid #ddd; padding: 12px; text-align: left; }
        th { background-color: #eafaea; color: #333; }
        .success { color: #155724; background-color: #d4edda; padding: 10px; border-radius: 5px; margin-bottom: 15px;}
        .error { color: #721c24; background-color: #f8d7da; padding: 10px; border-radius: 5px; margin-bottom: 15px; }
        .delete-link { color: red; text-decoration: none; font-weight: bold; }
    </style>
</head>
<body>
    <div class="container">
        <h1>📧 Lista Abonați Newsletter (Admin)</h1>
        <p><a href="../dashboard.php">← Înapoi la Dashboard</a> | <a href="../logout.php">Deconectare</a></p>

        <?php if ($mesaj): ?>
            <div class="<?php echo $clasa_mesaj; ?>"><?php echo htmlspecialchars(urldecode($mesaj)); ?></div>
        <?php endif; ?>

        <?php if (count($lista_abonati) > 0): ?>
            <table>
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>Adresă Email</th>
                        <th>Data Abonării</th>
                        <th>Acțiuni</th> </tr>
                </thead>
                <tbody>
                    <?php foreach ($lista_abonati as $abonat): ?>
                    <tr>
                        <td><?php echo htmlspecialchars($abonat['id_inscriere']); ?></td>
                        <td><?php echo htmlspecialchars($abonat['email']); ?></td>
                        <td><?php echo htmlspecialchars($abonat['data_inscriere']); ?></td>
                        <td>
                            <a href="sterge.php?id=<?php echo htmlspecialchars($abonat['id_inscriere']); ?>" 
                               class="delete-link"
                               onclick="return confirm('Ești sigur că vrei să ștergi acest abonat (<?php echo htmlspecialchars($abonat['email']); ?>)?');">
                                Șterge
                            </a>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
            <p style="margin-top: 20px; font-weight: bold;">Total Abonați: <?php echo count($lista_abonati); ?></p>
        <?php else: ?>
            <p>Nu există încă abonați înregistrați în baza de date.</p>
        <?php endif; ?>
    </div>
</body>
</html>