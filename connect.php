<?php

$config = require_once 'config.php';
mysqli_report(MYSQLI_REPORT_STRICT);
try {
    $connection = new mysqli($config['host'], $config['user'], $config['password'], $config['database']);
    if ($connection->connect_errno != 0) {
        throw new Exception("Connection to database error.");
    } else {
        echo "Jest gites";
    }
} catch (Exception $error) {
    echo $error->getMessage();
    exit();
}
