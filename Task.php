<?php
class Task {
    private $db;
    private $table = 'tasks';

    public function __construct() {
        $this->db = Database::getInstance()->getConnection();
    }

    public function getAll() {
        $stmt = $this->db->query("SELECT * FROM {$this->table} ORDER BY created_at DESC");
        return $stmt->fetchAll();
    }

    public function getById($id) {
        $stmt = $this->db->prepare("SELECT * FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    public function create($data) {
        $sql = "INSERT INTO {$this->table} (title, description, status) VALUES (?, ?, ?)";
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['title'],
            $data['description'] ?? '',
            $data['status'] ?? 'pending'
        ]);
        
        return $this->db->lastInsertId();
    }

    public function update($id, $data) {
        $sql = "UPDATE {$this->table} SET 
                title = ?, 
                description = ?, 
                status = ?, 
                updated_at = CURRENT_TIMESTAMP 
                WHERE id = ?";
        
        $stmt = $this->db->prepare($sql);
        $stmt->execute([
            $data['title'],
            $data['description'] ?? '',
            $data['status'] ?? 'pending',
            $id
        ]);
        
        return $stmt->rowCount();
    }

    public function delete($id) {
        $stmt = $this->db->prepare("DELETE FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->rowCount();
    }

    public function taskExists($id) {
        $stmt = $this->db->prepare("SELECT COUNT(*) FROM {$this->table} WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetchColumn() > 0;
    }
}