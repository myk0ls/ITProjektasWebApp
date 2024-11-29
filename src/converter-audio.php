<?php

include('session.php');
include('config.php'); 

ini_set('memory_limit', '256M'); 
set_time_limit(0);  

require 'vendor/autoload.php';  

use FFMpeg\FFMpeg; 
use FFMpeg\Format\Audio\Mp3; 
use FFMpeg\Format\Audio\Wav; 
use FFMpeg\Format\Audio\Vorbis;  

$target_dir = "/var/www/html/uploads/"; 
$converted_dir = "/var/www/html/converted/";  

if (!file_exists($target_dir)) mkdir($target_dir, 0755, true); 
if (!file_exists($converted_dir)) mkdir($converted_dir, 0755, true);  

$allowed_audio_formats = [  
    "wav", "aiff", "pcm", 
    "mp3", "aac", "wma", 
    "ogg", "flac", "m4a", 
    "opus", "webm", 
    "oga", "ac3", "eac3"
];      

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


if(isset($_FILES["fileToUpload"]) && $_FILES["fileToUpload"]["error"] == 0) {
    // Get file details     
    $original_filename = basename($_FILES["fileToUpload"]["name"]);     
    $fileType = strtolower(pathinfo($original_filename, PATHINFO_EXTENSION));      

    
    if (in_array($fileType, $allowed_audio_formats)) {         
        $target_file = $target_dir . $original_filename;     

        // remove a con amount
        $update_user_query = sprintf(
            "UPDATE users SET con_amount = con_amount - 1 WHERE id = %d",
            $user_id
        );
        
        if (!mysqli_query($db, $update_user_query)) {

            error_log("Failed to update user conversion amount: " . mysqli_error($db));
        }


        if (move_uploaded_file($_FILES["fileToUpload"]["tmp_name"], $target_file)) {      
            try {             

                $target_format = $_POST['format'];             
                $target_bitrate = $_POST['bitrate'];              

                
                $ffmpeg = FFMpeg::create();             
                $audio = $ffmpeg->open($target_file);              
 

                $safe_filename = str_replace(' ', '_', pathinfo($original_filename, PATHINFO_FILENAME));
                $output_filename = $converted_dir . $safe_filename . "." . $target_format;        

                
                switch ($target_format) {                 
                    case 'mp3':                     
                        $format = new Mp3();                     
                        break;                 
                    case 'wav':                     
                        $format = new Wav();                     
                        break;                 
                    case 'ogg':                     
                        $format = new Vorbis();        
                        break;             
                }             
                $format->setAudioKiloBitrate(intval($target_bitrate));          

                
                $audio->save($format, $output_filename);              

                $converted_filename = basename($output_filename);


                $log_conversion_data = [
                    'original_file' => $original_filename,
                    'converted_file' => $converted_filename,
                    'conversion_format' => $target_format,
                    'conversion_bitrate' => $target_bitrate,
                ];
                

                $sql = sprintf(
                    "INSERT INTO conversionrequests (user_id, original_file, converted_file, conversion_format, conversion_bitrate) 
                    VALUES ('%d', '%s', '%s', '%s', %d)",
                    $user_id,
                    mysqli_real_escape_string($db, $log_conversion_data['original_file']),
                    mysqli_real_escape_string($db, $log_conversion_data['converted_file']),
                    mysqli_real_escape_string($db, $log_conversion_data['conversion_format']),
                    intval($log_conversion_data['conversion_bitrate'])
                );

                if (!mysqli_query($db, $sql)) {
                    error_log("Database Logging Error: " . mysqli_error($db));
                    die("Logging failed. Check error logs.");
                }

                header("Location: download.php?file=". urlencode($converted_filename));
                exit;
            } catch (Exception $e) {             
                echo "Conversion Error: " . $e->getMessage();         
            }
        } else {
            die("Sorry, there was an error uploading your file.");
        }
    } else {         

        header("location: upload-audio.php?error=invalid");
        exit;
    } 
} else {     

    header("location: upload-audio.php?error=nofile");
    exit;
} 
?>