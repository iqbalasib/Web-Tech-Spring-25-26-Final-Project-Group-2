<?php
class Genre {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAll() {
        $stmt = $this->conn->query("SELECT * FROM genres ORDER BY name ASC");
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function add($name) {
        $stmt = $this->conn->prepare("INSERT INTO genres (name) VALUES (?)");
        try {
            return $stmt->execute([trim($name)]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function update($id, $name) {
        $stmt = $this->conn->prepare("UPDATE genres SET name = ? WHERE id = ?");
        try {
            return $stmt->execute([trim($name), $id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function delete($id) {
        $check = $this->conn->prepare("SELECT COUNT(*) FROM books WHERE genre_id = ?");
        $check->execute([$id]);
        if ($check->fetchColumn() > 0) {
            return false;
        }

        $stmt = $this->conn->prepare("DELETE FROM genres WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>