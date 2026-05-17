<?php
require_once 'helpers/auth.php';
require_once 'models/Fine.php';

class FineController {
    private $db;
    private $fineModel;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->fineModel = new Fine($this->db);
    }

    public function myFines() {
        auth_check('member');
        $fines = $this->fineModel->getMemberUnpaidFines($_SESSION['member_id']);

        $total_balance = 0;
        foreach($fines as $fine) {
            $total_balance += $fine['amount'];
        }

        include 'views/member/fines.php';
    }

    public function librarianDashboard() {
        auth_check('librarian');
        $search = $_GET['q'] ?? '';
        $fines = $this->fineModel->getAllUnpaidFines($search);

        include 'views/librarian/fines.php';
    }
}
?>