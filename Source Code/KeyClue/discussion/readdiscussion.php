<!--Start of PHP -->
<?php
//Session start
session_start();

//Checks if session is not logged in
if(!isset($_SESSION['username']) || empty($_SESSION['username'])){
  header("Location: ..\loginpage.php");
  exit;
}

require_once '..\tools\config.php';

//Define login parameters
$date = "";
$body = "";
$username = $_SESSION['username'];
$disc = $_GET['ID'];
$year = $_GET['year'];

//Login processing on post
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $year = trim($_POST["year"]);
    $disc = trim($_POST["disc"]);
    $tz = 'America/Edmonton';
    $tz_obj = new DateTimeZone($tz);
    $today = new DateTime("now", $tz_obj);
    $date = $today->format('Y-m-d H:i:s');
    $body =  trim($_POST["body"]);
    $username = $_SESSION['username'];

    //Validate login credentials
        //Prepare statement
        $sql = "INSERT INTO comment
                    (Account_Username, Disc_ID, Body, Date_Posted) VALUES (?, ?, ?, ?)";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "siss", $query_username, $query_disc, $query_body, $query_date);
            $query_username = $username;
            $query_date = $date;
            $query_disc = $disc;
            $query_body = $body;
            //Execute statement
            if (mysqli_stmt_execute($stmt)) {
                header("Location: ../discussion/readdiscussion.php?ID=" . $disc . "&year=" . $year);
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
            KeyClue Discussion
        </title>
        <meta charset="UTF-8">
        <meta name="description" content="ZOO's KeyClue Archive. Access to this site is restricted to members of the ZOO KeyClue team. Contact a KeyClue administrator for access">
        <meta name="copyright" content="Copyright 2018 Jared Brintnell, Mitchell Newell, and Davis Roman">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://fonts.googleapis.com/css?family=Josefin+Sans" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Fira+Sans" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="clue.css">
        <link rel="stylesheet" type="text/css" href="readdiscussion.css">
        <link rel="icon" href="..\images\zoo.png">
    </head>
    <body>
        <div class="header">
            KeyClue Archive &middot; Discussion #<?php echo $_GET['ID']?>
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
                <a href="readdiscussion.php?ID=<?php echo $_GET['ID']?>&year=<?php echo $_GET['year']?>" style="text-decoration:none">Discussion #<?php echo $_GET['ID']?></a>
            </div>


        <?php
            //Connect to database
            require_once '..\tools\config.php';
            //Prepare statement
            $sql = "SELECT D.Title, D.Date, D.Description, D.Admin_Username
                        FROM discussion AS D
                                WHERE D.ID = ?";
            //Display results
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "i", $query_ID);
                $query_ID = $_GET['ID'];
                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);
                    if (mysqli_stmt_num_rows($stmt) > 0) {
                        mysqli_stmt_bind_result($stmt, $title, $date, $descr, $username);
                        if (mysqli_stmt_fetch($stmt)) {
                                echo '<div class="alldisc">
                                        <div class="titler">
                                            ' . $title .
                                            '<div class="bodys">
                                                ' . $descr . '
                                            </div>
                                            <div class="extras">
                                                ' . $date . '
                                            </div>
                                            <div class="extras">
                                                ' . $username . '
                                            </div>
                                        </div>
                                    </div>';
                            }
                        }
                    }
            }

            $sql = "SELECT C.Body, C.Date_Posted, C.Account_Username
                        FROM comment AS C
                                WHERE C.Disc_ID = ?
                                    ORDER BY C.Date_Posted";
            //Display results
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "i", $query_ID);
                $query_ID = $_GET['ID'];
                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);
                    if (mysqli_stmt_num_rows($stmt) > 0) {
                        mysqli_stmt_bind_result($stmt, $descr, $date, $username);
                        while (mysqli_stmt_fetch($stmt)) {
                            if (strcmp($username, $_SESSION['username']) == 0) {
                                echo '<div class="alldisc">
                                        <div class="contenterme">';
                            } else {
                                echo '<div class="alldisc">
                                        <div class="contenter">';
                            }
                                            echo '<div class="bodys">
                                                ' . $descr . '
                                            </div>
                                            <div class="extras">
                                                ' . $date . '
                                            </div>
                                            <div class="extras">
                                                ' . $username . '
                                            </div>
                                        </div>
                                    </div>';
                            }
                        }
                    }
            }
        ?>
            <div class="newcommenter">
                <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                    <div class="inputGroup">
                        <label>New Comment</label>
                        <textarea name="body" class="form-control" value="<?php echo $descr; ?>"></textarea>
                    </div>
                    <input type="hidden" name="disc" class="form-control" value="<?php echo $disc; ?>">
                    <input type="hidden" name="year" class="form-control" value="<?php echo $year; ?>">
                    <div class="inputGroup">
                        <button type="submit" id="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>
