<!--Start of PHP -->
<?php
//Connect to database
require_once 'tools\config.php';

//Define login parameters for refilling feilds
$username = "";
$password = "";
$login_error = "";

//Login processing on post
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    //Password checks on field
    if (empty(trim($_POST["password"]))) {
        $login_error = "please enter your password.";
    } else {
        $password = trim($_POST["password"]);
    }

    //Username checks on field
    if (empty(trim($_POST["username"]))) {
        $login_error = "please enter your username.";
    } else {
        $username = trim($_POST["username"]);
    }

    //Validate login credentials
    if (empty($login_error)) {
        //Prepare statement to check username
        $sql = "SELECT A.Username, A.Password, AD.Permissions, U.Expiry
                    FROM account AS A LEFT JOIN admin AS AD
                        ON (A.Username = AD.Account_Username) LEFT JOIN user AS U
                            ON (A.Username = U.Account_Username)
                                WHERE A.Username = ?";
        if ($stmt = mysqli_prepare($link, $sql)) {
            mysqli_stmt_bind_param($stmt, "s", $query_username);
            $query_username = $username;
            //Execute statement
            if (mysqli_stmt_execute($stmt)) {
                mysqli_stmt_store_result($stmt);
                //Check number of returned results
                if (mysqli_stmt_num_rows($stmt) == 1) {
                    //Bind and fetch results
                    mysqli_stmt_bind_result($stmt, $username, $hash_password, $permissions, $expiry);
                    if (mysqli_stmt_fetch($stmt)) {
                        //Compare passwords for login
                        if (password_verify($password, $hash_password)) {
                            if ($expiry != null && time() - strtotime($expiry) > 0) {
                                $login_error = "account has expired.";
                            } else {
                                //Session start
                                session_start();
                                $_SESSION['username'] = $username;
                                if ($permissions != null) {
                                    $_SESSION['permissions'] = $permissions;
                                }
                                header("Location: year\yearselect.php");
                            }
                        } else {
                            $login_error = "username or password is incorrect.";
                        }
                    }
                } else {
                    $login_error = "username or password is incorrect.";
                }
            } else {
                echo "Something has gone wrong with the internal database connection. Please try again later.";
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
<!DOCTYPE HTML>
<html lang="en">
    <head>
        <title>
            KeyClue Login
        </title>
        <meta charset="UTF-8">
        <meta name="description" content="ZOO's KeyClue Archive. Access to this site is restricted to members of the ZOO KeyClue team. Contact a KeyClue administrator for access">
        <meta name="copyright" content="Copyright 2018 Jared Brintnell, Mitchell Newell, and Davis Roman">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://fonts.googleapis.com/css?family=Josefin+Sans" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="loginpage.css">
        <link rel="icon" href="zoo.png">
    </head>
    <body>
        <div class="wrapper">
            <!-- Post Login Info-->
            <form action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]); ?>" method="post">
                <div class="inputGroup">
                    <label>Username</label>
                    <input type="text" id="username" name="username" class="form-control" maxlength="256" value="<?php echo $username; ?>">
                </div>
                <div class="inputGroup">
                    <label>Password</label>
                    <input type="password" id="password" name="password" class="form-control">
                    <span class="help-block"> <?php echo $login_error; ?> </span>
                </div>
                <div class="inputGroup">
                    <button type="submit" id="login">Login</button>
                </div>
                <p><a href="mailto:keyclue@zooengg.ca">Need an account?</a></p>
            </form>
        </div>
    </body>
</html>
