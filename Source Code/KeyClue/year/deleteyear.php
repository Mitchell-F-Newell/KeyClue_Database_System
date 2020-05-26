<?php
//Session start
session_start();

//Checks if session is not logged in
if(!isset($_SESSION['username']) || empty($_SESSION['username'])){
  header("Location: ..\loginpage.php");
  exit;
}

if(!isset($_SESSION['permissions']) || strcmp($_SESSION['permissions'], "admin") != 0){
  header("Location: yearselect.php");
  exit;
}

//Connect to database
require_once '..\tools\config.php';
// sql delete statement
$sql = "DELETE
            FROM competition_year
                WHERE Year = ?";
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $query_year);
    $query_year = $_GET['ID'];
    //Execute statement
    if (mysqli_stmt_execute($stmt)) {
        header("Location: yearselect.php");
    }
}
mysqli_stmt_close($stmt);
mysqli_close($link);
header("Location: yearselect.php");

?>
