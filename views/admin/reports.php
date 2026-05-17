<?php include 'views/layout/header.php'; ?>
    <div style="max-width: 1200px; margin: 0 auto;">
        <h2 style="color: #2c3e50; margin-bottom: 30px;">Admin Dashboard: Library Analytics</h2>

        <div style="display: flex; gap: 30px; margin-bottom: 30px;">

            <div style="flex: 1; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <h3 style="color: #2980b9; margin-top: 0;">Top 10 Most Borrowed Books</h3>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr style="border-bottom: 2px solid #eee; text-align: left;">
                        <th style="padding: 10px;">Book Title</th>
                        <th style="padding: 10px; text-align: right;">Total Borrows</th>
                    </tr>
                    <?php foreach($top_books as $book): ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 10px;"><?php echo htmlspecialchars($book['title']); ?></td>
                            <td style="padding: 10px; text-align: right; font-weight: bold;"><?php echo $book['borrow_count']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>

            <div style="flex: 1; background: white; padding: 20px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
                <h3 style="color: #27ae60; margin-top: 0;">Top 10 Most Active Members</h3>
                <table style="width: 100%; border-collapse: collapse;">
                    <tr style="border-bottom: 2px solid #eee; text-align: left;">
                        <th style="padding: 10px;">Member Name</th>
                        <th style="padding: 10px; text-align: right;">Total Loans</th>
                    </tr>
                    <?php foreach($top_members as $member): ?>
                        <tr style="border-bottom: 1px solid #eee;">
                            <td style="padding: 10px;"><?php echo htmlspecialchars($member['name']); ?></td>
                            <td style="padding: 10px; text-align: right; font-weight: bold;"><?php echo $member['loan_count']; ?></td>
                        </tr>
                    <?php endforeach; ?>
                </table>
            </div>
        </div>

        <div style="background: white; padding: 30px; border-radius: 8px; box-shadow: 0 2px 4px rgba(0,0,0,0.05);">
            <h3 style="color: #8e44ad; margin-top: 0; margin-bottom: 20px;">Borrowing Trends (Past 6 Months)</h3>
            <canvas id="borrowsChart" height="80"></canvas>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        const ctx = document.getElementById('borrowsChart').getContext('2d');
        new Chart(ctx, {
            type: 'bar',
            data: {
                labels: <?php echo json_encode($chartLabels); ?>,
                datasets: [{
                    label: 'Total Books Borrowed',
                    data: <?php echo json_encode($chartTotals); ?>,
                    backgroundColor: 'rgba(142, 68, 173, 0.6)',
                    borderColor: 'rgba(142, 68, 173, 1)',
                    borderWidth: 1,
                    borderRadius: 4
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: { beginAtZero: true, ticks: { stepSize: 1 } }
                }
            }
        });
    </script>
<?php include 'views/layout/footer.php'; ?>