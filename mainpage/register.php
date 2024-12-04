<?php 

include 'connect.php';

if(isset($_POST['signUp'])){
    $firstName=$_POST['fName'];
    $lastName=$_POST['lName'];
    $address=$_POST['address'];
    $contact=$_POST['contact'];
    //$contact= mysqli_real_escape_string($conn,$_POST['contact']);
    $email=$_POST['email'];
    $password=$_POST['password'];
    $password=md5($password);

     $checkEmail="SELECT * From customer where email='$email'";
     $result=$conn->query($checkEmail);
     if($result->num_rows>0){
        echo "Email Address Already Exists !";
     }
     else{
        $insertQuery="INSERT INTO customer(firstName,lastName,address,contact,email,password)
                       VALUES ('$firstName','$lastName','$address','$contact','$email','$password')";
            if($conn->query($insertQuery)==TRUE){
                echo "Registration Successfull !";
                header("location: index.php");
            }
            else{
                echo "Error:".$conn->error;
            }
     }
   

}

if (isset($_POST['signIn'])) {
    $email = $_POST['email'];
    $password = md5($_POST['password']); // Hash the input password for database comparison

    // Query to check email and password in the database
    $sql = "SELECT * FROM customer WHERE email='$email' AND password='$password'";
    $result = $conn->query($sql);

    if ($result->num_rows > 0) {
        session_start();
        $row = $result->fetch_assoc();
        $_SESSION['email'] = $row['email'];
        header("Location: customerpage.php");
        exit;
    } 
    // Fixed email and password condition for admin
    elseif ($email === 'pharmanest@gmail.com' && $_POST['password'] === 'adminpassword') {
        session_start();
        $_SESSION['email'] = $email; // Set session for fixed login
        header("Location: adminpage.php"); // Redirect to admin or special page
        exit;
    } 
      // Fixed email and password condition for pharmacist
      elseif ($email === 'pharmanest@gmail.com' && $_POST['password'] === 'pharmacistpassword') {
        session_start();
        $_SESSION['email'] = $email; // Set session for fixed login
        header("Location: pharmacistpage.php"); // Redirect to admin or special page
        exit;
    } 
    else {
        echo "Not Found, Incorrect Email or Password.";
    }
}
?>