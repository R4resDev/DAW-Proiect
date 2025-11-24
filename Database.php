<?php
// Database.php (Conexiunea Singleton)
class Database {
    private static ?Database $instance = null; 
    private PDO $connection;

    // Setări pentru XAMPP
    private string $host = "localhost";
    private string $dbName = "sala_de_fitness"; 
    private string $username = "root";
    private string $password = "";

    // Private constructor
    private function __construct() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbName};charset=utf8mb4";
            // Setări PDO
            $this->connection = new PDO($dsn, $this->username, $this->password, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            // Oprește execuția dacă nu se poate conecta la DB
            die("Database connection failed: " . $e->getMessage());
        }
    }

    private function __clone() {}
    public function __wakeup() {
        throw new Exception("Cannot unserialize a singleton.");
    }

    public static function getInstance(): Database {
        if (self::$instance === null) {
            self::$instance = new Database();
        }
        return self::$instance;
    }

    public function getConnection(): PDO {
        return $this->connection;
    }
}
?>