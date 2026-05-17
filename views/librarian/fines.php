<?php include 'views/layout/header.php'; ?>
    <div style="max-width: 1000px; margin: 0 auto;">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px;">
            <h2 style="color: #2c3e50; margin: 0;">Process Fine Payments</h2>

            <form method="GET" action="index.php" style="display: flex;">
                <input type="hidden" name="action" value="librarian_fines">
                <input type="text" name="q" placeholder="Search Member Name..." value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>" style="padding: 8px; border: 1px solid #ccc; border-radius: 4px 0 0 4px;">
                <button type="submit" style="padding: 8px 15px; background: #2c3e50; color: white; border: none; border-radius: 0 4px 4px 0; cursor: pointer;">Search</button>
            </form>
        </div>

        <div style="background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
            <table style="width: 100%; border-collapse: collapse;">
                <tr style="border-bottom: 2px solid #eee; text-align: left;">
                    <th style="padding: 12px;">Member</th>
                    <th style="padding: 12px;">Book Title</th>
                    <th style="padding: 12px;">Overdue</th>
                    <th style="padding: 12px;">Amount</th>
                    <th style="padding: 12px; text-align: right;">Action</th>
                </tr>
                <?php if(empty($fines)): ?>
                    <tr><td colspan="5" style="padding: 20px; text-align: center; color: #7f8c8d;">No unpaid fines found.</td></tr>
                <?php endif; ?>

                <?php foreach($fines as $fine): ?>
                    <tr id="fine-<?php echo $fine['id']; ?>" style="border-bottom: 1px solid #eee;">
                        <td style="padding: 12px;"><strong><?php echo htmlspecialchars($fine['member_name']); ?></strong></td>
                        <td style="padding: 12px;"><?php echo htmlspecialchars($fine['title']); ?></td>
                        <td style="padding: 12px;"><?php echo $fine['overdue_days']; ?> days</td>
                        <td style="padding: 12px; font-weight: bold; color: #c0392b;">$<?php echo number_format($fine['amount'], 2); ?></td>
                        <td style="padding: 12px; text-align: right;">
                            <button onclick="markPaid(<?php echo $fine['id']; ?>)" style="padding: 6px 12px; background: #27ae60; color: white; border: none; border-radius: 4px; cursor: pointer; font-weight: bold;">Mark as Paid</button>
                        </td>
                    </tr>
                <?php endforeach; ?>
            </table>
        </div>
    </div>

    <script>
        function markPaid(fineId) {
            if(!confirm("Confirm payment received for this fine?")) return;

            let formData = new FormData();
            formData.append('fine_id', fineId);

            fetch('index.php?action=api/fines/pay', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    if(data.status === 'success') {
                        document.getElementById('fine-' + fineId).remove();
                    } else {
                        alert('Error processing payment.');
                    }
                });
        }
    </script>
<?php include 'views/layout/footer.php'; ?>