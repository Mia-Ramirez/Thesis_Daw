<?php
    session_start();
    $base_url = $_SESSION["BASE_URL"];
    $doc_root = $_SESSION["DOC_ROOT"];
?>
<!DOCTYPE html>
<html>
    <head>
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
        <link rel="stylesheet" type="text/css" href="<?php echo $base_url;?>assets/styles/bootstrap.css">
        <link rel="stylesheet" type="text/css" href="../styles.css">
        <link rel="stylesheet" type="text/css" href="styles.css">
        <script src="<?php echo $base_url;?>assets/scripts/common_fx.js"></script>
        <script src="<?php echo $base_url;?>assets/scripts/auto_refresh_messaging.js"></script>
        <?php include '../components/title.php'; ?>
    </head>

    <body>
        <?php include '../components/unauth_redirection.php'; ?>

        <?php include '../components/side_nav.php'; ?>
        
        <?php
            $current_page_title = "messages";
            include '../components/top_nav.php';
        ?> 
        <?php
            $isDisabled = '0';
            include($doc_root.'/utils/connect.php');
            
            $inbox_limit = 4;
            $inbox_page_no = 1;
            if (isset($_GET['page_no'])){
                $inbox_page_no = $_GET['page_no'];
                if ($inbox_page_no >= 1){
                    $inbox_limit = (int)$inbox_page_no * 4;
                } else {
                    $inbox_page_no = 1;
                };
            };

            $convo_limit = 10;
            $convo_page_no = 1;
            if (isset($_GET['cpage_no'])){
                $convo_page_no = $_GET['cpage_no'];
                if ($convo_page_no >= 1){
                    $convo_limit = (int)$convo_page_no * 10;
                } else {
                    $convo_page_no = 1;
                };
            };

            $sqlGetInquiries = "
                SELECT
                    c.first_name,
                    c.last_name,
                    m.inquiry,
                    m.date_inquired,
                    m.response,
                    m.customer_id
                FROM messages m
                INNER JOIN (
                    SELECT customer_id, MAX(date_inquired) AS latest_date
                    FROM messages
                    GROUP BY customer_id
                ) AS latest_message
                ON m.customer_id = latest_message.customer_id
                AND m.date_inquired = latest_message.latest_date
                INNER JOIN customer c
                ON m.customer_id = c.id
                LIMIT 0, ".$inbox_limit."
            ";
            $customer_inquiries_result = mysqli_query($conn,$sqlGetInquiries);
            
            $customer_id = NULL;
            if (isset($_GET['customer_id'])){
                $customer_id = $_GET['customer_id'];
            };

            if (($customer_inquiries_result->num_rows != 0) && (is_null($customer_id))){
                $row = mysqli_fetch_array($customer_inquiries_result);
                $customer_id = $row['customer_id'];
                mysqli_data_seek($customer_inquiries_result, 0);
            };
            
            if (!is_null($customer_id)){
                $sqlGetCustomerName = "SELECT first_name, last_name FROM customer WHERE id=$customer_id";
                $customer_name_result = mysqli_query($conn,$sqlGetCustomerName);
                $row = mysqli_fetch_array($customer_name_result);
                $customer_name = $row['first_name']." ".$row['last_name'];
                $sqlGetCustomerConversation = "
                    SELECT
                        m1.*,
                        e.first_name,
                        e.last_name
                    FROM messages m1
                    JOIN (
                        SELECT id FROM messages WHERE customer_id = $customer_id ORDER BY id DESC LIMIT 0, ".$convo_limit."
                    ) m2 ON m1.id = m2.id
                    LEFT JOIN employee e ON m1.employee_id=e.id
                    ORDER BY m1.id ASC
                    ";
                    
                $customer_conversation_result = mysqli_query($conn, $sqlGetCustomerConversation);

                if ($customer_conversation_result->num_rows == 0){
                    header("Location:index.php");
                };
                
                $_SESSION['customer_id'] = $customer_id;

                $sqlGetLastMessage = "SELECT response FROM messages WHERE customer_id=$customer_id ORDER BY id DESC LIMIT 1";
                $last_message_result = mysqli_query($conn, $sqlGetLastMessage);
                if ($last_message_result->num_rows != 0){
                    $row = mysqli_fetch_array($last_message_result);
                    if (is_null($row['response'])){
                        $_SESSION["isDisabled"] = '0';
                    } else {
                        $_SESSION["isDisabled"] = '1';
                    };
                };
        
                if ($_SESSION["isDisabled"] == '1'){
                    $isDisabled = '1';
                }
            };

        ?>
    
        <div class="holder">
            <div class="chat-container">
            <center style="margin-top: -4%; margin-bottom: -4%;"><h1>Conversation</h1></center>

            <div class="chat-system">
                <!-- Chat Section -->

                <div class="chat" style="height: 320px;">
                <?php
                    if ($customer_inquiries_result->num_rows == 0){
                        echo '
                            <div class="no-messages" id="no-messages" style="display: none;">
                            No messages available.
                            </div>
                        ';
                    } else {
                        ?>
                    <div class="customer-name">Customer: <?php echo $customer_name; ?></div>
                    <div class="messages" id="conversation_box">
                    <?php
                        while($data = mysqli_fetch_array($customer_conversation_result)){
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
                            <p>
                                <?php
                                    $replied_by = $data['first_name']." ".$data['last_name'];
                                    if (($data['first_name'] == $_SESSION['user_first_name']) && ($data['last_name'] == $_SESSION['user_last_name'])){
                                        $replied_by = "You";
                                    };
                                    echo $replied_by.": ".$data['response'];
                                ?>
                            </p>
                        </div>
                        <?php
                            }
                        }
                        ?>
                    </div>
                    <p style="display: none" id="countdownDisplay">Time to refresh: 10 seconds</p>
                    
                    <form class="chat-input" action="process.php" method="post">
                        <input <?php echo ($isDisabled=='1' ? 'class="disabled"' : ''); ?>type="text" id="response" name="response" placeholder="Type your message..." <?php echo ($isDisabled=='1' ? 'disabled' : ''); ?> onchange="toggleButtonState()">
                        <button <?php echo ($isDisabled=='1' ? 'class="disabled"' : ''); ?>id="submit_response" name="submit_response" type="submit" <?php echo ($isDisabled=='1' ? 'disabled' : ''); ?>>Send</button>
                    </form>
                    
                    <?php } ?>
                </div>
            </div>
        </div>
        
        <div class="inbox">
            <div class="inbox-container">
                <center><h1>Inbox</h1></center>
                <?php
                    if ($customer_inquiries_result->num_rows == 0){
                        echo '
                            <div class="no-messages" id="no-messages" style="display: none;">
                            No messages available.
                            </div>
                        ';
                    } else {
                        echo '
                            <ul class="message-list" id="message-list">
                        ';
                        while($data = mysqli_fetch_array($customer_inquiries_result)){
                            if ($customer_id && ($data['customer_id'] === $customer_id)){
                                echo '<li class="message-item active" onclick="redirectToConvoPage('.$data['customer_id'].')"><span>From: '.$data['first_name']." ".$data['last_name'].'</span><p>'.substr($data['inquiry'], 0, 50).'</p></li>';
                            } else {
                                echo '<li class="message-item" onclick="redirectToConvoPage('.$data['customer_id'].')"><span>From: '.$data['first_name']." ".$data['last_name'].'</span><p>'.substr($data['inquiry'], 0, 50).'</p></li>';
                            };
                        };
                        echo '
                            </ul>
                        ';
                    }
                ?>
                
                <button onclick="loadMessages()">Load More</button>
            </div>

            
        </div>

        <script>
            function toggleButtonState() {
                var button = document.getElementById("submit_response");
                var text = document.getElementById("response");
                
                var feedback_is_disabled = "<?php echo $_SESSION["isDisabled"]; ?>";
                
                if (feedback_is_disabled === '1'){ // Disable the text-box and submit button if Pharmacist already sent message
                    button.disabled = true;
                } else if (text.value.length === 0){ // Disable the text-box and submit button if Pharmacist is responding but the message is blank
                    button.disabled = true;
                } else {
                    button.disabled = false;
                };

                resetInactivityTimer();
            };

            window.onload = function() {
                setActivePage("nav_messages");

                var convo_container = document.getElementById('conversation_box');
                convo_container.scrollTop = convo_container.scrollHeight;

                var inbox_container = document.getElementById('message-list');
                inbox_container.scrollTop = inbox_container.scrollHeight;

                document.body.onmousemove = resetInactivityTimer;
                document.body.onkeydown = resetInactivityTimer;

                resetInactivityTimer(); // Start the inactivity timer initially
            };

            function redirectToConvoPage(customer_id) {
                window.location.href = './index.php?customer_id='+customer_id;
            };

            function loadMessages(){
                let current_page = <?php echo $inbox_page_no ?>;
                let customer_id = <?php echo $customer_id ?>;
                let current_convo_page = <?php echo $convo_page_no ?>;
                current_page++;
                window.location.href = './index.php?page_no='+current_page+'&cpage_no='+current_convo_page+'&customer_id='+customer_id;
            }

        </script>
    </body>
</html>