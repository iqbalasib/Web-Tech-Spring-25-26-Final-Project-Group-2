<?php
require_once 'config/Database.php';
require_once 'models/Fine.php';

function generate_fines() {
    $db = (new Database())->getConnection();
    if ($db) {
        $fineModel = new Fine($db);
        $fineModel->generateFines();
    }
}
?>