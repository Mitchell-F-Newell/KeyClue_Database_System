<?php
//Session start
session_start();

//Remove all session variables
$_SESSION = array();

//Session stop
session_destroy();

//Back to login
header("Location: ..\loginpage.php");
exit;
?>
