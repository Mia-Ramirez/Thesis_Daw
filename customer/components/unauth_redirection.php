<?php
    if (isset($_SESSION["user_role"])) {
        $role = $_SESSION["user_role"];
        if (strpos($role, "admin") !== false) {
            header("Location:".$base_url."admin/index.php");
        } else if ($role == "pharmacist"){
            header("Location:".$base_url."pharmacist/dashboard/index.php");
        };
    } else {
        header("Location:".$base_url."index.php");
    };
?>