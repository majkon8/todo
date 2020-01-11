<?php

session_start();

if (!isset($_GET['token'])) {
    header('Location: login-register-reset.php');
} else if (isset($_GET['token']) && !isset($_POST['reset'])) {
    // !isset($_POST['reset']) mean when user enter reset page from link
    $token = $_GET['token'];
    require_once 'connect.php';
    try {
        $filtered_token = filter_var($token, FILTER_SANITIZE_STRING);
        if ($filtered_token != $token) {
            throw new Exception("Access denied");
        }
        if ($get_token_query = $connection->prepare("SELECT token, token_exp FROM user WHERE token = ?;")) {
            $get_token_query->bind_param('s', $token);
            $get_token_query->execute();
            $query_result = $get_token_query->get_result();
            if ($query_result->num_rows == 0) {
                throw new Exception("Access denied");
            }
            $query_data = $query_result->fetch_assoc();
            $_SESSION['token'] = $token;
            $token_expire = date_create_from_format('Y-m-d H:i:s', $query_data['token_exp']);
            $date_now = new DateTime('NOW');
            if ($token_expire < $date_now) {
                throw new Exception("Reset password reqest too old");
            }
            $get_token_query->free_result();
        } else {
            throw new Exception("Database connection error. Try again later.");
        }
        $connection->close();
    } catch (Exception $error) {
        $connection->close();
        echo $error->getMessage();
        exit();
    }
} else if (isset($_POST['reset'])) {
    // when reset password button is pressed
    $reset_ok = true;
    $new_password = $_POST['password'];
    $password_repeat = $_POST['repeat-password'];
    if (strlen($new_password) < 7 || strlen($new_password) > 20) {
        $reset_ok = false;
        $_SESSION['error_password'] = "Password can have 7 to 20 characters";
    }
    if ($new_password != $password_repeat) {
        $reset_ok = false;
        $_SESSION['error_password_repeat'] = "Passwords have to be the same";
    }
    if ($reset_ok) {
        $password_hashed = password_hash($new_password, PASSWORD_DEFAULT);
        require_once 'connect.php';
        try {
            if ($reset_password_query = $connection->prepare("UPDATE user SET password = ?, token = NULL, token_exp = NULL where token = ?;")) {
                $reset_password_query->bind_param('ss', $password_hashed, $_SESSION['token']);
                $reset_password_query->execute();
                unset($_SESSION['token']);
                $_SESSION['password_changed'] = "Password changed";
            } else {
                throw new Exception("Database connection error. Try again later.");
            }
            $connection->close();
        } catch (Exception $error) {
            $connection->close();
            echo $error->getMessage();
            exit();
        }
    }
}

?>


<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width" />
    <title>ToDo List</title>
    <link rel="stylesheet" href="login-register-reset.css" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous" />
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet" />
    <style>
        #repeat-password-div {
            display: block;
            opacity: 1;
        }
    </style>
</head>

<body>
    <div class="form-container">
        <div class="form-container__circle">
            <i class="fas fa-user"></i>
        </div>
        <form id="reset-form" name="reset-form" action="<?php $_SERVER['PHP_SELF'] ?>" method="post" class="form-container__form">
            <div id="password-div" class="form__input--container">
                <i class="fas fa-lock icon"></i>
                <input id="password-input" type="password" name="password" class="form__input" placeholder="New Password" minlength="7" maxlength="20" required />
                <?php
                if (isset($_SESSION['error_password'])) {
                    echo "<div class='error'>{$_SESSION['error_password']}</div>";
                    unset($_SESSION['error_password']);
                } ?>
            </div>
            <div id="repeat-password-div" class="form__input--container">
                <i class="fas fa-lock icon"></i>
                <input id="repeat-password-input" type="password" name="repeat-password" class="form__input" placeholder="Repeat Password" minlength="7" maxlength="20" requred />
                <?php
                if (isset($_SESSION['error_password_repeat'])) {
                    echo "<div class='error'>{$_SESSION['error_password_repeat']}</div>";
                    unset($_SESSION['error_password_repeat']);
                } ?>
            </div>
            <input id="hidden" type="hidden" name="reset" />
            <button id="submit-button" type="submit" form="reset-form" class="form__input form__input--button">
                Reset Password
            </button>
            <?php
            if (isset($_SESSION['password_changed'])) {
                echo "<div class='success'>{$_SESSION['password_changed']}</div>";
                unset($_SESSION['password_changed']);
            } ?>
        </form>
        <div id="return-to-login">
            <a id="return-to-login-button" href="login-register-reset.php">Log In</a>
        </div>
    </div>
    <script>
        document.getElementById("return-to-login-button").onclick = () => {
            localStorage.setItem("page", "sign-in");
        }
    </script>
</body>

</html>