<?php
require_once 'helpers/auth.php';
require_once 'models/Borrow.php';

class ApiBorrowController {
    private $db;
    private $borrowModel;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->borrowModel = new Borrow($this->db);
    }

    public function requestBorrow() {
        header('Content-Type: application/json');
        auth_check('member');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $result = $this->borrowModel->requestBorrow($_SESSION['member_id'], $_POST['book_id']);
            if ($result === 'success') {
                echo json_encode(["status" => "success", "message" => "Borrow request submitted! Wait for librarian approval."]);
            } elseif ($result === 'duplicate') {
                echo json_encode(["status" => "error", "message" => "You already have a Pending or Active loan for this exact book!"]);
            } else {
                echo json_encode(["status" => "error", "message" => "Failed to request. All copies might be currently borrowed."]);
            }
            exit;
        }
    }

    public function approve() {
        header('Content-Type: application/json');
        auth_check('librarian');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo json_encode(["status" => $this->borrowModel->updateStatus($_POST['record_id'], 'Active') ? "success" : "error"]);
            exit;
        }
    }

    public function reject() {
        header('Content-Type: application/json');
        auth_check('librarian');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo json_encode(["status" => $this->borrowModel->deleteRequest($_POST['record_id']) ? "success" : "error"]);
            exit;
        }
    }

    public function returnBook() {
        header('Content-Type: application/json');
        auth_check('librarian');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo json_encode(["status" => $this->borrowModel->processReturn($_POST['record_id']) ? "success" : "error"]);
            exit;
        }
    }

    public function checkAvailability() {
        header('Content-Type: application/json');
        if (isset($_GET['book_id'])) {
            echo json_encode(["available_copies" => $this->borrowModel->getBookAvailability($_GET['book_id'])]);
            exit;
        }
    }
}
?>