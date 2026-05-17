<?php
class Report {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getTopBooks() {
        $sql = "SELECT b.title, COUNT(br.id) as borrow_count 
                FROM borrow_records br 
                JOIN books b ON br.book_id = b.id 
                GROUP BY br.book_id 
                ORDER BY borrow_count DESC LIMIT 10";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getTopMembers() {
        $sql = "SELECT m.name, COUNT(br.id) as loan_count 
                FROM borrow_records br 
                JOIN members m ON br.member_id = m.id 
                GROUP BY br.member_id 
                ORDER BY loan_count DESC LIMIT 10";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }

    public function getMonthlyBorrows() {
        $sql = "SELECT DATE_FORMAT(borrow_date, '%b %Y') as month, COUNT(id) as total 
                FROM borrow_records 
                WHERE borrow_date >= DATE_SUB(CURRENT_DATE, INTERVAL 6 MONTH) 
                GROUP BY month 
                ORDER BY MIN(borrow_date) ASC";
        return $this->conn->query($sql)->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>