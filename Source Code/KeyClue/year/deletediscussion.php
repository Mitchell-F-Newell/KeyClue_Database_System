<?php
//Session start
session_start();

//Checks if session is not logged in
if(!isset($_SESSION['username']) || empty($_SESSION['username'])){
  header("Location: ..\loginpage.php");
  exit;
}

if(!isset($_SESSION['permissions']) || strcmp($_SESSION['permissions'], "admin") != 0){
  header("Location: year.php?year=" .  $_GET['year']);
  exit;
}

//Connect to database
require_once '..\tools\config.php';
//delete statement
$sql = "DELETE
            FROM discussion
                WHERE ID = ?";
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $query_id);
    $query_id = $_GET['ID'];
    //Execute statement
    if (mysqli_stmt_execute($stmt)) {
        header("Location: year.php?year=" .  $_GET['year']);
        exit;
    }
}
mysqli_stmt_close($stmt);
mysqli_close($link);
header("Location: year.php?year=" . $_GET['year']);
?>
