<?php

include('session.php');
include('config.php');

// Increase memory and execution time
ini_set('memory_limit', '512M'); // Increased for video processing
set_time_limit(0);

// Include FFmpeg library (ensure php-ffmpeg is installed)
require 'vendor/autoload.php';

use FFMpeg\FFMpeg;
use FFMpeg\Format\Video\X264;
use FFMpeg\Format\Video\WMV;
use FFMpeg\Coordinate\Dimension;

// Set upload and converted directories
$target_dir = "/var/www/html/uploads/";
$converted_dir = "/var/www/html/converted/";

// Ensure directories exist
if (!file_exists($target_dir)) mkdir($target_dir, 0755, true);
if (!file_exists($converted_dir)) mkdir($converted_dir, 0755, true);

// Allowed video formats
$allowed_video_formats = [  "mp4", "avi", "mov", "mkv", 
                            "wmv", "flv", "webm", 
                            "mpeg", "mpg", "m4v", 
                            "ts", "m3u8", 
                            "divx", "asf"];

$check_conversions_query = sprintf(
    "SELECT con_amount FROM users WHERE id = %d",
    $user_id
);
$result = mysqli_query($db, $check_conversions_query);
$user = mysqli_fetch_assoc($result);

if ($user['con_amount'] <= 0) {
    header("location: upload-video.php?error=no_conversions");
    exit;
}

if (isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["error"] == 0) {
    $original_filename = basename($_FILES["fileToUpload"]["name"]);
    $fileType = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION));
    
    if (in_array($fileType, $allowed_video_formats)) {
        $target_file = $target_dir . $original_filename;

        $update_user_query = sprintf(
            "UPDATE users SET con_amount = con_amount - 1 WHERE id = %d",
            $user_id
        );
        
        if (!mysqli_query($db, $update_user_query)) {
            error_log("Failed to update user conversion amount: " . mysqli_error($db));
        }
        
        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {
            try {
                $target_resolution = $_POST['resolution'];
                $target_bitrate = $_POST['bitrate'];


                $ffmpeg = FFMpeg::create();
                $video = $ffmpeg->open($target_file);

                $safe_filename = str_replace(' ', '_', pathinfo($original_filename, PATHINFO_FILENAME));
                $output_filename = $converted_dir . $safe_filename . "_converted.mp4";


                $format = new X264();
                $format->setKiloBitrate(intval(str_replace('k', '', $target_bitrate))); // Convert to bits
                $format->setAudioCodec('aac');

                // Resize video
                switch ($target_resolution) {
                    case '720':
                        $video->filters()->resize(new Dimension(1280, 720));
                        break;
                    case '480':
                        $video->filters()->resize(new Dimension(854, 480));
                        break;
                    case '360':
                        $video->filters()->resize(new Dimension(640, 360));
                        break;
                    case '240':
                        $video->filters()->resize(new Dimension(426, 240));
                        break;
                }


                $video->save($format, $output_filename);

                $converted_filename = basename($output_filename);


                $sql = sprintf(
                    "INSERT INTO conversionrequests (user_id, original_file, converted_file, conversion_format, conversion_bitrate) 
                    VALUES ('%d', '%s', '%s', '%s', %d)",
                    $user_id,
                    mysqli_real_escape_string($db, $original_filename),
                    mysqli_real_escape_string($db, $converted_filename),
                    mysqli_real_escape_string($db, $target_resolution),
                    intval($target_bitrate)
                );

                if (!mysqli_query($db, $sql)) {
                    error_log("Database Logging Error: " . mysqli_error($db));
                    die("Logging failed. Check error logs.");
                }


                header("Location: download.php?file=" . urlencode($converted_filename));
                exit;


            } catch (Exception $e) {
                echo "Conversion Error: " . $e->getMessage();
            }
        } else {
            header("location: upload-video.php?error=upload_failed");
            exit;
        }
    } else {
        header("location: upload-video.php?error=invalid");
        exit;
    }
} else {
    header("location: upload-video.php?error=nofile");
    exit;
}

?>
