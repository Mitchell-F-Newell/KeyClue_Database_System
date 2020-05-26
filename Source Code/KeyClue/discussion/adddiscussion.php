<!--Start of PHP -->
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

//Define login parameters
$year = $_GET['year'];
$title = "";
$date = "";
$descr = "";
$username = $_SESSION['username'];

//Login processing on post
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $year = trim($_POST["year"]);
    $title = trim($_POST["title"]);
    $tz = 'America/Edmonton';
    $tz_obj = new DateTimeZone($tz);
    $today = new DateTime("now", $tz_obj);
    $date = $today->format('Y-m-d H:i:s');
    $descr =  trim($_POST["descr"]);
    $username = $_SESSION['username'];

    //Validate login credentials
        //Prepare statement
        $sql = "INSERT INTO discussion
                    (Title, Date, Description, Competition_Date, Admin_Username) VALUES (?, ?, ?, ?, ?)";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "sssis", $query_title, $query_date, $query_descr, $query_comp_date, $query_admin);
            $query_title = $title;
            $query_date = $date;
            $query_descr = $descr;
            $query_comp_date = $year;
            $query_admin = $username;
            //Execute statement
            if (mysqli_stmt_execute($stmt)) {
                header("Location: ../year/year.php?year=" . $year);
            } else {
            }
        }
        //Close statement
        mysqli_stmt_close($stmt);
    //Close connection
    mysqli_close($link);
}
?>

<!--Start of HTML -->
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>
            KeyClue Add Discussion
        </title>
        <meta charset="UTF-8">
        <meta name="description" content="ZOO's KeyClue Archive. Access to this site is restricted to members of the ZOO KeyClue team. Contact a KeyClue administrator for access">
        <meta name="copyright" content="Copyright 2018 Jared Brintnell, Mitchell Newell, and Davis Roman">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://fonts.googleapis.com/css?family=Josefin+Sans" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Fira+Sans" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="clue.css">
        <link rel="stylesheet" type="text/css" href="addyear.css">
        <link rel="icon" href="..\images\zoo.png">
    </head>
    <body>
        <div class="header">
            KeyClue Archive &middot; Add Discussion
            <div class="menuHeader">
                <a href="../year/yearselect.php" style="text-decoration:none; color:#FFF">Competition Years</a>
                <?php if(isset($_SESSION['permissions']) && strcmp($_SESSION['permissions'], "admin") == 0) {
                    echo "&middot; <a href='../admin/admintools.php' style='text-decoration:none; color:#FFF'>Admin Tools</a>";
                }?>
                &middot; <a href="../account/manageaccount.php" style="text-decoration:none; color:#FFF"><?php echo $_SESSION['username'];?></a>
                <img src="..\images\zoo.png" class="zoomark"></img>
            </div>
        </div>
        <div class="contents">
            <div class="navigation">
                <a href="../year/yearselect.php" style="text-decoration:none">Competition Years</a> &rsaquo;
                <a href="adddiscussion.php?year=<?php echo $_GET['year']?>" style="text-decoration:none">Add Discussion</a>
            </div>
                <div class="wrapper">
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                <div class="inputGroup">
                                    <label>Title</label>
                                    <input type="text" name="title" class="form-control" value="<?php echo $title; ?>" required>
                                </div>
                                <div class="inputGroup">
                                    <label>Description</label>
                                    <textarea name="descr" class="form-control" value="<?php echo $descr; ?>"></textarea>
                                </div>
                                <input type="hidden" name="year" class="form-control" value="<?php echo $year; ?>">
                                <div class="inputGroup">
                                    <button type="submit" id="submit">Submit</button>
                                </div>
                            </form>
            </div>
        </div>
    </body>
</html>
