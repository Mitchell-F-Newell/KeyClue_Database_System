<?php
//Session start
session_start();

$password_err = "";

//Checks if session is not logged in
if(!isset($_SESSION['username']) || empty($_SESSION['username'])){
  header("Location: ..\loginpage.php");
  exit;
}

if($_SERVER["REQUEST_METHOD"] == "POST"){
    //Connect to database
    require_once '..\tools\config.php';

    if(empty(trim($_POST['password']))){
        $password_err = "Please enter a password.";
    } elseif (strlen(trim($_POST['password'])) < 6){
        $password_err = "Password must have atleast 6 characters.";
    } else {
        $password = trim($_POST['password']);
    }
    // change password
    if(empty($password_err)) {
        $sql = "UPDATE account
                    SET Password = ?
                        WHERE Username = ?";
            if($stmt = mysqli_prepare($link, $sql)) {
                mysqli_stmt_bind_param($stmt, "ss", $param_password, $param_username);
                $param_username = $_SESSION['username'];
                $param_password = password_hash($password, PASSWORD_DEFAULT);
                if(mysqli_stmt_execute($stmt)){
                    header("Location: manageaccount.php");
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
            KeyClue Archive &middot; Manage Account
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
                                <label>Change Password</label>
                                <input type="password" name="password" class="form-control" value="">
                                <span class="help-block"> <?php echo $password_err; ?> </span>
                            </div>
                            <div class="inputGroup">
                                <button type="submit" id="submit">Change Password</button>
                            </div>
                        </form>
                        <br><br>
                        <div class="inputGroup">
                            <a href="../tools/logout.php" style="text-decoration:none"><button type="logout">Logout</button></a>
                        </div>
        </div>
        </div>
    </body>
</html>
