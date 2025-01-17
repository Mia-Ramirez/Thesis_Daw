<?php
    session_start();
    $base_url = $_SESSION["BASE_URL"];
    // $doc_root = $_SESSION["DOC_ROOT"];
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" type="text/css" href="../styles.css">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <script src="<?php echo $base_url;?>assets/scripts/common_fx.js"></script>
        <?php include '../components/title.php'; ?>
    </head>

    <body>
        <?php include '../components/unauth_redirection.php'; ?>
        
        <?php include '../components/side_nav.php'; ?>
        
        <?php
            $current_page_title = "employees";
            include '../components/top_nav.php';
        ?>

        <div class="main">
            <div class="card account-recovery" onclick="redirectToPage('archived')">
                <h3>ARCHIVED</h3>
                <img class="pic" src="../../assets/images/recover.png" alt="recovery">
            </div>
            <div class="card create-account" onclick="redirectToPage('add')">
                <h3>CREATE ACCOUNT</h3>
                <img class="pic" src="../../assets/images/addAcct.png" alt="add_employee">
            </div>
            <div class="card accounts" onclick="redirectToPage('list')">
                <h3>ACCOUNTS</h3>
                <img class="pic" src="../../assets/images/acct.png" alt="list_employees">
            </div>
        </div>

        <script>
            function redirectToPage(page) {
                window.location.href = './'+page+'/index.php';
            };

            window.onload = function() {
                setActivePage("nav_employee");
            };
        </script>
    </body>
</html>