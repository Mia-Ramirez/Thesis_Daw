<!DOCTYPE html>
<?php
    if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
        header("Location:index.php");
        exit;
    };

    session_start();
    $doc_root = $_SESSION["DOC_ROOT"];
    include($doc_root.'/utils/connect.php');

    if ($_POST['action'] == 'cancel_order'){
        include './utils/cancel_order.php';
        header("Location:index.php");
        exit;

    }
?>