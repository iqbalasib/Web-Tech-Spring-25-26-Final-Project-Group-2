<?php include 'views/layout/header.php'; ?>
    <div style="max-width: 900px; margin: 0 auto;">
        <h2 style="color: #2c3e50;">My Outstanding Fines</h2>

        <div style="background: #e74c3c; color: white; padding: 20px; border-radius: 8px; text-align: center; margin-bottom: 30px;">
            <h3 style="margin: 0;">Total Balance Due</h3>
            <h1 style="font-size: 48px; margin: 10px 0 0 0;">$<?php echo number_format($total_balance, 2); ?></h1>
        </div>

        <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
            <table style="width: 100%; border-collapse: collapse;">
                <tr style="border-bottom: 2px solid #eee; text-align: left;">
                    <th style="padding: 10px;">Book Title</th>
                    <th style="padding: 10px;">Due Date</th>
                    <th style="padding: 10px;">Return Status</th>
                    <th style="padding: 10px; text-align: center;">Days Overdue</th>
                    <th style="padding: 10px; text-align: right;">Amount</th>
                </tr>
                <?php if(empty($fines)): ?>
                    <tr><td colspan="5" style="padding: 20px; text-align: center; color: #27ae60; font-weight: bold;">You have no outstanding fines! Great job!</td></tr>
                <?php endif; ?>

                <?php foreach($fines as $fine): ?>
                    <tr style="border-bottom: 1px solid #eee;">
                        <td style="padding: 10px;"><strong><?php echo htmlspecialchars($fine['title']); ?></strong></td>
                        <td style="padding: 10px;"><?php echo date('M d, Y', strtotime($fine['due_date'])); ?></td>
                        <td style="padding: 10px; color: #7f8c8d;">
                            <?php echo $fine['return_date'] ? 'Returned on ' . date('M d', strtotime($fine['return_date'])) : 'Not Yet Returned'; ?>
                        </td>
                        <td style="padding: 10px; text-align: center; color: #c0392b; font-weight: bold;"><?php echo $fine['overdue_days']; ?></td>
                        <td style="padding: 10px; text-align: right; font-weight: bold;">$<?php echo number_format($fine['amount'], 2); ?></td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>
<?php include 'views/layout/footer.php'; ?>