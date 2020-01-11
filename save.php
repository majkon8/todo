<?php

require_once 'connect.php';
try {
    if ($insert_tasks_query = $connection->prepare("UPDATE account SET tasks = ? WHERE user_id = (SELECT id FROM user WHERE auth_token = ?);")) {
        $tasks = $_POST['tasks'];
        $auth_token = $_POST['authToken'];
        json_decode($tasks);
        if (json_last_error() == JSON_ERROR_NONE) {
            $insert_tasks_query->bind_param('ss', $tasks, $auth_token);
            $insert_tasks_query->execute();
        }
    } else {
        throw new Exception($connection->$error);
    }
    $connection->close();
} catch (Exception $error) {
    echo $error;
}
