<?php
require_once 'helpers/auth.php';
require_once 'models/Book.php';
require_once 'models/Borrow.php';

class BorrowController {
    private $db;
    private $bookModel;
    private $borrowModel;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->bookModel = new Book($this->db);
        $this->borrowModel = new Borrow($this->db);
    }

    public function memberBrowse() {
        auth_check('member');
        $books = $this->bookModel->getAllWithAvailability();

        $user_borrowed_books = $this->borrowModel->getUserActiveBookIds($_SESSION['member_id']);

        include 'views/member/browse.php';
    }

    public function librarianManage() {
        auth_check('librarian');
        $search = $_GET['q'] ?? '';

        $pending_requests = $this->borrowModel->getPendingRequests();
        $active_loans = $this->borrowModel->getActiveLoans($search);

        include 'views/librarian/borrows.php';
    }
}
?>