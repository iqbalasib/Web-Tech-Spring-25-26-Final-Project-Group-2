<?php
function auth_check($required_role = 'member') {
    if (session_status() === PHP_SESSION_NONE) {
        session_start();
    }

    if (!isset($_SESSION['member_id'])) {
        header("Location: index.php?action=login");
        exit;
    }

    $role_hierarchy = ['member' => 1, 'librarian' => 2, 'admin' => 3];
    $user_role = $_SESSION['role'];

    if ($role_hierarchy[$user_role] < $role_hierarchy[$required_role]) {
        $_SESSION['flash_error'] = "You do not have permission to access that page.";

        if ($user_role === 'admin') header("Location: index.php?action=admin_dashboard");
        elseif ($user_role === 'librarian') header("Location: index.php?action=librarian_dashboard");
        else header("Location: index.php?action=member_dashboard");
        exit;
    }
}
?>