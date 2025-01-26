
<?php
    session_start();
    $base_url = $_SESSION["BASE_URL"];
    $doc_root = $_SESSION["DOC_ROOT"];
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>PHARMANEST ESSENTIAL</title>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>assets/styles/bootstrap.css">
        <link rel="stylesheet" href="../styles.css">
        <link rel="stylesheet" href="styles.css">
        <script src="<?php echo $base_url;?>assets/scripts/common_fx.js"></script>
        <script src="<?php echo $base_url;?>assets/scripts/auto_refresh_messaging.js"></script>
    </head>
    <body class="body">
        <?php include '../components/unauth_redirection.php'; ?>
        
        <?php include '../components/navbar.php'; ?> 


        <?php
            
            $user_id = $_SESSION['user_id'];
            
            include($doc_root.'/utils/connect.php');

            $isDisabled = '0';

            if (isset($_SESSION['customer_id']) == false){
                $sqlGetCustomerID = "SELECT id AS customer_id FROM customer WHERE user_id=$user_id";
                
                $result = mysqli_query($conn,$sqlGetCustomerID);
                $row = mysqli_fetch_array($result);

                $customer_id = $row['customer_id'];
                $_SESSION['customer_id'] = $customer_id;
            } else {
                $customer_id = $_SESSION['customer_id'];
            };

            $sqlGetLastMessage = "SELECT response FROM messages WHERE customer_id=$customer_id ORDER BY id DESC LIMIT 1";
            
            $last_message_result = mysqli_query($conn, $sqlGetLastMessage);
            if ($last_message_result->num_rows > 0){
                $row = mysqli_fetch_array($last_message_result);
                if (is_null($row['response'])){
                    $_SESSION["isDisabled"] = '1';
                } else {
                    $_SESSION["isDisabled"] = '0';
                };
            } else {
                $_SESSION["isDisabled"] = '0';
            };

            if ($_SESSION["isDisabled"] == '1'){
                $isDisabled = '1';
            };

            $convo_limit = 10;
            $convo_page_no = 1;
            if (isset($_GET['page_no'])){
                $convo_page_no = $_GET['page_no'];
                if ($convo_page_no >= 1){
                    $convo_limit = (int)$convo_page_no * 10;
                } else {
                    $convo_page_no = 1;
                };
            };

            $sqlGetAllMessages = "SELECT m1.*
                                    FROM messages m1
                                    JOIN (
                                        SELECT id FROM messages WHERE customer_id = $customer_id ORDER BY id DESC LIMIT 0, ".$convo_limit."
                                    ) m2 ON m1.id = m2.id
                                    ORDER BY m1.id ASC";

            $all_messages_result = mysqli_query($conn, $sqlGetAllMessages);
        ?>

        <div class="main-container">
            <div class="send-message-section">
                <?php
                    if (isset($_SESSION["message_string"])) {
                        ?>
                            <div class="alert alert-<?php echo $_SESSION["message_class"] ?>">
                                <?php 
                                echo $_SESSION["message_string"];
                                ?>
                            </div>
                            
                        <?php
                        unset($_SESSION["message_string"]);
                        unset($_SESSION["message_class"]);
                    }
                ?>
                <h1>Have inquiries?</h1>
                <p style="display: none" id="countdownDisplay">Time to refresh: 10 seconds</p>
                <form action="process.php" method="post" enctype="multipart/form-data">
                    <textarea <?php echo ($isDisabled=='1' ? 'class="disabled"' : ''); ?>id="inquiry" name="inquiry" rows="4" placeholder="Type your message here..." <?php echo ($isDisabled=='1' ? 'disabled' : ''); ?> onchange="toggleButtonState()"></textarea>
                    <button <?php echo ($isDisabled=='1' ? 'class="disabled"' : ''); ?>id="inquiry_photo_button" type="button" <?php echo ($isDisabled=='1' ? 'disabled' : ''); ?>>Attach Photo <i class="fa fa-image"></i></button>
                    <input id="inquiry_file" name="image" type="file" accept="image/*" style="display: none">
                    <button <?php echo ($isDisabled=='1' ? 'class="disabled"' : ''); ?>id="submit_inquiry" name="submit_inquiry" type="submit" <?php echo ($isDisabled=='1' ? 'disabled' : ''); ?>>Send</button>
                </form>
            </div>

            <div class="conversation-section">
                <h1>Conversation with Pharmacist</h1>
                <div class="messages" id="conversation_box">
                <?php
                    while($data = mysqli_fetch_array($all_messages_result)){
                    ?>
                    <div class="message customer">
                        <p><?php
                            echo $data['inquiry'];
                            if (!is_null($data['inquiry_attachment_path'])){
                                echo '<br/><img class="prescription_photo" src="'.$data['inquiry_attachment_path'].'" style="height:50px; width:50px" onclick=openFullscreen(this)>';
                            };
                        ?></p>
                    </div>
                    <?php
                        if (!is_null($data["response"])){
                    ?>
                    <div class="message pharmacist">
                        <p><?php echo $data['response'];?></p>
                    </div>
                    <?php
                        }
                    }
                    ?>
                </div>
            </div>
        </div>

        <script src="../script.js"></script>

        <script>
            function toggleButtonState() {
                var button = document.getElementById("submit_inquiry");
                var text = document.getElementById("inquiry");
                
                var feedback_is_disabled = "<?php echo $_SESSION["isDisabled"]; ?>";
                
                if (feedback_is_disabled === '1'){ // Disable the text-box and submit button if Customer already sent message
                    button.disabled = true;
                } else if (text.value.length === 0){ // Disable the text-box and submit button if Customer is responding but the message is blank
                    button.disabled = true;
                } else {
                    button.disabled = false;
                };

                resetInactivityTimer();
            };
   
            window.onload = function() {
                setActivePage("nav_message");

                var container = document.getElementById('conversation_box');
                container.scrollTop = container.scrollHeight;

                document.body.onmousemove = resetInactivityTimer;
                document.body.onkeydown = resetInactivityTimer;

                resetInactivityTimer(); // Start the inactivity timer initially
            };

            document.getElementById('inquiry_file').addEventListener('change', function(event) {
                const fileInput = event.target;
                const targetContainer = document.getElementById('inquiry_photo_button');
                const file = fileInput.files[0];

                if (file) {
                    // Check if the selected file is an image
                    if (file.type.startsWith('image/')) {
                        const reader = new FileReader();

                        targetContainer.innerText = 'Change Photo (' + file.name + ')';
                        
                        // Read the selected file as a data URL
                        reader.readAsDataURL(file);
                    } else {
                        alert('Please select a valid image file.');
                        // Clear the file input
                        fileInput.value = '';
                    };
                };

            });

            document.getElementById('inquiry_photo_button').addEventListener('click', function() {
                document.getElementById('inquiry_file').click();
            });

            function loadMessages(){
                let current_convo_page = <?php echo $convo_page_no ?>;
                current_page++;
                window.location.href = './index.php?page_no='+current_convo_page;
            }

        </script>
    </body>
</html>