<?php
session_start();

require_once 'config/Database.php';
require_once 'helpers/auth.php';
require_once 'helpers/fines.php';

generate_fines();

require_once 'controllers/AuthController.php';
require_once 'controllers/CatalogController.php';
require_once 'controllers/BorrowController.php';
require_once 'controllers/FineController.php';
require_once 'controllers/ReportController.php';

require_once 'controllers/api/ApiCatalogController.php';
require_once 'controllers/api/ApiBorrowController.php';
require_once 'controllers/api/ApiFineController.php';

$action = isset($_GET['action']) ? $_GET['action'] : 'login';

switch ($action) {
    // AUTHENTICATION & PROFILE
    case 'login':
        (new AuthController())->login();
        break;
    case 'register':
        (new AuthController())->register();
        break;
    case 'logout':
        (new AuthController())->logout();
        break;
    case 'profile':
    case 'member_dashboard':
        (new AuthController())->profile();
        break;

    // CATALOG MANAGEMENT (LIBRARIAN)
    case 'librarian_dashboard':
        (new CatalogController())->manage();
        break;
    case 'api/books/search':
        (new ApiCatalogController())->search();
        break;

    // BORROWING & RETURNS
    case 'browse_books':
        (new BorrowController())->memberBrowse();
        break;
    case 'api/borrow/request':
        (new ApiBorrowController())->requestBorrow();
        break;
    case 'librarian_borrows':
        (new BorrowController())->librarianManage();
        break;
    case 'api/borrow/approve':
        (new ApiBorrowController())->approve();
        break;
    case 'api/borrow/reject':
        (new ApiBorrowController())->reject();
        break;
    case 'api/borrow/return':
        (new ApiBorrowController())->returnBook();
        break;
    case 'api/books/availability':
        (new ApiBorrowController())->checkAvailability();
        break;

    // FINES
    case 'my_fines':
        (new FineController())->myFines();
        break;
    case 'librarian_fines':
        (new FineController())->librarianDashboard();
        break;
    case 'api/fines/pay':
        (new ApiFineController())->payFine();
        break;

    // ADMIN REPORTS
    case 'admin_dashboard':
        (new ReportController())->dashboard();
        break;

    default:
        if (isset($_SESSION['member_id'])) {
            if ($_SESSION['role'] === 'admin') header("Location: index.php?action=admin_dashboard");
            elseif ($_SESSION['role'] === 'librarian') header("Location: index.php?action=librarian_dashboard");
            else header("Location: index.php?action=member_dashboard");
        } else {
            header("Location: index.php?action=login");
        }
        break;
}
?>