<?php
include('config.php'); // Include database configuration
include('session.php');

// Check if the script has been included and log_conversion_data exists
if (!isset($_SESSION['log_conversion_data'])) {
    die('Invalid Request: Missing log_conversion_data.');
}

// Extract and sanitize data
$user_id = intval($log_conversion_data['user_id']);
$original_file = mysqli_real_escape_string($db, $log_conversion_data['original_file']);
$converted_file = mysqli_real_escape_string($db, $log_conversion_data['converted_file']);
$conversion_format = mysqli_real_escape_string($db, $log_conversion_data['conversion_format']);
$conversion_bitrate = intval($log_conversion_data['conversion_bitrate']);

// Validate data
if ($user_id > 0 && $original_file && $converted_file && $conversion_format && $conversion_bitrate > 0) {
    // Insert data into the database
    $sql = sprintf(
        "INSERT INTO conversionrequests (user_id, original_file, converted_file, conversion_format, conversion_bitrate) 
         VALUES (%d, '%s', '%s', '%s', %d)",
        $user_id,
        $original_file,
        $converted_file,
        $conversion_format,
        $conversion_bitrate
    );

    if (!mysqli_query($db, $sql)) {
        error_log("Database Error: " . mysqli_error($db));
        die('Database Error: Unable to log the conversion.');
    }
} else {
    die('Invalid Input: Missing or incorrect conversion data.');
}
