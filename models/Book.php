<?php
class Book {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    private function getBaseQuery() {
        return "SELECT b.*, g.name as genre_name, 
                       (b.total_copies - COALESCE(active_loans.borrowed_count, 0)) as available_copies 
                FROM books b 
                LEFT JOIN genres g ON b.genre_id = g.id 
                LEFT JOIN (
                    SELECT book_id, COUNT(*) as borrowed_count 
                    FROM borrow_records 
                    WHERE status = 'Active' 
                    GROUP BY book_id
                ) active_loans ON b.id = active_loans.book_id";
    }

    public function getAllWithAvailability() {
        $sql = $this->getBaseQuery() . " ORDER BY b.created_at DESC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getBookById($id) {
        $sql = $this->getBaseQuery() . " WHERE b.id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function searchBooks($keyword) {
        $sql = $this->getBaseQuery() . " WHERE b.title LIKE ? OR b.author LIKE ? OR b.isbn LIKE ? ORDER BY b.title ASC";
        $stmt = $this->conn->prepare($sql);
        $term = "%$keyword%";
        $stmt->execute([$term, $term, $term]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function add($genre_id, $title, $author, $isbn, $total_copies, $shelf, $year) {
        $sql = "INSERT INTO books (genre_id, title, author, isbn, total_copies, shelf_location, published_year) 
                VALUES (?, ?, ?, ?, ?, ?, ?)";
        $stmt = $this->conn->prepare($sql);
        try {
            return $stmt->execute([$genre_id, $title, $author, $isbn, $total_copies, $shelf, $year]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function update($id, $genre_id, $title, $author, $isbn, $total_copies, $shelf, $year) {
        $sql = "UPDATE books SET genre_id = ?, title = ?, author = ?, isbn = ?, 
                total_copies = ?, shelf_location = ?, published_year = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        try {
            return $stmt->execute([$genre_id, $title, $author, $isbn, $total_copies, $shelf, $year, $id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function delete($id) {
        $check = $this->conn->prepare("SELECT COUNT(*) FROM borrow_records WHERE book_id = ? AND status = 'Active'");
        $check->execute([$id]);
        if ($check->fetchColumn() > 0) {
            return false;
        }

        $stmt = $this->conn->prepare("DELETE FROM books WHERE id = ?");
        return $stmt->execute([$id]);
    }
}
?>