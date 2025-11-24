<?php
// operatii_db.php (Clasa CRUD)

class OperatiiDB{

    public static function read(string $tabel, string $query): array {
        require_once 'Database.php';

        $conn = Database::getInstance()->getConnection();
        $sql = "SELECT * FROM $tabel " . $query; 
        $stmt = $conn->prepare($sql);
        $stmt->execute(); 

        return $stmt->fetchAll();
    }
    
    public static function custom_read(string $sql, array $parametri = []): array {
        require_once 'Database.php';

        $conn = Database::getInstance()->getConnection();
        
        $stmt = $conn->prepare($sql);
        $stmt->execute($parametri); 

        return $stmt->fetchAll();
    }


    public static function create(string $tabel, array $valori): int {
        require_once 'Database.php';

        $conn = Database::getInstance()->getConnection();
        
        $coloaneNeformatate = implode(",",array_keys($valori));
        $placeholders = ":" . implode(", :", array_keys($valori));

        $sql = "INSERT INTO $tabel ($coloaneNeformatate) VALUES ($placeholders)";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute($valori);

        return $conn->lastInsertId();
    }

    public static function update(string $tabel, array $valoriSet, string $conditieSQL, array $valoriConditie = []): void {
        require_once 'Database.php';

        $conn = Database::getInstance()->getConnection();
        $setClauses = [];
        
        foreach (array_keys($valoriSet) as $coloana) {
            $setClauses[] = $coloana . "=:" . $coloana;
        }
        $setClausesString = implode(", ", $setClauses);

        $parametriExecutie = array_merge($valoriSet, $valoriConditie);

        $sql = "UPDATE $tabel SET $setClausesString WHERE $conditieSQL";
        
        $stmt = $conn->prepare($sql);
        $stmt->execute($parametriExecutie);
    }

    public static function delete(string $tabel, string $conditieSQL, array $valoriConditie = []): void {
        require_once 'Database.php';

        $conn = Database::getInstance()->getConnection();

        $sql = "DELETE FROM $tabel WHERE $conditieSQL"; 
        $stmt = $conn->prepare($sql);
        $stmt->execute($valoriConditie); 
    }
}
?>