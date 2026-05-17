<?php include 'views/layout/header.php'; ?>
    <div style="max-width: 1200px; margin: 0 auto;">
        <h2 style="color: #2c3e50;">Borrowing & Return Management</h2>

        <div style="display: flex; gap: 30px; flex-wrap: wrap;">

            <div style="flex: 1; min-width: 400px; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <h3 style="color: #f39c12; margin-top: 0;">Pending Requests</h3>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr style="border-bottom: 2px solid #eee; text-align: left;">
                        <th style="padding: 10px;">Date</th>
                        <th style="padding: 10px;">Member</th>
                        <th style="padding: 10px;">Book</th>
                        <th style="padding: 10px;">Actions</th>
                    </tr>
                    <?php if(empty($pending_requests)): ?>
                        <tr><td colspan="4" style="padding: 15px; text-align: center; color: #7f8c8d;">No pending requests.</td></tr>
                    <?php endif; ?>

                    <?php foreach($pending_requests as $req): ?>
                        <tr id="req-<?php echo $req['id']; ?>" style="border-bottom: 1px solid #eee;">
                            <td style="padding: 10px;"><?php echo date('M d', strtotime($req['borrow_date'])); ?></td>
                            <td style="padding: 10px;"><strong><?php echo htmlspecialchars($req['member_name']); ?></strong></td>
                            <td style="padding: 10px;"><?php echo htmlspecialchars($req['book_title']); ?></td>
                            <td style="padding: 10px; display: flex; gap: 5px;">
                                <button onclick="handleRequest(<?php echo $req['id']; ?>, 'approve')" style="padding: 5px 10px; background: #27ae60; color: white; border: none; border-radius: 4px; cursor: pointer;">Approve</button>
                                <button onclick="handleRequest(<?php echo $req['id']; ?>, 'reject')" style="padding: 5px 10px; background: #e74c3c; color: white; border: none; border-radius: 4px; cursor: pointer;">Reject</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>

            <div style="flex: 1.5; min-width: 500px; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 15px;">
                    <h3 style="color: #2980b9; margin: 0;">Active Loans (Process Returns)</h3>
                    <form method="GET" action="index.php" style="display: flex;">
                        <input type="hidden" name="action" value="librarian_borrows">
                        <input type="text" name="q" placeholder="Search Member or Book..." value="<?php echo htmlspecialchars($_GET['q'] ?? ''); ?>" style="padding: 6px; border: 1px solid #ccc; border-radius: 4px 0 0 4px;">
                        <button type="submit" style="padding: 6px 12px; background: #2c3e50; color: white; border: none; border-radius: 0 4px 4px 0; cursor: pointer;">Search</button>
                    </form>
                </div>

                <table style="width: 100%; border-collapse: collapse;">
                    <tr style="border-bottom: 2px solid #eee; text-align: left;">
                        <th style="padding: 10px;">Member</th>
                        <th style="padding: 10px;">Book</th>
                        <th style="padding: 10px;">Due Date</th>
                        <th style="padding: 10px;">Action</th>
                    </tr>
                    <?php if(empty($active_loans)): ?>
                        <tr><td colspan="4" style="padding: 15px; text-align: center; color: #7f8c8d;">No active loans found.</td></tr>
                    <?php endif; ?>

                    <?php foreach($active_loans as $loan): ?>
                        <tr id="loan-<?php echo $loan['id']; ?>" style="border-bottom: 1px solid #eee;">
                            <td style="padding: 10px;"><strong><?php echo htmlspecialchars($loan['member_name']); ?></strong></td>
                            <td style="padding: 10px;"><?php echo htmlspecialchars($loan['book_title']); ?></td>
                            <td style="padding: 10px; color: <?php echo (strtotime($loan['due_date']) < time()) ? '#c0392b; font-weight: bold;' : '#333;'; ?>">
                                <?php echo date('M d, Y', strtotime($loan['due_date'])); ?>
                                <?php if(strtotime($loan['due_date']) < time()) echo " (Overdue)"; ?>
                            </td>
                            <td style="padding: 10px;">
                                <button onclick="processReturn(<?php echo $loan['id']; ?>)" style="padding: 6px 12px; background: #34495e; color: white; border: none; border-radius: 4px; cursor: pointer;">Process Return</button>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>

        </div>
    </div>

    <script>
        function handleRequest(recordId, actionType) {
            let endpoint = actionType === 'approve' ? 'index.php?action=api/borrow/approve' : 'index.php?action=api/borrow/reject';
            let formData = new FormData();
            formData.append('record_id', recordId);

            fetch(endpoint, { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    if(data.status === 'success') {
                        document.getElementById('req-' + recordId).remove();
                        if(actionType === 'approve') {
                            location.reload();
                        }
                    } else {
                        alert('Error processing request.');
                    }
                });
        }

        function processReturn(recordId) {
            if(!confirm("Are you sure you want to mark this book as returned?")) return;

            let formData = new FormData();
            formData.append('record_id', recordId);

            fetch('index.php?action=api/borrow/return', { method: 'POST', body: formData })
                .then(res => res.json())
                .then(data => {
                    if(data.status === 'success') {
                        document.getElementById('loan-' + recordId).remove();
                        alert("Return processed successfully. Available copies updated.");
                    } else {
                        alert('Error processing return.');
                    }
                });
        }
    </script>
<?php include 'views/layout/footer.php'; ?>