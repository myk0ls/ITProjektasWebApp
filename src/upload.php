<?php
// Set the target directory relative to the current script's location
$target_dir = "/var/www/html/uploads/";

// Set upload max file size to 10 megabytes
ini_set('upload_max_filesize', '10M');

// Also recommended to set post_max_size slightly larger
ini_set('post_max_size', '11M');


// Check if a file was uploaded
if(isset($_FILES["fileToUpload"])) {
    $target_file = $target_dir . basename($_FILES["fileToUpload"]["name"]);
    $uploadOk = 1;
    $fileType = strtolower(pathinfo($target_file, PATHINFO_EXTENSION));

    // Check if file already exists
    if (file_exists($target_file)) {
        echo "Sorry, file already exists.";
        $uploadOk = 0;
    }

    // Check file size (limit to 5MB)
    if ($_FILES["fileToUpload"]["size"] > 5000000) {
        echo "Sorry, your file is too large.";
        $uploadOk = 0;
    }

    // Audio formats
$audio_formats = [
    // Uncompressed
    "wav", "aiff", "pcm", 
    
    // Compressed
    "mp3", "aac", "wma", 
    "ogg", "flac", "m4a", 
    "opus", "webm", 
    
    // Advanced formats
    "oga", "ac3", "eac3"
];

// Video formats
$video_formats = [
    // Common compressed formats
    "mp4", "avi", "mov", "mkv", 
    "wmv", "flv", "webm", 
    
    // High quality/professional
    "mpeg", "mpg", "m4v", 
    
    // Streaming formats
    "ts", "m3u8", 
    
    // Less common but supported
    "divx", "asf"
];

// Combine audio and video formats
$allowed_types = array_merge($audio_formats, $video_formats);
    if (!in_array($fileType, $allowed_types)) {
        echo "Sorry, this file format is not supported for conversion.";
        $uploadOk = 0;
    }

    // Check if $uploadOk is set to 0 by an error
    if ($uploadOk == 0) {
        echo "Sorry, your file was not uploaded.";
    } else {
        // Try to upload file
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            echo "The file ". basename($_FILES["fileToUpload"]["name"]). " has been uploaded.";
        } else {
             // Capture potential reasons for upload failure
        echo "Upload failed. Debug information:\n";
        echo "Temporary file: " . $_FILES["fileToUpload"]["tmp_name"] . "\n";
        echo "Target file: " . $target_file . "\n";
        echo "File exists check: " . (file_exists($target_file) ? "Yes" : "No") . "\n";
        echo "Directory permissions: " . decoct(fileperms(dirname($target_file))) . "\n";
        
        // Check PHP upload settings
        echo "max_file_size: " . ini_get('upload_max_filesize') . "\n";
        echo "post_max_size: " . ini_get('post_max_size') . "\n";
        
        // Log the full file upload details
        error_log(print_r($_FILES, true));
        }
    }
}
?>

<html>
<a href="http://localhost/file-upload.html">retry</a>
</html>
