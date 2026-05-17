<?php
require_once 'helpers/auth.php';
require_once 'models/Book.php';

class ApiCatalogController {
    private $db;
    private $bookModel;

    public function __construct() {
        auth_check('librarian');
        $this->db = (new Database())->getConnection();
        $this->bookModel = new Book($this->db);
    }

    public function search() {
        header('Content-Type: application/json');
        $q = trim($_GET['q'] ?? '');
        echo json_encode($this->bookModel->searchBooks($q));
        exit;
    }
}
?>