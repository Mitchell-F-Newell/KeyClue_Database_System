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
$year = trim($_GET['ID']);
$theme = "";
$numkm = "";

        $sql = "SELECT CY.Year, CY.Theme, YK.Keymaster_Name
                    FROM competition_year AS CY  LEFT JOIN year_keymaster AS YK
                        ON (CY.Year = YK.Comp_Date)
                            WHERE CY.Year = ?";
        //Display results
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "i", $query_year);
            $query_year = $year;
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                if (mysqli_stmt_num_rows($stmt) > 0) {
                    $numkm = mysqli_stmt_num_rows($stmt);
                    mysqli_stmt_bind_result($stmt, $year, $theme, $kmname);
                    while (mysqli_stmt_fetch($stmt)) {

                    }
                }
            }
        }



$year_err = "";

//Login processing on post
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $year = trim($_POST["year"]);
    $theme = trim($_POST["theme"]);

    //Validate login credentials
    if (empty($year_err)) {
        //Prepare statement
        $sql = "UPDATE competition_year
                    SET Theme = ?, Admin_Username = ?
                        WHERE Year = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "ssi", $query_theme, $query_admin, $query_year);
            $query_year = $year;
            $query_admin = $_SESSION['username'];
            $query_theme = $theme;
            //Execute statement
            if (mysqli_stmt_execute($stmt)) {
                // delete old keymasters
                $sql = "DELETE
                            FROM year_keymaster
                                WHERE Comp_Date = ?";
                        if ($stmt = mysqli_prepare($link, $sql)) {
                            mysqli_stmt_bind_param($stmt, "i", $query_year);
                            $query_year = $year;
                            mysqli_stmt_execute($stmt);
                        }
                        // add new keymasters
                $sql = "INSERT INTO year_keymaster
                            (Comp_Date, Keymaster_Name) VALUES (?, ?)";
                            if ($stmt = mysqli_prepare($link, $sql)) {
                                mysqli_stmt_bind_param($stmt, "is", $query_year, $query_name);
                    for ($i = 0; $i < $_POST["number_km"]; $i++) {
                                    $query_year = $year;
                                    $kmid = "km" . $i;
                                    $query_name = $_POST[$kmid];
                                    //Execute statement
                                    if (mysqli_stmt_execute($stmt)) {
                                    }
                                }
                }
                if (empty($year_err))
                header("Location: yearselect.php");
            } else {
                $year_err = "the selected year already exists.";
            }
        }
        //Close statement
        mysqli_stmt_close($stmt);
    }
    //Close connection
    mysqli_close($link);
}
?>

<!--Start of HTML -->
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>
            KeyClue Edit Year
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
            KeyClue Archive &middot; Edit Year <?php echo $_GET['ID'];?>
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
                <a href="yearselect.php" style="text-decoration:none">Competition Years</a> &rsaquo;
                <a href="edityear.php?ID=<?php echo $_GET['ID'];?>" style="text-decoration:none">Edit Year <?php echo $_GET['ID'];?></a>
            </div>
                <div class="wrapper">
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                                <div class="inputGroup">
                                    <label>Year: <?php echo $year?></label>
                                    <input type="hidden" name="year" class="form-control" value="<?php echo $year; ?>">
                                </div>
                                <div class="inputGroup">
                                    <label>Theme</label>
                                    <input type="text" name="theme" class="form-control" value="<?php echo $theme; ?>">
                                </div>
                                <div class="inputGroup">
                                    <label>Number of New Keymasters</label>
                                    <input type="number" id="number_km" name="number_km" class="form-control" value="<?php echo $numkm; ?>">
                                    <a href="javascript:addFields();" class="updatekmbutton" style='text-decoration:none; color:#FFF'>Update Number</a>
                                </div>
                                <div id="km_fields">
                                </div>
                                <div class="inputGroup">
                                    <button type="submit" id="submit">Submit</button>
                                </div>
                            </form>
                            <script type="text/javascript" src="addyear.js"></script>
            </div>
        </div>
    </body>
</html>
