<!--Start of PHP -->
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

//Define login parameters
$year = $_GET['year'];
$title = "";
$date = "";
$descr = "";
$username = $_SESSION['username'];
$author = "";
$dsource = "";
$ploc = "";
$timelimit = "";

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
    $author = trim($_POST["author"]);

    //Validate login credentials
        //Prepare statement
        $sql = "INSERT INTO clue
                    (Title, Date, Time_Sensitivity, State, Author, Description, Acc_Username, Comp_Date) VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "sssisssi", $query_title, $query_date, $query_time, $query_state, $query_author, $query_descr, $query_username, $query_comp_date);
            $query_title = $title;
            $query_date = $date;
            $query_descr = $descr;
            $query_state = 0;
            $query_author = $author;
            $query_comp_date = $year;
            $query_username = $username;
            if (empty(trim($_POST["timelimit"]))) {
                $query_time = null;
            } else {
                $query_time = trim($_POST["timelimit"]);
            }

            //Execute statement
            if (mysqli_stmt_execute($stmt)) {
                $last_id = mysqli_insert_id($link);
                if (!empty(trim($_POST["ploc"]))) {
                $sql = "INSERT INTO physical
                            (Clue_ID, Storage) VALUES (?, ?)";
                if ($stmt = mysqli_prepare($link, $sql)) {
                    mysqli_stmt_bind_param($stmt, "is", $query_comp_date, $query_storage);
                    $query_comp_date = $last_id;
                    $query_storage = trim($_POST["ploc"]);
                    //Execute statement
                    mysqli_stmt_execute($stmt);
            }
        }
        if (!empty(trim($_POST["dsource"]))) {
        $sql = "INSERT INTO digital
                    (Clue_ID, Source) VALUES (?, ?)";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "is", $query_comp_date, $query_source);
            $query_comp_date = $last_id;
            $query_source = trim($_POST["dsource"]);
            //Execute statement
            mysqli_stmt_execute($stmt);
    }
}

// file upload stuff 
if (isset($_FILES["photo"]) && is_uploaded_file($_FILES['photo']['tmp_name'])) {
    $upload_base_dir="../files/";
    $upload_time_dir=date('Y')."/".date('m')."/".date('d')."/"; // setup directory name
    $upload_dir = $upload_base_dir.$upload_time_dir;
    if (!file_exists($upload_dir)) {
        mkdir($upload_dir, 0777, true);  //create directory if not exist
    }

    $image_name=basename($_FILES['photo']['name']);
    $image=time().'_'.$image_name;
    move_uploaded_file($_FILES['photo']['tmp_name'],$upload_dir.$image);

    $sql = "INSERT INTO attachment
                (Clue_ID, File_Name, File_Location, Date) VALUES (?, ?, ?, ?)";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "isss", $last_id, $image, $upload_dir, $date);
        //Execute statement
        mysqli_stmt_execute($stmt);
    }
}
    header("Location: ../year/year.php?year=" . $year);
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
            KeyClue Add Clue
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
            KeyClue Archive &middot; Add Clue
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
                <a href="addclue.php?year=<?php echo $_GET['year']?>" style="text-decoration:none">Add Clue</a>
            </div>
                <div class="wrapper">
                            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post" enctype="multipart/form-data">
                                <div class="inputGroup">
                                    <label>Title</label>
                                    <input type="text" name="title" class="form-control" value="<?php echo $title; ?>" required>
                                </div>
                                <div class="inputGroup">
                                    <label>Author</label>
                                    <input type="text" name="author" class="form-control" value="<?php echo $author; ?>" required>
                                </div>
                                <div class="inputGroup">
                                    <label>Digital Source</label>
                                    <input type="text" name="dsource" class="form-control" value="">
                                </div>
                                <div class="inputGroup">
                                    <label>Storage Location</label>
                                    <input type="text" name="ploc" class="form-control" value="">
                                </div>
                                <div class="inputGroup">
                                    <label>Time Limit</label>
                                    <input type="datetime-local" name="timelimit" class="form-control" value="">
                                </div>
                                <div class="inputGroup">
                                    <label>Description</label>
                                    <textarea name="descr" class="form-control" value="<?php echo $descr; ?>"></textarea>
                                </div>
                                <div class="inputGroup">
                                    <label>File</label>
                                    <input type="file" name="photo" id="photo">
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
