<?php
require_once 'helpers/auth.php';
require_once 'models/Genre.php';
require_once 'models/Book.php';

class CatalogController {
    private $db;
    private $genreModel;
    private $bookModel;

    public function __construct() {
        auth_check('librarian');

        $this->db = (new Database())->getConnection();
        $this->genreModel = new Genre($this->db);
        $this->bookModel = new Book($this->db);
    }

    private function validateIsbn($isbn) {
        $clean_isbn = str_replace('-', '', trim($isbn));
        return preg_match('/^(\d{10}|\d{13})$/', $clean_isbn) ? $clean_isbn : false;
    }

    public function manage() {
        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $action_type = $_POST['action_type'] ?? '';

            switch ($action_type) {
                case 'add_genre':
                    if ($this->genreModel->add(trim($_POST['genre_name']))) {
                        $_SESSION['flash_success'] = "Genre added successfully.";
                    } else {
                        $_SESSION['flash_error'] = "Error: Genre name might already exist.";
                    }
                    break;

                case 'edit_genre':
                    if ($this->genreModel->update($_POST['genre_id'], trim($_POST['genre_name']))) {
                        $_SESSION['flash_success'] = "Genre updated successfully.";
                    } else {
                        $_SESSION['flash_error'] = "Error updating genre.";
                    }
                    break;

                case 'delete_genre':
                    if ($this->genreModel->delete($_POST['genre_id'])) {
                        $_SESSION['flash_success'] = "Genre deleted.";
                    } else {
                        $_SESSION['flash_error'] = "Cannot delete genre: Books are currently assigned to it.";
                    }
                    break;

                case 'add_book':
                case 'edit_book':
                    $clean_isbn = $this->validateIsbn($_POST['isbn']);

                    if (!$clean_isbn) {
                        $_SESSION['flash_error'] = "Invalid ISBN format. Must be 10 or 13 digits.";
                    } else {
                        $title = trim($_POST['title']);
                        $author = trim($_POST['author']);
                        $copies = (int)$_POST['total_copies'];
                        $shelf = trim($_POST['shelf_location']);
                        $year = (int)$_POST['published_year'];
                        $genre_id = $_POST['genre_id'];

                        if ($action_type === 'add_book') {
                            $success = $this->bookModel->add($genre_id, $title, $author, $clean_isbn, $copies, $shelf, $year);
                            $msg = "Book successfully added to the catalog.";
                            $err = "Error: A book with that ISBN already exists.";
                        } else {
                            $success = $this->bookModel->update($_POST['book_id'], $genre_id, $title, $author, $clean_isbn, $copies, $shelf, $year);
                            $msg = "Book successfully updated.";
                            $err = "Error updating book.";
                        }

                        if ($success) {
                            $_SESSION['flash_success'] = $msg;
                        } else {
                            $_SESSION['flash_error'] = $err;
                        }
                    }
                    break;

                case 'delete_book':
                    if ($this->bookModel->delete($_POST['book_id'])) {
                        $_SESSION['flash_success'] = "Book removed from the catalog.";
                    } else {
                        $_SESSION['flash_error'] = "Cannot delete book: There are currently active borrow records tied to it.";
                    }
                    break;
            }

            header("Location: index.php?action=librarian_dashboard");
            exit;
        }

        $genres = $this->genreModel->getAll();
        $books = $this->bookModel->getAllWithAvailability();
        include 'views/librarian/catalog.php';
    }
}
?>