<?php
require_once 'helpers/auth.php';
require_once 'models/Fine.php';

class ApiFineController {
    private $db;
    private $fineModel;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->fineModel = new Fine($this->db);
    }

    public function payFine() {
        header('Content-Type: application/json');
        auth_check('librarian');
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            echo json_encode(["status" => $this->fineModel->markAsPaid($_POST['fine_id']) ? "success" : "error"]);
            exit;
        }
    }
}
?>