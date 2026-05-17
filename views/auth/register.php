<?php include 'views/layout/header.php'; ?>
    <div style="max-width: 500px; margin: 40px auto; padding: 30px; background: white; box-shadow: 0 4px 6px rgba(0,0,0,0.05); border-radius: 8px;">
        <h2 style="text-align: center; margin-top: 0;">Member Registration</h2>

        <?php if(!empty($errors)): ?>
            <div class="alert error"><?php echo implode('<br>', $errors); ?></div>
        <?php endif; ?>

        <form action="index.php?action=register" method="POST">
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Full Name</label>
                <input type="text" name="name" value="<?php echo isset($_POST['name']) ? htmlspecialchars($_POST['name']) : ''; ?>" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Email Address</label>
                <input type="email" name="email" value="<?php echo isset($_POST['email']) ? htmlspecialchars($_POST['email']) : ''; ?>" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
            </div>
            <div style="margin-bottom: 15px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Phone Number (Digits only)</label>
                <input type="text" name="phone" value="<?php echo isset($_POST['phone']) ? htmlspecialchars($_POST['phone']) : ''; ?>" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
            </div>
            <div style="margin-bottom: 20px;">
                <label style="display: block; font-weight: bold; margin-bottom: 5px;">Password (Min 8 characters)</label>
                <input type="password" name="password" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
            </div>
            <button type="submit" style="width: 100%; padding: 12px; background: #2c3e50; color: white; border: none; cursor: pointer; font-size: 16px; border-radius: 4px; font-weight: bold;">Create Account</button>
        </form>
        <p style="text-align: center; margin-top: 20px;">Already a member? <a href="index.php?action=login" style="color: #2980b9;">Login here</a></p>
    </div>
<?php include 'views/layout/footer.php'; ?>