<?php

function login()
{
    $login_ok = false;
    if (isset($_POST['email'])) {
        $login_ok = true;
    }
    $email = $_POST['email'];
    $_SESSION['remember_email'] = $email;
    $clean_email = filter_var($email, FILTER_SANITIZE_EMAIL);
    if (!$email == $clean_email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $login_ok = false;
        $_SESSION['error_email'] = "Enter the correct Email address";
    }
    if (strlen($email) > 255) {
        $login_ok = false;
        $_SESSION['error_email'] = "Enter the correct Email address";
    }
    $password = $_POST['password'];
    if (strlen($password) < 7 || strlen($password) > 20) {
        $login_ok = false;
        $_SESSION['error_password'] = "Enter the correct password";
    }
    if ($login_ok) {
        require_once 'connect.php';
        try {
            if ($get_user_query = $connection->prepare("SELECT email, password, auth_token FROM user WHERE email = ?;")) {
                $get_user_query->bind_param('s', $email);
                $get_user_query->execute();
                $query_result = $get_user_query->get_result();
                if ($query_result->num_rows == 0) {
                    $_SESSION['error_email'] = "No account with this Email address";
                } else {
                    $account_data = $query_result->fetch_assoc();
                    if (password_verify($password, $account_data['password'])) {
                        $_SESSION['auth_token'] = $account_data['auth_token'];
                        setcookie('auth_token', $account_data['auth_token'], time() + (10 * 365 * 24 * 60 * 60));
                        $_SESSION['signed_in'] = true;
                        if (isset($_POST['remember'])) {
                            setcookie('signed_in', 1, time() + (10 * 365 * 24 * 60 * 60));
                        }
                        header('Location: index.php');
                    } else {
                        $_SESSION['error_password'] = "Password incorrect";
                    }
                }
            } else {
                throw new Exception("Database query error. Try again later.");
            }
            $connection->close();
        } catch (Exception $error) {
            $connection->close();
            echo $error->getMessage();
            exit();
        }
    }
}
