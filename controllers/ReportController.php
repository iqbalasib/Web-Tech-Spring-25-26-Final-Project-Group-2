<?php
require_once 'helpers/auth.php';
require_once 'models/Report.php';

class ReportController {
    public function dashboard() {
        auth_check('admin');

        $db = (new Database())->getConnection();
        $reportModel = new Report($db);

        $top_books = $reportModel->getTopBooks();
        $top_members = $reportModel->getTopMembers();

        $monthly_data = $reportModel->getMonthlyBorrows();
        $chartLabels = [];
        $chartTotals = [];
        foreach($monthly_data as $row) {
            $chartLabels[] = $row['month'];
            $chartTotals[] = $row['total'];
        }

        include 'views/admin/reports.php';
    }
}
?>