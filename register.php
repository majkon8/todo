<?php

function register()
{
    $register_ok = false;
    if (isset($_POST['email'])) {
        $register_ok = true;
    }
    $email = $_POST['email'];
    $_SESSION['remember_email'] = $email;
    $clean_email = filter_var($email, FILTER_SANITIZE_EMAIL);
    if (!$email == $clean_email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $register_ok = false;
        $_SESSION['error_email'] = "Enter the correct Email address";
    }
    if (strlen($email) > 255) {
        $register_ok = false;
        $_SESSION['error_email'] = "Email can be 255 characters long";
    }
    $password = $_POST['password'];
    if (strlen($password) < 7 || strlen($password) > 20) {
        $register_ok = false;
        $_SESSION['error_password'] = "Password can have 7 to 20 characters";
    }
    $password_repeat = $_POST['repeat-password'];
    if ($password != $password_repeat) {
        $register_ok = false;
        $_SESSION['error_password_repeat'] = "Passwords have to be the same";
    }
    if ($register_ok) {
        require_once 'connect.php';
        try {
            // First check if email is not registered yet
            if ($get_email_query = $connection->prepare("SELECT email FROM user WHERE email = ?;")) {
                $get_email_query->bind_param('s', $email);
                $get_email_query->execute();
                $email_check = $get_email_query->fetch();
                if ($email_check) {
                    $_SESSION['error_email'] = "This Email address is already registered";
                    $get_email_query->free_result();
                } else {
                    $get_email_query->free_result();
                    if ($register_query = $connection->prepare("INSERT INTO user (password, email, auth_token) VALUES (?, ?, ?);")) {
                        $password_hashed = password_hash($password, PASSWORD_DEFAULT);
                        $auth_token = hash("sha256", $password . strval(mt_rand()) . $email);
                        $register_query->bind_param('sss', $password_hashed, $email, $auth_token);
                        $register_query->execute();
                        // The end of registration, now its time to add starting tasks to db
                        $register_query->free_result();
                        if ($get_user_id_query = $connection->prepare("SELECT id FROM user WHERE email = ?;")) {
                            $get_user_id_query->bind_param('s', $email);
                            $get_user_id_query->execute();
                            $query_result = $get_user_id_query->get_result();
                            if ($query_result->num_rows == 0) {
                                throw new Exception('Database query result error. Try again later.');
                            }
                            $query_data = $query_result->fetch_assoc();
                            $user_id = $query_data['id'];
                            $tasks = '{"current":[],"done":[]}';
                            $get_user_id_query->free_result();
                            if ($insert_tasks_query = $connection->prepare("INSERT INTO account (user_id, tasks) VALUES (?,?)")) {
                                $insert_tasks_query->bind_param('is', $user_id, $tasks);
                                $insert_tasks_query->execute();
                            } else {
                                throw new Exception('Database tasks query error. Try again later.');
                            }
                        } else {
                            throw new Exception('Database user query error. Try again later.');
                        }
                        $_SESSION['register_done'] = "You can sign in to your account";
                    } else {
                        throw new Exception('Database register query error. Try again later.');
                    }
                }
            } else {
                throw new Exception('Email address already taken');
            }
            $connection->close();
        } catch (Exception $error) {
            $connection->close();
            echo $error->getMessage();
            exit();
        }
    }
}
