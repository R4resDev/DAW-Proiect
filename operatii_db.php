<?php
require_once 'Database.php';

class OperatiiDB {
    public static function read(string $tabel, string $conditie = "", array $parametri = []): array {
        $conn = Database::getInstance()->getConnection();
        $sql = "SELECT * FROM $tabel " . $conditie;
        $stmt = $conn->prepare($sql);
        $stmt->execute($parametri);
        return $stmt->fetchAll();
    }

    public static function create(string $tabel, array $valori): int {
        $conn = Database::getInstance()->getConnection();
        $coloane = implode(", ", array_keys($valori));
        $placeholders = ":" . implode(", :", array_keys($valori));
        $sql = "INSERT INTO $tabel ($coloane) VALUES ($placeholders)";
        $stmt = $conn->prepare($sql);
        $stmt->execute($valori);
        return (int)$conn->lastInsertId();
    }

    public static function update(string $tabel, array $date, string $conditie, array $parametriConditie): bool {
        $conn = Database::getInstance()->getConnection();
        $sets = [];
        foreach (array_keys($date) as $cheie) { $sets[] = "$cheie = :$cheie"; }
        $sql = "UPDATE $tabel SET " . implode(", ", $sets) . " WHERE $conditie";
        $stmt = $conn->prepare($sql);
        return $stmt->execute(array_merge($date, $parametriConditie));
    }

    public static function delete(string $tabel, string $conditie, array $parametri): bool {
        $conn = Database::getInstance()->getConnection();
        $sql = "DELETE FROM $tabel WHERE $conditie";
        $stmt = $conn->prepare($sql);
        return $stmt->execute($parametri);
    }
}
?>