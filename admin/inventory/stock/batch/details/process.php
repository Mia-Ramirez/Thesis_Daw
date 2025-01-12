<!DOCTYPE html>
<?php
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location:index.php");
        exit;
    };

    session_start();
    include('../../../../../utils/connect.php');

    if ($_POST['action'] == 'dispose_stock'){
        include '../utils/dispose_stock.php';
        header("Location:index.php");
        exit;
    }
?>