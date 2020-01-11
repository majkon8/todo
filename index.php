<?php

session_start();

if (!isset($_SESSION['signed_in']) && !isset($_COOKIE['signed_in'])) {
    header('Location: login-register-reset.php');
    exit();
}

require_once 'connect.php';

if (!isset($_COOKIE['auth_token'])) {
    $auth_token = $_SESSION['auth_token'];
} else {
    $auth_token = $_COOKIE['auth_token'];
}
$get_tasks_query = "SELECT tasks FROM account WHERE user_id = (SELECT id FROM user WHERE auth_token = '{$auth_token}');";
$query_result = $connection->query($get_tasks_query);
if ($query_result->num_rows > 0) {
    while ($row = $query_result->fetch_assoc()) {
        $tasks = $row['tasks'];
    }
    $connection->close();
} else {
    $connection->close();
    exit("Error. Please try later.");
} ?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title>ToDo List</title>
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <link rel="stylesheet" href="index.css" />
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet" />
    <link rel="stylesheet" href="https://use.fontawesome.com/releases/v5.8.2/css/all.css" integrity="sha384-oS3vJWv+0UjzBfQzYUhtDYW+Pj2yciDJxpsK1OYPAYjqT085Qq/1cq5FLXAZQ7Ay" crossorigin="anonymous" />
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css" />
    <script>
        const tasks = <?php echo $tasks ?>;
        const authToken = "<?php echo $auth_token ?>";
    </script>
</head>

<body class="transition">
    <div id="container" class="container transition">
        <div id="top-bar" class="top-bar transition">
            <div id="date" class="top-bar__date transition"></div>
            <div title="Toggle menu" class="top-bar__menu toggle-menu">
                <i id="show-menu" class="fas fa-bars transition"></i>
            </div>
            <div style="clear: both"></div>
            <div class="top-bar__input-div">
                <input id="input" type="text" class="input-div__input" placeholder="Enter a task..." maxlength="50" required />
            </div>
            <div id="add-button" class="top-bar__button transition color" title="Add new task">
                +
            </div>
        </div>
        <div id="main-div" class="main">
            <div id="current-tasks" class="main__tasks main__tasks--current transition">
                <span id="no-current" class="empty-indicator transition">You don't have anything to do today!</span>
            </div>
            <div class="break list-transition"></div>
            <div id="done-tasks" class="main__tasks main__tasks--done transition">
                <span id="no-done" class="empty-indicator transition">You don't have any completed tasks</span>
            </div>
            <div class="break list-transition"></div>
        </div>
    </div>
    <div id="menu-container" class="menu-container">
        <div id="menu" class="menu-container__menu transition">
            <div title="Toggle menu" class="menu__bars toggle-menu">
                <i id="hide-menu" class="fas fa-bars"></i>
            </div>
            <div class="menu__section menu__themes transition">
                <div class="themes__title">THEMES</div>
                <div class="break list-transition"></div>
                <div id="light-theme" class="themes__option themes__option--light transition">
                    Light theme
                </div>
                <div id="dark-theme" class="themes__option themes__option--dark transition">
                    Dark theme
                </div>
            </div>
            <div class="menu__section menu__colors transition">
                <div class="colors__title">COLORS</div>
                <div class="break list-transition"></div>
                <div class="colors__icons">
                    <div id="green-1" class="icons__icon"></div>
                    <div id="green-2" class="icons__icon"></div>
                    <div id="blue-1" class="icons__icon"></div>
                    <div id="blue-2" class="icons__icon"></div>
                    <div id="red-1" class="icons__icon"></div>
                    <div id="red-2" class="icons__icon"></div>
                    <div id="gold" class="icons__icon"></div>
                    <div id="orange" class="icons__icon"></div>
                    <div id="brown" class="icons__icon"></div>
                    <div id="pink-1" class="icons__icon"></div>
                    <div id="pink-2" class="icons__icon"></div>
                    <div id="transparent" class="icons__icon"></div>
                    <div id="white" class="icons__icon"></div>
                    <div id="black" class="icons__icon"></div>
                    <div id="gray" class="icons__icon"></div>
                </div>
            </div>
            <div id="log-out" class="menu__section menu__log-out transition" title="Log out">
                <a href=<?php echo "http://localhost/todo/logout.php" ?>> Log out</a><i class="fas fa-sign-out-alt log-out-icon"></i>
            </div>
        </div>
    </div>
    <script id="calendar-script" src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script src="Sortable.min.js"></script>
    <script src="index.js"></script>
</body>

</html>