<?php
    if (isset($_SESSION["user_role"])) {
        $role = $_SESSION["user_role"];
        if ($role == "customer"){
          header("Location:".$base_url."customer/home/index.php");
        } else if (strpos($role, "admin") !== false) {
          header("Location:".$base_url."admin/dashboard/index.php");
        };
    } else {
        header("Location:".$base_url."index.php");
    };
?>