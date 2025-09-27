<?php
class Database {
    private $connection;
    private static $instance = null;

    private function __construct() {
        $config = require __DIR__ . '/config.php';
        $dbConfig = $config['database'];
        
        try {
            if ($dbConfig['type'] === 'sqlite') {
                $this->connection = new PDO(
                    'sqlite:' . $dbConfig['name'],
                    null,
                    null,
                    $dbConfig['options']
                );
            }
            
            $this->createTables();
        } catch (PDOException $e) {
            throw new Exception("Database connection failed: " . $e->getMessage());
        }
    }

    public static function getInstance() {
        if (self::$instance === null) {
            self::$instance = new self();
        }
        return self::$instance;
    }

    public function getConnection() {
        return $this->connection;
    }

    private function createTables() {
        $sql = "CREATE TABLE IF NOT EXISTS tasks (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            title TEXT NOT NULL,
            description TEXT,
            status TEXT DEFAULT 'pending',
            created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
            updated_at DATETIME DEFAULT CURRENT_TIMESTAMP
        )";

        $this->connection->exec($sql);
    }
}