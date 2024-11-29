<?php
    include("config.php");
    session_start();
    $error = '';

    if ($_SERVER["REQUEST_METHOD"] == "POST") {

        // Retrieve username and password from form
        $myusername = mysqli_real_escape_string($db, $_POST['username']);
        $mypassword = mysqli_real_escape_string($db, $_POST['password']); 


        // Fetch user from database
        $sql = "SELECT * FROM users WHERE username = '$myusername'";
        $result = mysqli_query($db, $sql);

        if (mysqli_num_rows($result) == 1) {
            // Fetch the user row
            $user = mysqli_fetch_assoc($result);

            // Verify the hashed password
            if ((password_verify($mypassword, $user['password']))) {
                // Password matches, log the user in
                $_SESSION['login_user'] = $myusername;
                $_SESSION['login_user_id'] = $user['id'];
                if ($user['type'] === 'administrator') {
                header("location: admin-page.php");
                } else{
                    header("location: choose-converter.php");
                }
            } else {
                // Invalid password
                header("location: index.php?error=invalid");
            }
        } else {
            // Username not found
            header("location: index.php?error=invalid");
        }
    }
?>