<?php
    include('session.php');
    include('config.php');

    $query = "SELECT type FROM users WHERE username = '$login_session'";
    $result = mysqli_query($db, $query);
    $user = mysqli_fetch_assoc($result);


    $video_allowed = ($user['type'] === 'advanced');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Type Selection</title>

    <link rel="stylesheet" href="styles.css">
    
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f9f9f9;
            text-align: center;
            padding: 0;
            margin: 0;
        }
        .container {
            
        }
        .icon {
            font-size: 80px;
            margin-bottom: 20px;
        }
        .button-group {
            margin: 20px;
        }
        button {
            font-size: 18px;
            padding: 10px 20px;
            margin: 10px;
            cursor: pointer;
            border: none;
            border-radius: 5px;
            color: white;
            background-color: #5b4b8a;
        }
        button:hover {
            background-color: #4a3c6f;
        }
        
    </style>
</head>
<body>
    <a href="logout.php" class="logout">Atsijungti</a>
    <div class="container">
        <div class="icon">📁</div>
        <h2>Pasirinkite failo tipą</h2>
        <div class="button-group">
            <form action="upload-audio.php" method="post">
                <button type="submit">Įkelti garso failą</button>
            </form>
            <form action="upload-video.php" method="post" enctype="multipart/form-data">
                <button type="submit" id="file-upload-button" <?php echo $video_allowed ? '' : 'disabled'; ?>>
                    Įkelti vaizdo failą
                </button>
                <?php if (!$video_allowed): ?>
                    <p class="error-message">Jūs neturite teisės įkelti vaizdo failų</p>
                <?php endif; ?>
                <?php
                $sql = "SELECT * FROM users WHERE id = '$user_id'";
                $result = mysqli_query($db, $sql);
                $user = mysqli_fetch_assoc($result);
                ?>
                <h1>Likę konversijos kreditai: <?php echo $user['con_amount']; ?></h1>
            </form>
        </div>
    </div>
</body>
</html>