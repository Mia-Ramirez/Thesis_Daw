<!DOCTYPE html>
<?php
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location:index.php");
        exit;
    };

    session_start();
    include('../../utils/connect.php');

    if ($_POST['action'] == 'cancel_order'){
        include './utils/cancel_order.php';
        header("Location:index.php");
        exit;

    }
?>