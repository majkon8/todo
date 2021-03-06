<?php

$url = parse_url(getenv("CLEARDB_DATABASE_URL"));
$server = $url["host"];
$username = $url["user"];
$password = $url["pass"];
$db = substr($url["path"], 1);

return [
	'host' => $server,
	'user' => $username,
	'password' => $password,
	'database' => $db
];
