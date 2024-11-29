<?php
    include('session.php');
?>

<!DOCTYPE html>
<html>
<head>
    <title>Failo įkėlimas</title>
    
    <link rel="stylesheet" href="styles.css">

    <style>

        .upload-container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
            text-align: center;
        }

        .upload-container h2 {
            margin-top: 0;
        }

        .upload-container input[type="file"] {
            margin: 20px 0;
        }

        .upload-container button {
            background-color: #5b4b8a;
            color: white;
            border: none;
            padding: 10px 20px;
            text-align: center;
            text-decoration: none;
            display: inline-block;
            font-size: 16px;
            border-radius: 5px;
            cursor: pointer;
        }

        .upload-container button:hover {
            background-color: #4a3c6f;
        }
    </style>
</head>
<body>
    <div class="upload-container">
        <h2>Upload File</h2>
        <form action="converter-video.php" method="post" enctype="multipart/form-data">

            <div class="form-group">
                <input type="file" name="fileToUpload" id="fileToUpload" accept="video/*">
            </div>
            
            <div class="form-group">
            <label for="resolution">Pasirinkite rezoliucija:</label>
            <select name="resolution" id="resolution">
                <option value="720">720p</option>
                <option value="480">480p</option>
                <option value="360">360p</option>
                <option value="240">240p</option>
            </select>
        </div>
        
        <div class="form-group">
            <label for="bitrate">Pasirinkite bitrate:</label>
            <select name="bitrate" id="bitrate">
                <option value="1000k">1000 kbps</option>
                <option value="750k">750 kbps</option>
                <option value="500k">500 kbps</option>
                <option value="250k">250 kbps</option>
            </select>
        </div>
            <button type="submit">Konvertuoti</button>
        </form>
        <form action="choose-converter.php">
            <button type="submit">Atgal</button>
        </form>
         <!-- Error message -->
     <?php
     // Check if the error query parameter exists and equals "invalid"
     if (isset($_GET['error']) && $_GET['error'] === 'invalid') {
         echo '<div class="error-message">Blogas failo formatas. Pabandykite iš naujo.</div>';
     }
     if (isset($_GET['error']) && $_GET['error'] === 'nofile') {
        echo '<div class="error-message">Neįkeltas failas. Pabandykite iš naujo.</div>';
    }
    if (isset($_GET['error']) && $_GET['error'] === 'no_conversions') {
        echo '<div class="error-message">Nebeturite tokenų atlikti konvertacijas. Pabandykite iš naujo.</div>';
    }
     ?>
    </div>
</body>
</html>