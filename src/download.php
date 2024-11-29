<?php

include('session.php');

// Check if the file parameter is provided
if (!isset($_GET['file']) || empty($_GET['file'])) {
    die("No file specified for download.");
}

// Sanitize the file name to prevent malicious input
$file = htmlspecialchars($_GET['file']);

// Define the directory where the converted files are stored
$converted_dir = "/var/www/html/converted/";

// Full file path
$file_path = $converted_dir . $file;

// Check if the file exists
if (!file_exists($file_path)) {
    die("The specified file does not exist.");
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Atsisiųsti</title>
    <link rel="stylesheet" href="styles.css">
</head>
<body>
    <div class="download-container">
        <h1>Konvertacija įvykdyta</h1>
        <p>Jūsų failą galima atsisiųsti:</p>
        <a href="/converted/<?php echo urlencode($file); ?>" download>Atsisiųsti failą</a>

        <form action="choose-converter.php">
            <button type="submit">Konvertuoti naują failą</button>
        </form>

    </div>
</body>
</html>