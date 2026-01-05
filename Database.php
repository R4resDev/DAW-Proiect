<?php
class Database {
    private static ?Database $instance = null; 
    private PDO $connection;

    private string $host = "localhost";
    private string $dbName = "rneculas_sala_de_fitness"; 
    private string $username = "rneculas_sala_de_fitness";
    private string $password = "vtwBZpsWL7kbbMxU6hZq";

    private function __construct() {
        try {
            $dsn = "mysql:host={$this->host};dbname={$this->dbName};charset=utf8mb4";
            $this->connection = new PDO($dsn, $this->username, $this->password, [
                PDO::ATTR_ERRMODE            => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
                PDO::ATTR_EMULATE_PREPARES   => false,
            ]);
        } catch (PDOException $e) {
            die("Database connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance(): Database {
        if (self::$instance === null) { self::$instance = new Database(); }
        return self::$instance;
    }

    public function getConnection(): PDO { return $this->connection; }
}
?>