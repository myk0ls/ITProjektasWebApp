<?php
    define('DB_SERVER', 'mysql');
    define('DB_USERNAME', 'root');
    define('DB_PASSWORD', 'admin');
    define('DB_DATABASE', 'convert');
    $db = mysqli_connect(DB_SERVER,DB_USERNAME,DB_PASSWORD,DB_DATABASE);
?>