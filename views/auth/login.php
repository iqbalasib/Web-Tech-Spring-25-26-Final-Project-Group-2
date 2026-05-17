<?php include 'views/layout/header.php'; ?>
    <div style="max-width: 400px; margin: 60px auto; padding: 30px; background: white; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-radius: 8px;">
        <h2 style="text-align: center; margin-top: 0;">Library Login</h2>

        <?php if(isset($error)): ?>
            <div class="alert error"><?php echo $error; ?></div>
        <?php endif; ?>

        <form action="index.php?action=login" method="POST">
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Email</label>
                <input type="email" name="email" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
            </div>
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Password</label>
                <input type="password" name="password" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
            </div>
            <button type="submit" style="width: 100%; padding: 12px; background: #2980b9; color: white; border: none; cursor: pointer; font-size: 16px; border-radius: 4px; font-weight: bold;">Login</button>
        </form>
        <p style="text-align: center; margin-top: 20px;">Need an account? <a href="index.php?action=register" style="color: #2980b9;">Register here</a></p>
    </div>
<?php include 'views/layout/footer.php'; ?>