<!DOCTYPE html>
<?php
    $enable_send_email = "1";
    $enable_send_email = "0"; // comment this to enable sending email in local
    
    $enable_logging = "0";
    $enable_logging = "1"; // comment this to disable logging in local

    $enable_send_sms = "1";
    $enable_send_sms = "0"; // comment this to enable sending SMS in local

    $timezone = "Asia/Manila";

    $env = "prod";
    $env = "local"; // comment this to use PROD ENV

    if ($env == "prod"){
        $enable_send_email = "1";
        $enable_logging = "0";
        $enable_send_sms = "1";
    };

    function updateTimeZone($timezone){
        date_default_timezone_set($timezone);
    };
    
    function getBaseURL() {
        $protocol = (!empty($_SERVER['HTTPS']) && $_SERVER['HTTPS'] !== 'off') ? "https://" : "http://";
        
        // Get the host name
        $host = $_SERVER['HTTP_HOST'];
        $base_url = $protocol . $host . "/";
        if (strpos($base_url, "localhost") != false){
            return $base_url . "pharmanest/";
        };

        return $base_url;
    };

    function getCurrentURL() {
        // Check if HTTPS is enabled
        $base_url = getBaseURL();
        
        // Get the request URI (path and query string)
        $uri = $_SERVER['REQUEST_URI'];
        
        // Combine all parts to form the full URL
        return $base_url . $uri;
    };
    

?>
