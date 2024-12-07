<!DOCTYPE html>
<?php
    
    function sendSMSMain($ch){
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true); // Return response as a string
        curl_setopt($ch, CURLOPT_POST, true); // Specify the POST method
        // Execute the cURL request
        $response = curl_exec($ch);

        // Check for errors
        if ($response === false) {
            error_log('Curl error: ' . curl_error($ch));
        } else {
            error_log('Response: ' . $response);
        };
        curl_close ($ch);
    };

    function sendSMSViaInfoBIP($receiver_mobile_number, $message){
        $INFOBIP_API_BASE_URL = "";
        $INFO_BIP_API_KEY = "";

        $ch = curl_init('https://'.$INFOBIP_API_BASE_URL.'/sms/2/text/advanced');
        
        // Set cURL headers
        curl_setopt($ch, CURLOPT_HTTPHEADER, [
            'Content-Type: application/json', 
            'Authorization: App '.$INFO_BIP_API_KEY, 
            'Accept: application/json'
        ]);

        // Auto-update mobile_number format required by Service Provider
        if (strpos($receiver_mobile_number, "+") == false){
            $receiver_mobile_number = '+63' . substr($receiver_mobile_number, 1);
        };

        $payload = json_encode([
            'messages' => [
                [
                    'destinations' => [['to' => $receiver_mobile_number]],
                    'text' => $message
                ]
            ]
        ]);

        // Attach the JSON data to the request
        curl_setopt($ch, CURLOPT_POSTFIELDS, $payload);
        sendSMSMain($ch);
    };


    function sendSMS($receiver_mobile_number, $message){
        sendSMSViaInfoBIP($receiver_mobile_number, $message);
    };
    
?>