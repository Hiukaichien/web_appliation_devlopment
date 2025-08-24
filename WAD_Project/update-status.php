<?php
require_once 'DBhelper.php';
$dbhelper = new DBhelper();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $id = intval($_POST['id']);
    $status = intval($_POST['status']);
    $dbhelper->updateMessageStatus($id, $status);
}
