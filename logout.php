<?php

session_start();

unset($_SESSION['signed_in']);
setcookie('signed_in', 1, time() - 3600);

header('Location: login-register-reset.php');
