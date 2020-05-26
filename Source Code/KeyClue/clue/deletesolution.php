<?php
//Session start
session_start();

//Checks if session is not logged in
if(!isset($_SESSION['username']) || empty($_SESSION['username'])){
  header("Location: ..\loginpage.php");
  exit;
}

//Connect to database
require_once '..\tools\config.php';

$sql = "DELETE
            FROM solution
                WHERE Clue_ID = ?";
if ($stmt = mysqli_prepare($link, $sql)) {
    mysqli_stmt_bind_param($stmt, "i", $query_id);
    $query_id = $_GET['clue_ID'];
    //Execute statement
    if (mysqli_stmt_execute($stmt)) {
        $sql = "UPDATE clue
                    SET State = 0
                        WHERE ID = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "i", $query_id);
                $query_id = $_GET['clue_ID'];
                mysqli_stmt_execute($stmt);
            }

        header("Location: readclue.php?clue_ID=" . $_GET['clue_ID'] . "&year=" .  $_GET['year']);
        exit;
    }
}
mysqli_stmt_close($stmt);
mysqli_close($link);
header("Location: year.php?year=" . $_GET['year']);
?>
