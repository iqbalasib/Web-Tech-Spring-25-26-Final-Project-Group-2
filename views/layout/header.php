<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Library Management System</title>
</head>
<body>
<header style="background: #2c3e50; padding: 15px 40px; color: white; display: flex; justify-content: space-between; align-items: center; box-shadow: 0 2px 5px rgba(0,0,0,0.1);">
    <div style="font-size: 24px; font-weight: bold;">📚 Library Portal</div>
    <div>
        <?php if (isset($_SESSION['member_id'])): ?>

            <?php if ($_SESSION['role'] === 'admin'): ?>
                <a href="index.php?action=admin_dashboard" style="color: white; text-decoration: none; margin-right: 20px;">Reports</a>

            <?php elseif ($_SESSION['role'] === 'librarian'): ?>
                <a href="index.php?action=librarian_dashboard" style="color: white; text-decoration: none; margin-right: 20px;">Catalog</a>
                <a href="index.php?action=librarian_borrows" style="color: white; text-decoration: none; margin-right: 20px;">Loans & Returns</a>
                <a href="index.php?action=librarian_fines" style="color: #f1c40f; text-decoration: none; margin-right: 20px; font-weight: bold;">Process Fines</a>

            <?php else: ?>
                <a href="index.php?action=browse_books" style="color: white; text-decoration: none; margin-right: 20px;">Browse Catalog</a>
                <a href="index.php?action=my_fines" style="color: #e74c3c; text-decoration: none; margin-right: 20px; font-weight: bold;">My Fines</a>
                <a href="index.php?action=profile" style="color: white; text-decoration: none; margin-right: 20px;">My Dashboard</a>
            <?php endif; ?>

            <a href="index.php?action=logout" style="color: #ff6b6b; text-decoration: none;">Logout (<?php echo htmlspecialchars($_SESSION['name']); ?>)</a>

        <?php else: ?>
            <a href="index.php?action=login" style="color: white; text-decoration: none; margin-right: 20px;">Login</a>
            <a href="index.php?action=register" style="color: white; text-decoration: none;">Register</a>
        <?php endif; ?>
    </div>
</header>
<main style="max-width: 1200px; margin: 30px auto; padding: 0 20px; min-height: 75vh;">

    <?php if (isset($_SESSION['flash_success'])): ?>
        <div class="alert success"><?php echo $_SESSION['flash_success']; unset($_SESSION['flash_success']); ?></div>
    <?php endif; ?>
    <?php if (isset($_SESSION['flash_error'])): ?>
    <div class="alert error"><?php echo $_SESSION['flash_error']; unset($_SESSION['flash_error']); ?></div>
<?php endif; ?>