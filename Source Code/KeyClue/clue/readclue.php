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
$clue = $_GET['clue_ID'];
$year = $_GET['year'];
$timelimit = "";
$state = "";
$author = "";

//Login processing on post
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $year = trim($_POST["year"]);
    $clue = trim($_POST["clue"]);
    $tz = 'America/Edmonton';
    $tz_obj = new DateTimeZone($tz);
    $today = new DateTime("now", $tz_obj);
    $date = $today->format('Y-m-d H:i:s');
    $body =  trim($_POST["body"]);
    $username = $_SESSION['username'];

    //Validate login credentials
        //Prepare statement
        $sql = "";
        if ($_POST["solutionEh"] == "solve") {
            $sql = "UPDATE clue
                        SET State = 1
                            WHERE ID = ?";
            if ($stmt = mysqli_prepare($link, $sql)) {
                    mysqli_stmt_bind_param($stmt, "i", $query_id);
                    $query_id = $clue;
                    mysqli_stmt_execute($stmt);
                }
            $sql = "INSERT INTO solution
                        (Account_Username, Clue_ID, Body, Date) VALUES (?, ?, ?, ?)";
        } else {
            $sql = "INSERT INTO comment
                    (Account_Username, Clue_ID, Body, Date_Posted) VALUES (?, ?, ?, ?)";
        }
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "siss", $query_username, $query_clue, $query_body, $query_date);
            $query_username = $username;
            $query_date = $date;
            $query_clue = $clue;
            $query_body = $body;
            //Execute statement
            if (mysqli_stmt_execute($stmt)) {
                header("Location: ../clue/readclue.php?clue_ID=" . $clue . "&year=" . $year);
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
            KeyClue Clue
        </title>
        <meta charset="UTF-8">
        <meta name="description" content="ZOO's KeyClue Archive. Access to this site is restricted to members of the ZOO KeyClue team. Contact a KeyClue administrator for access">
        <meta name="copyright" content="Copyright 2018 Jared Brintnell, Mitchell Newell, and Davis Roman">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://fonts.googleapis.com/css?family=Josefin+Sans" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Fira+Sans" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="clue.css">
        <link rel="stylesheet" type="text/css" href="readclue.css">
        <link rel="icon" href="..\images\zoo.png">
    </head>
    <body>
        <div class="header">
            KeyClue Archive &middot; Clue #<?php echo $_GET['clue_ID']?>
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
                <a href="../year/yearselect.php" style="text-decoration:none">Competition Years</a>
            </div>


        <?php
            //Connect to database
            require_once '..\tools\config.php';
            //Prepare statement
            $sql = "SELECT C.Title, C.Date, C.Description, C.Acc_Username, C.Time_Sensitivity, C.State, C.Author, P.Storage, D.Source, A.File_Name, A.File_Location
                        FROM clue AS C LEFT JOIN physical AS P
                            ON (C.ID = P.Clue_ID) LEFT JOIN digital AS D
                                ON (C.ID = D.Clue_ID) LEFT JOIN attachment AS A
                                    ON (A.Clue_ID = C.ID)
                                        WHERE C.ID = ?";
            //Display results
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "i", $query_ID);
                $query_ID = $_GET['clue_ID'];
                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);
                    if (mysqli_stmt_num_rows($stmt) > 0) {
                        mysqli_stmt_bind_result($stmt, $title, $date, $descr, $username, $timelimit, $state, $author, $storage, $source, $filename, $fileloc);
                        if (mysqli_stmt_fetch($stmt)) {
                                echo '<div class="alldisc">
                                        <div class="titler">
                                            ' . $title .
                                            '<div class="bodys">
                                                ' . $descr . '
                                            </div>
                                            <div class="extras2">
                                                <a href="' . $fileloc . $filename . '">' . $filename . '</a>
                                            </div><div class="newline">
                                            </div>';
                                             if ($storage != null) {
                                                 echo '<div class="extras2">
                                                     Currently Stored: ' . $storage . '
                                                 </div>';
                                             }
                                             if ($source != null) {
                                                 echo '<div class="extras2">
                                                     Clue Source: ' . $source . '
                                                 </div>';
                                             }
                                            echo '<div class="newline">
                                            </div>
                                            <div class="extras">
                                                ' . $date . '
                                            </div>
                                            <div class="extras">
                                                ' . $username . '
                                            </div>';

                                            if ($state == 1) {
                                                echo '<div class="extras2" style="color:#00BB00">
                                                    COMPLETE <img src="..\images\complete.png" class="allicons"></img>
                                                </div>';
                                            } else {
                                                if ($timelimit != null) {
                                                    echo '<div class="extras2" style="color:#BBBB00">
                                                        TIMED <img src="..\images\timed.png" class="allicons"></img>
                                                    </div>
                                                    <div class="extras2">
                                                        Expires: ' . $timelimit . '
                                                    </div>';
                                                } else  {
                                                        echo '<div class="extras2" style="color:#BB0000">
                                                            INCOMPLETE <img src="..\images\incomplete.png" class="allicons"></img>
                                                        </div>';
                                                    }
                                            }
                                        echo '<div class="extras2">
                                            Author: ' . $author . '
                                        </div>
                                        </div>
                                    </div>';
                            }
                        }
                    }
            }

            $sql = "SELECT S.Body, S.Date, S.Account_Username
                        FROM solution AS S
                                WHERE S.Clue_ID = ?";
            //Display results
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "i", $query_ID);
                $query_ID = $_GET['clue_ID'];
                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);
                    if (mysqli_stmt_num_rows($stmt) > 0) {
                        mysqli_stmt_bind_result($stmt, $descr, $date, $username);
                        while (mysqli_stmt_fetch($stmt)) {
                                echo '<div class="alldisc">
                                        <div class="titler">
                                            Solution';
                                            echo '<a href="deletesolution.php?clue_ID='. $_GET['clue_ID'] .'&year=' . $_GET['year'] . '" title="Delete Solution"><img src="..\images\delete.png" class="actionicons"></img></a>';
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

            $sql = "SELECT C.Body, C.Date_Posted, C.Account_Username
                        FROM comment AS C
                                WHERE C.Clue_ID = ?
                                    ORDER BY C.Date_Posted";
            //Display results
            if ($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "i", $query_ID);
                $query_ID = $_GET['clue_ID'];
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
                    <?php if ($state != 1) {
                    echo '<div class="inputGroup">
                        <label><input type="checkbox" name="solutionEh" class="checker" value="solve">Solution</label>
                    </div>';
                } else {
                    echo '<input type="hidden" name="solutionEh" class="form-control" value="">';
                } ?>
                    <input type="hidden" name="clue" class="form-control" value="<?php echo $clue; ?>">
                    <input type="hidden" name="year" class="form-control" value="<?php echo $year; ?>">
                    <div class="inputGroup">
                        <button type="submit" id="submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </body>
</html>
