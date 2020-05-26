<!--Start of PHP -->
<?php
//Session start
session_start();

//Checks if session is not logged in
if(!isset($_SESSION['username']) || empty($_SESSION['username'])){
  header("Location: ..\loginpage.php");
  exit;
}
?>

<!--Start of HTML -->
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>
            KeyClue Years
        </title>
        <meta charset="UTF-8">
        <meta name="description" content="ZOO's KeyClue Archive. Access to this site is restricted to members of the ZOO KeyClue team. Contact a KeyClue administrator for access">
        <meta name="copyright" content="Copyright 2018 Jared Brintnell, Mitchell Newell, and Davis Roman">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://fonts.googleapis.com/css?family=Josefin+Sans" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Fira+Sans" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="clue.css">
        <link rel="stylesheet" type="text/css" href="yearselect.css">
        <link rel="icon" href="..\images\zoo.png">
    </head>
    <body>
        <div class="header">
            KeyClue Archive &middot; Competition Years
            <div class="menuHeader">
                <a href="yearselect.php" style="text-decoration:none; color:#FFF">Competition Years</a>
                <?php if(isset($_SESSION['permissions']) && strcmp($_SESSION['permissions'], "admin") == 0) {
                    echo "&middot; <a href='../admin/admintools.php' style='text-decoration:none; color:#FFF'>Admin Tools</a>";
                }?>
                &middot; <a href="../account/manageaccount.php" style="text-decoration:none; color:#FFF"><?php echo $_SESSION['username'];?></a>
                <img src="..\images\zoo.png" class="zoomark"></img>
            </div>
        </div>
        <div class="contents">
            <div class="navigation">
                <a href="yearselect.php" style="text-decoration:none">Competition Years</a>
            </div>
            <?php if(isset($_SESSION['permissions']) && strcmp($_SESSION['permissions'], "admin") == 0) {
                echo '<div class="addbuttons">
                          <a href="addyear.php" style="text-decoration:none; color:#FFF">
                                <div class="adders">Add Year</div>
                          </a>
                      </div>';
            }?>
        <?php
            //Connect to database
            require_once '..\tools\config.php';
            //Prepare statement
            $sql = "SELECT CY.Year, CY.Theme, YK.Keymaster_Name
                        FROM COMPETITION_YEAR AS CY LEFT JOIN year_keymaster AS YK
                            ON (CY.Year = YK.Comp_Date)
                                ORDER BY CY.Year DESC";
            //Display results
            if ($stmt = mysqli_prepare($link, $sql)) {
                if (mysqli_stmt_execute($stmt)) {
                    mysqli_stmt_store_result($stmt);
                    if (mysqli_stmt_num_rows($stmt) > 0) {
                        mysqli_stmt_bind_result($stmt, $year, $theme, $keymaster);
                        $current_year = -1;
                        $count = 2;
                        while (mysqli_stmt_fetch($stmt)) {
                            if ($year != $current_year) {
                                for ($i = $count; $i < 2; $i++) {
                                    echo '<div class="keymaster">
                                    </div>';
                                }
                                if ($current_year != -1) {
                                    echo '<div class="activities">
                                        <a href="deleteyear.php?ID='. $current_year .'" title="Delete Year"><img src="..\images\delete.png" class="yearactionicons"></img></a>
                                        <a href="edityear.php?ID='. $current_year .'" title="Edit Year"><img src="..\images\edit.png" class="yearactionicons"></img></a>
                                    </div>
                                </div>
                            </div>
                        </a>';
                                }
                                $current_year = $year;
                                $count = 0;
                                echo '<a href="year.php?year=' . $year . '" style="text-decoration:none; color:#000">
                                    <div class="allyears">
                                        <div class="oneyear">
                                            ' . $year . ' &middot; ' . $theme .
                                            '<div class="keymaster">
                                                ' . $keymaster . '
                                            </div>';
                            } else {
                                echo '<div class="keymaster">
                                    ' . $keymaster . '
                                </div>';
                                $count++;
                            }
                        }
                        for ($i = $count; $i < 2; $i++) {
                            echo '<div class="keymaster">
                            </div>';
                        }
                        if ($current_year != -1) {
                            echo '<div class="activities">
                                <a href="deleteyear.php?ID='. $year .'" title="Delete Year"><img src="..\images\delete.png" class="yearactionicons"></img></a>
                                <a href="edityear.php?ID='. $year .'" title="Edit Year"><img src="..\images\edit.png" class="yearactionicons"></img></a>
                            </div>
                        </div>
                    </div>
                </a>';
                        }
                    }
                } else {
                    echo "Something has gone wrong with the internal database connection. Please try again later.";
                }
            }
            mysqli_close($link);
        ?>
        </div>
    </body>
</html>
