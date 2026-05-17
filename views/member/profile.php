<?php include 'views/layout/header.php'; ?>
    <div style="max-width: 900px; margin: 0 auto;">

        <div style="display: flex; gap: 20px; margin-bottom: 30px;">
            <div style="flex: 1; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); text-align: center; border-bottom: 4px solid #3498db;">
                <h3 style="margin-top: 0; color: #666;">Active Loans</h3>
                <h1 style="color: #2c3e50; font-size: 40px; margin: 10px 0;"><?php echo $stats['active_loans']; ?></h1>
            </div>
            <div style="flex: 1; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); text-align: center; border-bottom: 4px solid #f39c12;">
                <h3 style="margin-top: 0; color: #666;">Upcoming Due</h3>
                <h1 style="color: #2c3e50; font-size: 40px; margin: 10px 0;"><?php echo $stats['upcoming_due']; ?></h1>
            </div>
            <div style="flex: 1; background: white; padding: 25px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05); text-align: center; border-bottom: 4px solid #e74c3c;">
                <h3 style="margin-top: 0; color: #666;">Outstanding Fines</h3>
                <h1 style="color: #e74c3c; font-size: 40px; margin: 10px 0;">$<?php echo number_format($stats['outstanding_fines'], 2); ?></h1>
            </div>
        </div>

        <div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
            <h2 style="margin-top: 0; color: #2c3e50;">Profile Settings</h2>

            <?php if(!empty($errors)): ?>
                <div class="alert error"><?php echo implode('<br>', $errors); ?></div>
            <?php endif; ?>

            <form action="index.php?action=profile" method="POST">
                <div style="display: flex; gap: 20px; margin-bottom: 15px;">
                    <div style="flex: 1;">
                        <label style="display: block; font-weight: bold; margin-bottom: 5px;">Full Name</label>
                        <input type="text" name="name" value="<?php echo htmlspecialchars($user['name']); ?>" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
                    </div>
                    <div style="flex: 1;">
                        <label style="display: block; font-weight: bold; margin-bottom: 5px;">Phone Number</label>
                        <input type="text" name="phone" value="<?php echo htmlspecialchars($user['phone']); ?>" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
                    </div>
                </div>

                <div style="margin-bottom: 25px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Email Address</label>
                    <input type="email" name="email" value="<?php echo htmlspecialchars($user['email']); ?>" required style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
                </div>

                <hr style="margin: 25px 0; border: none; border-top: 1px solid #eee;">

                <h3 style="color: #2c3e50;">Change Password (Optional)</h3>
                <div style="margin-bottom: 15px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">Current Password</label>
                    <input type="password" name="current_password" placeholder="Required only to confirm changes" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
                </div>
                <div style="margin-bottom: 20px;">
                    <label style="display: block; font-weight: bold; margin-bottom: 5px;">New Password (Min 8 chars)</label>
                    <input type="password" name="new_password" style="width: 100%; padding: 10px; border: 1px solid #ccc; border-radius: 4px; box-sizing: border-box;">
                </div>

                <button type="submit" style="padding: 12px 25px; background: #27ae60; color: white; border: none; cursor: pointer; font-size: 16px; border-radius: 4px; font-weight: bold;">Save Changes</button>
            </form>
        </div>
    </div>
<?php include 'views/layout/footer.php'; ?>