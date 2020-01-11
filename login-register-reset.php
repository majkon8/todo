<?php

session_start();

if (isset($_SESSION['signed_in']) || isset($_COOKIE['signed_in'])) {
    header('Location: index.php');
    exit();
}

if (isset($_POST['sign-up'])) {
    require_once 'register.php';
    register();
} else if (isset($_POST['sign-in'])) {
    require_once 'login.php';
    login();
} else if (isset($_POST['reset'])) {
    require_once 'reset.php';
    reset_password();
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
</head>

<body>
    <div class="form-container">
        <div class="form-container__circle">
            <i class="fas fa-user"></i>
        </div>
        <form id="login-form" name="login-form" action="<?php $_SERVER['PHP_SELF'] ?>" method="post" class="form-container__form">
            <ul class="form__description">
                <li id="sign-in" class="description__item">
                    Sign in
                    <div id="arrow-sign-in" class="arrow"></div>
                </li>
                <li id="sign-up" class="description__item">
                    Sign up
                    <div id="arrow-sign-up" class="arrow"></div>
                </li>
                <li id="reset" class="description__item">
                    Reset
                    <div id="arrow-reset" class="arrow"></div>
                </li>
            </ul>
            <div class="form__input--container">
                <i class="fas fa-envelope icon"></i>
                <input id="email-input" type="email" name="email" class="form__input" placeholder="Email" maxlength="255" required value="<?php
                                                                                                                                            if (isset($_SESSION['remember_email'])) {
                                                                                                                                                echo $_SESSION['remember_email'];
                                                                                                                                                unset($_SESSION['remember_email']);
                                                                                                                                            }                                                                                           ?>" />
                <?php
                if (isset($_SESSION['error_email'])) {
                    echo "<div class='error'>{$_SESSION['error_email']}</div>";
                    unset($_SESSION['error_email']);
                } ?>
                <?php
                if (isset($_SESSION['reset_confirm'])) {
                    echo "<div class='success'>{$_SESSION['reset_confirm']}</div>";
                    unset($_SESSION['reset_confirm']);
                } ?>
            </div>
            <div id="password-div" class="form__input--container">
                <i class="fas fa-lock icon"></i>
                <input id="password-input" type="password" name="password" class="form__input" placeholder="Password" minlength="7" maxlength="20" required />
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
            <div id="checkbox-container">
                <label class="form__input--checkbox--label"><input id="checkbox" type="checkbox" name="remember" />Keep me Signed In</label>
            </div>
            <input id="hidden" type="hidden" name="sign-in" />
            <button id="submit-button" type="submit" form="login-form" class="form__input form__input--button">
                Sign In
            </button>
            <?php
            if (isset($_SESSION['register_done'])) {
                echo "<div class='success'>{$_SESSION['register_done']}</div>";
                unset($_SESSION['register_done']);
            } ?>
        </form>
    </div>
    <script src="login-register-reset.js"></script>
</body>

</html>