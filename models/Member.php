<?php
class Member {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function register($name, $email, $phone, $password) {
        $hash = password_hash($password, PASSWORD_DEFAULT);

        $stmt = $this->conn->prepare("INSERT INTO members (name, email, phone, password_hash, role) VALUES (?, ?, ?, ?, 'member')");

        try {
            return $stmt->execute([$name, $email, $phone, $hash]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function login($email, $password) {
        $stmt = $this->conn->prepare("SELECT * FROM members WHERE email = ? LIMIT 1");
        $stmt->execute([$email]);
        $user = $stmt->fetch(PDO::FETCH_ASSOC);

        if ($user && password_verify($password, $user['password_hash'])) {
            return $user;
        }
        return false;
    }

    public function getMemberById($id) {
        $stmt = $this->conn->prepare("SELECT * FROM members WHERE id = ? LIMIT 1");
        $stmt->execute([$id]);
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public function updateProfile($id, $name, $email, $phone) {
        $stmt = $this->conn->prepare("UPDATE members SET name = ?, email = ?, phone = ? WHERE id = ?");
        try {
            return $stmt->execute([$name, $email, $phone, $id]);
        } catch (PDOException $e) {
            return false;
        }
    }

    public function updatePassword($id, $new_password) {
        $hash = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $this->conn->prepare("UPDATE members SET password_hash = ? WHERE id = ?");
        return $stmt->execute([$hash, $id]);
    }

    public function getDashboardStats($member_id) {
        $stats = [
            'active_loans' => 0,
            'upcoming_due' => 0,
            'outstanding_fines' => 0.00
        ];

        $stmt1 = $this->conn->prepare("SELECT COUNT(*) FROM borrow_records WHERE member_id = ? AND status = 'Active'");
        $stmt1->execute([$member_id]);
        $stats['active_loans'] = $stmt1->fetchColumn();

        $stmt2 = $this->conn->prepare("SELECT COUNT(*) FROM borrow_records WHERE member_id = ? AND status = 'Active' AND due_date <= DATE_ADD(CURRENT_DATE, INTERVAL 7 DAY)");
        $stmt2->execute([$member_id]);
        $stats['upcoming_due'] = $stmt2->fetchColumn();

        $stmt3 = $this->conn->prepare("SELECT SUM(amount) FROM fines WHERE member_id = ? AND is_paid = 0");
        $stmt3->execute([$member_id]);
        $stats['outstanding_fines'] = $stmt3->fetchColumn() ?: 0.00;

        return $stats;
    }
}
?>