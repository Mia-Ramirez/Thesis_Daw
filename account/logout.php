<?php
    session_start();
    error_log("HERE");
    session_unset();
    session_destroy();
    header("Location: index.php");
?>