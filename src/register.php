<?php
    include("config.php");
    session_start();
    $error = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        $myusername = mysqli_real_escape_string($db, $_POST['username']);
        $mypassword = mysqli_real_escape_string($db, $_POST['password']); 
        $myemail = mysqli_real_escape_string($db, $_POST['email']);

        $checkUserSql = "SELECT * FROM users WHERE username = '$myusername'";
        $checkResult = mysqli_query($db, $checkUserSql);
        $count = mysqli_num_rows($checkResult);

        if ($count > 0) {
            header("location: registration.php?error=exists");
        } else {
            $hashedPassword = password_hash($mypassword, PASSWORD_DEFAULT); 
            $insertSql = "INSERT INTO users (username, password, email) VALUES ('$myusername', '$hashedPassword', '$myemail')";

            if (mysqli_query($db, $insertSql)) {
                $_SESSION['login_user'] = $myusername;
                header("location: choose-converter.php");
            } else {
                // Database error
                header("location: registration.php?error=dberror");
            }
        }
    }
?>