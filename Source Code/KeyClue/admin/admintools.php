<?php
//Session start
session_start();

$username_err = "";
$password_err = "";

//Checks if session is not logged in
if(!isset($_SESSION['username']) || empty($_SESSION['username'])){
  header("Location: ..\loginpage.php");
  exit;
}

if(!isset($_SESSION['permissions']) || strcmp($_SESSION['permissions'], "admin") != 0){
  header("Location: yearselect.php");
  exit;
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    //Connect to database
    require_once '..\tools\config.php';

    $password_err = "";
    $username_err = "";

    if(empty(trim($_POST['password']))){
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST['password'])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else {
        $password = trim($_POST['password']);
    }

    $sql = "SELECT Username FROM account WHERE Username = ?";

        if($stmt = mysqli_prepare($link, $sql)){
            // Bind variables to the prepared statement as parameters
            mysqli_stmt_bind_param($stmt, "s", $param_username);

            // Set parameters
            $param_username = trim($_POST["username"]);

            // Attempt to execute the prepared statement
            if(mysqli_stmt_execute($stmt)){
                /* store result */
                mysqli_stmt_store_result($stmt);

                if(mysqli_stmt_num_rows($stmt) == 1){
                    $username_err = "This username is already taken.";
                } else{
                    $username = trim($_POST["username"]);
                }
            } else{
                echo "Oops! Something went wrong. Please try again later.";
            }
        }

    if(empty(trim($_POST['username']))){
        $username_err = "Please enter a username.";
    } else {
        $username = trim($_POST['username']);
    }
// add new account
    if(empty($password_err) && empty($username_err)) {
        $sql = "INSERT INTO account
                    (Username, Password, Name, Email) VALUES (?, ?, ?, ?)";
            if($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "ssss", $param_username, $param_password, $param_name, $param_email);
                $param_username = $username;
                $param_password = password_hash($password, PASSWORD_DEFAULT);
                $param_email = trim($_POST["email"]);
                $param_name = trim($_POST["name"]);
                if(mysqli_stmt_execute($stmt)){
                }
            }
// add admin account previledges
            if ($_POST["isAdmin"] == "admin") {
                $sql = "INSERT INTO admin
                            (Account_Username, Permissions) VALUES (?, ?)";
                    if($stmt = mysqli_prepare($link, $sql)) {
                        mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_perm);
                        $param_username = $username;
                        $param_perm = "admin";
                        if(mysqli_stmt_execute($stmt)){
                            header("Location: admintools.php");
                        }
            }
            // add user expiry
        } else {
            $sql = "INSERT INTO user
                        (Account_Username, Expiry) VALUES (?, ?)";
                if($stmt = mysqli_prepare($link, $sql)) {
                    mysqli_stmt_bind_param($stmt, "ss", $param_username, $param_exp);
                    $param_username = $username;
                    $date = date('Y-m-d', strtotime(date("Y-m-d", mktime()) . " + 365 day"));
                    $param_exp = $date;
                    if(mysqli_stmt_execute($stmt)){
                        header("Location: admintools.php");
                    }
                }
        }
    }

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
        <link rel="stylesheet" type="text/css" href="account.css">
        <link rel="stylesheet" type="text/css" href="addyear.css">
        <link rel="icon" href="..\images\zoo.png">
    </head>
    <body>
        <div class="header">
            KeyClue Archive &middot; Admin Tools
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
            </div>
            <div class="wrapper">
                        <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                            <div class="inputGroup">
                                <label>Username</label>
                                <input type="text" name="username" class="form-control" value="" required>
                                <span class="help-block"> <?php echo $username_err; ?> </span>
                            </div>
                            <div class="inputGroup">
                                <label>Password</label>
                                <input type="password" name="password" class="form-control" value="" required>
                                <span class="help-block"> <?php echo $password_err; ?> </span>
                            </div>
                            <div class="inputGroup">
                                <label>Name</label>
                                <input type="text" name="name" class="form-control" value="" required>
                            </div>
                            <div class="inputGroup">
                                <label>Email</label>
                                <input type="email" name="email" class="form-control" value="" required>
                            </div>
                            <div class="inputGroup">
                                <label>Admin Account<input type="checkbox" name="isAdmin" class="checker" value="admin"></label>
                            </div>
                            <div class="inputGroup">
                                <button type="submit" id="submit">Create Account</button>
                            </div>
                        </form>
                        <br><br>
        </div>
        </div>
    </body>
</html>
