<?php
require_once 'models/Member.php';
require_once 'helpers/auth.php';

class AuthController {
    private $db;
    private $memberModel;

    public function __construct() {
        $this->db = (new Database())->getConnection();
        $this->memberModel = new Member($this->db);
    }

    public function register() {
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $phone = trim($_POST['phone']);
            $password = $_POST['password'];

            // Validation chain
            if (empty($name) || empty($email) || empty($phone) || empty($password)) {
                $errors[] = "All fields are required.";
            } elseif (!is_numeric($phone)) {
                $errors[] = "Phone number must contain only numbers.";
            } elseif (strlen($password) < 8) {
                $errors[] = "Password must be at least 8 characters long.";
            } elseif ($this->memberModel->register($name, $email, $phone, $password)) {
                $_SESSION['flash_success'] = "Registration successful! You may now log in.";
                header("Location: index.php?action=login");
                exit;
            } else {
                $errors[] = "That email is already registered.";
            }
        }

        include 'views/auth/register.php';
    }

    public function login() {
        $error = null;

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $user = $this->memberModel->login($_POST['email'], $_POST['password']);

            if ($user) {
                $_SESSION['member_id'] = $user['id'];
                $_SESSION['name'] = $user['name'];
                $_SESSION['role'] = $user['role'];

                $routes = [
                    'admin' => 'admin_dashboard',
                    'librarian' => 'librarian_dashboard'
                ];
                $action = $routes[$user['role']] ?? 'member_dashboard';

                header("Location: index.php?action=" . $action);
                exit;
            }
            $error = "Invalid email or password.";
        }

        include 'views/auth/login.php';
    }

    public function logout() {
        session_destroy();
        header("Location: index.php?action=login");
        exit;
    }

    public function profile() {
        auth_check('member');

        $user = $this->memberModel->getMemberById($_SESSION['member_id']);
        $errors = [];

        if ($_SERVER['REQUEST_METHOD'] == 'POST') {
            $name = trim($_POST['name']);
            $email = trim($_POST['email']);
            $phone = trim($_POST['phone']);
            $current_password = $_POST['current_password'];
            $new_password = $_POST['new_password'];

            if (!is_numeric($phone)) {
                $errors[] = "Phone number must contain only numbers.";
            }

            if (empty($errors)) {
                $this->memberModel->updateProfile($user['id'], $name, $email, $phone);
                $_SESSION['name'] = $name;

                if (!empty($new_password)) {
                    if (password_verify($current_password, $user['password_hash'])) {
                        if (strlen($new_password) >= 8) {
                            $this->memberModel->updatePassword($user['id'], $new_password);
                            $_SESSION['flash_success'] = "Profile and password updated successfully.";
                        } else {
                            $_SESSION['flash_error'] = "New password must be at least 8 characters. Profile updated, but password was not changed.";
                        }
                    } else {
                        $_SESSION['flash_error'] = "Current password was incorrect. Profile updated, but password was not changed.";
                    }
                } else {
                    $_SESSION['flash_success'] = "Profile updated successfully.";
                }

                header("Location: index.php?action=profile");
                exit;
            }
        }

        $stats = $this->memberModel->getDashboardStats($_SESSION['member_id']);

        include 'views/member/profile.php';
    }
}
?>