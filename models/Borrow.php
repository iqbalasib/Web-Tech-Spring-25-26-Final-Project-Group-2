<?php
class Borrow {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getUserActiveBookIds($member_id) {
        $sql = "SELECT book_id FROM borrow_records WHERE member_id = ? AND status IN ('Pending', 'Active')";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$member_id]);
        return $stmt->fetchAll(PDO::FETCH_COLUMN);
    }

    public function requestBorrow($member_id, $book_id) {
        // 1. NEW CHECK: Does the user already have this book?
        $duplicate_sql = "SELECT COUNT(*) FROM borrow_records 
                          WHERE member_id = ? AND book_id = ? AND status IN ('Pending', 'Active')";
        $dup_stmt = $this->conn->prepare($duplicate_sql);
        $dup_stmt->execute([$member_id, $book_id]);

        if ($dup_stmt->fetchColumn() > 0) {
            return 'duplicate';
        }

        $check_sql = "SELECT (b.total_copies - COALESCE(a.borrowed, 0)) as available 
                      FROM books b 
                      LEFT JOIN (SELECT book_id, COUNT(*) as borrowed FROM borrow_records WHERE status = 'Active' GROUP BY book_id) a 
                      ON b.id = a.book_id 
                      WHERE b.id = ?";
        $stmt = $this->conn->prepare($check_sql);
        $stmt->execute([$book_id]);
        $available = $stmt->fetchColumn();

        if ($available <= 0) {
            return 'unavailable';
        }

        $sql = "INSERT INTO borrow_records (member_id, book_id, status, borrow_date, due_date) 
                VALUES (?, ?, 'Pending', CURRENT_DATE, DATE_ADD(CURRENT_DATE, INTERVAL 14 DAY))";
        $insert = $this->conn->prepare($sql);

        if ($insert->execute([$member_id, $book_id])) {
            return 'success';
        }
        return 'error';
    }

    public function getPendingRequests() {
        $sql = "SELECT br.id, br.borrow_date, m.name as member_name, b.title as book_title 
                FROM borrow_records br
                JOIN members m ON br.member_id = m.id
                JOIN books b ON br.book_id = b.id
                WHERE br.status = 'Pending'
                ORDER BY br.borrow_date ASC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getActiveLoans($search = '') {
        $sql = "SELECT br.id, br.borrow_date, br.due_date, m.name as member_name, b.title as book_title 
                FROM borrow_records br
                JOIN members m ON br.member_id = m.id
                JOIN books b ON br.book_id = b.id
                WHERE br.status = 'Active'";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND (m.name LIKE ? OR b.title LIKE ?)";
            $term = "%$search%";
            $params = [$term, $term];
        }

        $sql .= " ORDER BY br.due_date ASC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function updateStatus($record_id, $status) {
        $sql = "UPDATE borrow_records SET status = ? WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$status, $record_id]);
    }

    public function deleteRequest($record_id) {
        $sql = "DELETE FROM borrow_records WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$record_id]);
    }

    public function processReturn($record_id) {
        $sql = "UPDATE borrow_records SET status = 'Returned', return_date = CURRENT_DATE WHERE id = ?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$record_id]);
    }

    public function getBookAvailability($book_id) {
        $sql = "SELECT (b.total_copies - COALESCE(a.borrowed, 0)) as available 
                FROM books b 
                LEFT JOIN (SELECT book_id, COUNT(*) as borrowed FROM borrow_records WHERE status = 'Active' GROUP BY book_id) a 
                ON b.id = a.book_id 
                WHERE b.id = ?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$book_id]);
        return (int)$stmt->fetchColumn();
    }
}
?>