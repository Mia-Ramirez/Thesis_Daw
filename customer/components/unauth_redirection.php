<?php
    $base_url = $_SESSION["BASE_URL"];
    if (isset($_SESSION["user_role"])) {
        $role = $_SESSION["user_role"];
      if (strpos($role, "admin") !== false) {
          header("Location:".$base_url."admin/index.php");
      } else if ($_SESSION["user_role"] == "pharmacist"){
          header("Location:".$base_url."pharmacist/dashboard/index.php");
      };
      
    } else {
        header("Location:".$base_url."index.php");
    };
?>