<?php
class Fine {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function generateFines() {
        $sql = "SELECT id, member_id, DATEDIFF(CURRENT_DATE, due_date) as overdue_days 
                FROM borrow_records 
                WHERE status = 'Active' AND due_date < CURRENT_DATE";
        $stmt = $this->conn->query($sql);
        $overdue_records = $stmt->fetchAll(PDO::FETCH_ASSOC);

        foreach ($overdue_records as $rec) {
            $amount = $rec['overdue_days'] * 5;

            $check = $this->conn->prepare("SELECT id, is_paid FROM fines WHERE borrow_record_id = ?");
            $check->execute([$rec['id']]);
            $fine = $check->fetch(PDO::FETCH_ASSOC);

            if ($fine) {
                if ($fine['is_paid'] == 0) {
                    $update = $this->conn->prepare("UPDATE fines SET amount = ? WHERE id = ?");
                    $update->execute([$amount, $fine['id']]);
                }
            } else {
                $insert = $this->conn->prepare("INSERT INTO fines (borrow_record_id, member_id, amount) VALUES (?, ?, ?)");
                $insert->execute([$rec['id'], $rec['member_id'], $amount]);
            }
        }
    }

    public function getMemberUnpaidFines($member_id) {
        $sql = "SELECT f.*, b.title, br.due_date, br.return_date, DATEDIFF(CURRENT_DATE, br.due_date) as overdue_days 
                FROM fines f
                JOIN borrow_records br ON f.borrow_record_id = br.id
                JOIN books b ON br.book_id = b.id
                WHERE f.member_id = ? AND f.is_paid = 0
                ORDER BY f.created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$member_id]);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getAllUnpaidFines($search = '') {
        $sql = "SELECT f.*, b.title, br.due_date, m.name as member_name, DATEDIFF(CURRENT_DATE, br.due_date) as overdue_days 
                FROM fines f
                JOIN borrow_records br ON f.borrow_record_id = br.id
                JOIN books b ON br.book_id = b.id
                JOIN members m ON f.member_id = m.id
                WHERE f.is_paid = 0";
        $params = [];

        if (!empty($search)) {
            $sql .= " AND m.name LIKE ?";
            $params[] = "%$search%";
        }

        $sql .= " ORDER BY f.created_at DESC";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function markAsPaid($fine_id) {
        $stmt = $this->conn->prepare("UPDATE fines SET is_paid = 1 WHERE id = ?");
        return $stmt->execute([$fine_id]);
    }
}
?>