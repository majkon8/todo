<?php

function reset_password()
{
    $reset_ok = false;
    if (isset($_POST['email'])) {
        $reset_ok = true;
    }
    $email = $_POST['email'];
    $_SESSION['remember_email'] = $email;
    $clean_email = filter_var($email, FILTER_SANITIZE_EMAIL);
    if (!$email == $clean_email || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $reset_ok = false;
        $_SESSION['error_email'] = "Enter the correct Email address";
    }
    if (strlen($email) > 255) {
        $reset_ok = false;
        $_SESSION['error_email'] = "Enter the correct Email address";
    }
    if ($reset_ok) {
        require_once 'connect.php';
        try {
            if ($get_email_query = $connection->prepare("SELECT email FROM user WHERE email = ?;")) {
                $get_email_query->bind_param('s', $email);
                $get_email_query->execute();
                $email_check = $get_email_query->fetch();
                if (!$email_check) {
                    $_SESSION['error_email'] = 'No account with this Email address';
                } else {
                    $get_email_query->free_result();
                    // Create and send password reset token
                    $token = bin2hex(random_bytes(50));
                    $token_expires = new DateTime('NOW');
                    $token_expires->add(new DateInterval('PT01H'));
                    $token_expires = $token_expires->format('Y-m-d H:i:s');
                    if ($token_query = $connection->prepare("UPDATE user SET token = ?, token_exp = ? WHERE email = ?;")) {
                        $token_query->bind_param('sss', $token, $token_expires, $email);
                        $token_query->execute();
                        $ADMIN_NAME = "ToDo";
                        $ADMIN_EMAIL = "majkonserver@gmail.com";
                        $url = "http://localhost/todo/reset-password.php?token={$token}";
                        $to = $email;
                        $subject = 'Your password reset link';
                        $message = '<p>We recieved a password reset request. The link to reset your password is below. ';
                        $message .= 'If you did not make this request, you can ignore this email</p>';
                        $message .= '<p>Here is your password reset link:</br>';
                        $message .= sprintf('<a href="%s">%s</a></p>', $url, $url);
                        $message .= '<p>Thanks!</p>';
                        $headers = "From: " . $ADMIN_NAME . " <" . $ADMIN_EMAIL . ">\r\n";
                        $headers .= "Reply-To: " . $ADMIN_EMAIL . "\r\n";
                        $headers .= "Content-type: text/html\r\n";
                        $sent = mail($to, $subject, $message, $headers);
                        if ($sent) {
                            $_SESSION['reset_confirm'] = "Check email to reset password";
                        } else {
                            $_SESSION['error_email'] = "Something went wrong";
                        }
                    } else {
                        throw new Exception('Database query error');
                    }
                }
            }
            $connection->close();
        } catch (Exception $error) {
            $connection->close();
            echo $error->getMessage();
            exit();
        }
    }
}
