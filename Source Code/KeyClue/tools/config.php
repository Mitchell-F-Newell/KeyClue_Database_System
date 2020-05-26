<?php
//Credentials for database
define('DB_SERVER', 'localhost');
define('DB_USER', 'root');
define('DB_PASSWORD', 'mysql');
define('DB_SCHEMA', 'cpsc471');

//Attempt database connection
$link = mysqli_connect(DB_SERVER, DB_USER, DB_PASSWORD, DB_SCHEMA);

//Check connection
if ($link == false) {
    die("FATAL ERROR: Could not connect to db" . mysqli_connect_error());
}
?>
