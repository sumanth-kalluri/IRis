<?php
define('db_host', 'localhost');
define('db_user', 'root');
define('db_password', '');
define('db_name', 'whiz');
$con = mysqli_connect(db_host, db_user, db_password, db_name) or die("Couldn't connect.");
?>
