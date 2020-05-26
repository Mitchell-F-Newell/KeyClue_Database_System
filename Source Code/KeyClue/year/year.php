<!--Start of PHP -->
<?php
    //Session start
    session_start();

    //Checks if session is not logged in
    if (!isset($_SESSION['username']) || empty($_SESSION['username'])) {
      header("Location: ..\loginpage.php");
      exit;
    }

    if (!isset($_GET['year']) || empty($_GET['year'])) {
        header("Location: yearselect.php");
        exit;
    }

    // create connection
    require_once '..\tools\config.php';
    // sql statement
    $sql = "SELECT Y.Year
                FROM competition_year AS Y
                        WHERE Y.Year = ?";
    if ($stmt = mysqli_prepare($link, $sql)) {
        mysqli_stmt_bind_param($stmt, "i", $query_year);
        $query_year = $_GET['year'];
        if (mysqli_stmt_execute($stmt)) {
            mysqli_stmt_store_result($stmt);
            if (mysqli_stmt_num_rows($stmt) != 1) {
                // redirect
                header("Location: yearselect.php");
                exit;
            }
        }
        else {
            echo "Something has gone wrong with the internal database connection. Please try again later.";
        }
    }
?>

<!--Start of HTML -->
<!DOCTYPE html>
<html lang="en">
    <head>
        <title>
            KeyClue <?php echo $_GET['year'];?>
        </title>
        <meta charset="UTF-8">
        <meta name="description" content="ZOO's KeyClue Archive. Access to this site is restricted to members of the ZOO KeyClue team. Contact a KeyClue administrator for access">
        <meta name="copyright" content="Copyright 2018 Jared Brintnell, Mitchell Newell, and Davis Roman">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <link href="https://fonts.googleapis.com/css?family=Josefin+Sans" rel="stylesheet">
        <link href="https://fonts.googleapis.com/css?family=Fira+Sans" rel="stylesheet">
        <link rel="stylesheet" type="text/css" href="clue.css">
        <link rel="icon" href="..\images\zoo.png">
    </head>
    <body>
        <div class="header">
            KeyClue Archive &middot; <?php echo $_GET['year'];?>
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
                <?php echo "<a href='year.php?year=" . $_GET['year'] . "' style='text-decoration:none'>" . $_GET['year'] . "</a>";?>
            </div>
            <div class="addbuttons">
                <?php if(isset($_SESSION['permissions']) && strcmp($_SESSION['permissions'], "admin") == 0) {
                    echo '<a href="../discussion/adddiscussion.php?year=' . $_GET['year'] . '" style="text-decoration:none; color:#FFF">
                            <div class="adders">Add Discussion</div>
                          </a>';
                }?>
                    <?php echo '<a href="../clue/addclue.php?year=' . $_GET['year'] . '" style="text-decoration:none; color:#FFF">
                        <div class="adders">Add Clue</div>
                    </a>';?>
            </div>
            <!--PUT BUTTON TO ADD CLUE HERE-->

            <?php
            // Select all time sensitive clues and display them
                require_once '..\tools\config.php';
                $sql = "SELECT C.ID, C.Date, C.Time_Sensitivity, C.Title, C.State, C.Author, C.Acc_Username, P.Storage, D.Source
                            FROM clue AS C LEFT JOIN physical AS P
                                ON (C.ID = P.Clue_ID) LEFT JOIN digital AS D
                                    ON (C.ID = D.Clue_ID)
                                        WHERE C.Comp_Date = ?
                                        AND C.Time_Sensitivity IS NOT NULL
                                        AND C.State = 0
                                            ORDER BY C.ID";
                if ($stmt = mysqli_prepare($link, $sql)) {
                    mysqli_stmt_bind_param($stmt, "i", $query_year);
                    $query_year = $_GET['year'];
                    if (mysqli_stmt_execute($stmt)) {
                        mysqli_stmt_store_result($stmt);
                        if (mysqli_stmt_num_rows($stmt) > 0) {
                            echo '<div class="tablearea">
                                <div class="tablelabel">
                                    Time Sensitive Clues
                                </div>
                                <div class="tableformatting">
                                    <table id="table1">
                                        <thead>
                                            <tr>
                                                <th class="min">Clue ID<a href="javascript:sortTable(0, 1);"><img src="..\images\updown.png" class="actionicons"></img></a></th>
                                                <th>Title<a href="javascript:sortTable(1, 1);"><img src="..\images\updown.png" class="actionicons"></img></a></th>
                                                <th>Author<a href="javascript:sortTable(2, 1);"><img src="..\images\updown.png" class="actionicons"></img></a></th>
                                                <th>Date<a href="javascript:sortTable(3, 1);"><img src="..\images\updown.png" class="actionicons"></img></a></th>
                                                <th>Status<a href="javascript:sortTable(4, 1);"><img src="..\images\updown.png" class="actionicons"></img></a></th>
                                                <th class="min">Time Remaining<a href="javascript:sortTable(5, 1);"><img src="..\images\updown.png" class="actionicons"></img></a></th>
                                                <th class="min">Created By<a href="javascript:sortTable(6, 1);"><img src="..\images\updown.png" class="actionicons"></img></a></th>
                                                <th>Type<a href="javascript:sortTable(7, 1);"><img src="..\images\updown.png" class="actionicons"></img></a></th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>';
                                        mysqli_stmt_bind_result($stmt, $ID, $date, $sens, $title, $state, $author, $acc, $storage, $source);
                                        for ($i = 0; mysqli_stmt_fetch($stmt); $i++) {
                                            echo '<tr>
                                                <td>' . $ID . '</td>
                                                <td>' . $title . '</td>
                                                <td class="min">' . $author . '</td>
                                                <td class="min">' . $date . '</td>
                                                <td class="min" style="color:#BBBB00">TIMED <img src="..\images\timed.png" class="allicons"></img></td>
                                                <td class="timeclass" id="time-' . $i . '">' . $sens . '</td>
                                                <td class="min">' . $acc . '</td>';
                                                    if ($storage != null) {
                                                        if ($source != null) {
                                                            echo '<td class="min">physical/digital</td>';
                                                        } else {
                                                            echo '<td class="min">physical</td>';
                                                        }
                                                    } else if ($source != null) {
                                                        echo '<td class="min">digital</td>';
                                                    } else {
                                                        echo '<td class="min">unknown</td>';
                                                    }

                                                echo '<td class="min">
                                                    <a href="../clue/readclue.php?clue_ID='. $ID .'&year=' . $_GET['year'] . '" title="View Clue"><img src="..\images\view.png" class="actionicons"></img></a>&nbsp;&nbsp;
                                                    <a href="../clue/editclue.php?clue_ID='. $ID .'&year=' . $_GET['year'] . '" title="Edit Clue"><img src="..\images\edit.png" class="actionicons"></img></a>&nbsp;&nbsp;
                                                    <a href="deleteclue.php?clue_ID='. $ID .'&year=' . $_GET['year'] . '" title="Delete Clue"><img src="..\images\delete.png" class="actionicons"></img></a>
                                                </td>
                                            </tr>';
                                        }
                                        echo '</tbody>
                                    </table>
                                </div>
                            </div>';
                                    }
                                } else {
                                    echo "Something has gone wrong with the internal database connection. Please try again later.";
                                }
                            }
            ?>
            <?php
            // select all non time sensitive clues and display them
                require_once '..\tools\config.php';
                $sql = "SELECT C.ID, C.Date, C.Title, C.State, C.Author, C.Acc_Username, P.Storage, D.Source
                            FROM clue AS C LEFT JOIN physical AS P
                                ON (C.ID = P.Clue_ID) LEFT JOIN digital AS D
                                    ON (C.ID = D.Clue_ID)
                                        WHERE C.Comp_Date = ?
                                        AND (C.Time_Sensitivity IS NULL
                                            OR C.State = 1)
                                                ORDER BY C.ID";
                if ($stmt = mysqli_prepare($link, $sql)) {
                    mysqli_stmt_bind_param($stmt, "i", $query_year);
                    $query_year = $_GET['year'];
                    if (mysqli_stmt_execute($stmt)) {
                        mysqli_stmt_store_result($stmt);
                        if (mysqli_stmt_num_rows($stmt) > 0) {
                            echo '<div class="tablearea">
                                <div class="tablelabel">
                                    Clues
                                </div>
                                <div class="tableformatting">
                                    <table id="table2">
                                        <thead>
                                            <tr>
                                                <th class="min">Clue ID<a href="javascript:sortTable(0, 2);"><img src="..\images\updown.png" class="actionicons"></img></a></th>
                                                <th>Title<a href="javascript:sortTable(1, 2);"><img src="..\images\updown.png" class="actionicons"></img></a></th>
                                                <th>Author<a href="javascript:sortTable(2, 2);"><img src="..\images\updown.png" class="actionicons"></img></a></th>
                                                <th>Date<a href="javascript:sortTable(3, 2);"><img src="..\images\updown.png" class="actionicons"></img></a></th>
                                                <th>Status<a href="javascript:sortTable(4, 2);"><img src="..\images\updown.png" class="actionicons"></img></a></th>
                                                <th class="min">Created By<a href="javascript:sortTable(5, 2);"><img src="..\images\updown.png" class="actionicons"></img></a></th>
                                                <th>Type<a href="javascript:sortTable(6, 2);"><img src="..\images\updown.png" class="actionicons"></img></a></th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>';
                                        mysqli_stmt_bind_result($stmt, $ID, $date, $title, $state, $author, $acc, $storage, $source);
                                        for ($i = 0; mysqli_stmt_fetch($stmt); $i++) {
                                            echo '<tr>
                                                <td>' . $ID . '</td>
                                                <td>' . $title . '</td>
                                                <td class="min">' . $author . '</td>
                                                <td class="min">' . $date . '</td>';
                                                if ($state == 0) {
                                                    echo '<td class="min" style="color:#BB0000"><span>INCOMPLETE <img src="..\images\incomplete.png" class="allicons"></img></span></td>';
                                                } else {
                                                    echo '<td class="min" style="color:#00BB00"><span>COMPLETE <img src="..\images\complete.png" class="allicons"></img></span></td>';
                                                }
                                            echo '<td class="min">' . $acc . '</td>';
                                                if ($storage != null) {
                                                    if ($source != null) {
                                                        echo '<td class="min">physical/digital</td>';
                                                    } else {
                                                        echo '<td class="min">physical</td>';
                                                    }
                                                } else if ($source != null) {
                                                    echo '<td class="min">digital</td>';
                                                } else {
                                                    echo '<td class="min">unknown</td>';
                                                }

                                            echo '<td class="min">
                                            <a href="../clue/readclue.php?clue_ID='. $ID .'&year=' . $_GET['year'] . '" title="View Clue"><img src="..\images\view.png" class="actionicons"></img></a>&nbsp;&nbsp;
                                            <a href="../clue/editclue.php?clue_ID='. $ID .'&year=' . $_GET['year'] . '" title="Edit Clue"><img src="..\images\edit.png" class="actionicons"></img></a>&nbsp;&nbsp;
                                            <a href="deleteclue.php?clue_ID='. $ID .'&year=' . $_GET['year'] . '" title="Delete Clue"><img src="..\images\delete.png" class="actionicons"></img></a>
                                            </td>
                                            </tr>';
                                        }
                                        echo '</tbody>
                                    </table>
                                </div>
                            </div>';
                                    }
                                } else {
                                    echo "Something has gone wrong with the internal database connection. Please try again later.";
                                }
                            }
            ?>
            <?php
            // select all discussions and display them
                require_once '..\tools\config.php';
                $sql = "SELECT D.ID, D.Date, D.Title, D.Admin_Username
                            FROM discussion AS D
                                WHERE D.Competition_Date = ?
                                    ORDER BY D.ID";
                if ($stmt = mysqli_prepare($link, $sql)) {
                    mysqli_stmt_bind_param($stmt, "i", $query_year);
                    $query_year = $_GET['year'];
                    if (mysqli_stmt_execute($stmt)) {
                        mysqli_stmt_store_result($stmt);
                        if (mysqli_stmt_num_rows($stmt) > 0) {
                            echo '<div class="tablearea">
                                <div class="tablelabel">
                                    Discussions
                                </div>
                                <div class="tableformatting">
                                    <table id="table3">
                                        <thead>
                                            <tr>
                                                <th class="min">Disc ID<a href="javascript:sortTable(0, 3);"><img src="..\images\updown.png" class="actionicons"></img></a></th>
                                                <th>Title<a href="javascript:sortTable(1, 3);"><img src="..\images\updown.png" class="actionicons"></img></a></th>
                                                <th>Date<a href="javascript:sortTable(2, 3);"><img src="..\images\updown.png" class="actionicons"></img></a></th>
                                                <th class="min">Created By<a href="javascript:sortTable(3, 3);"><img src="..\images\updown.png" class="actionicons"></img></a></th>
                                                <th>Action</th>
                                            </tr>
                                        </thead>
                                        <tbody>';
                                        mysqli_stmt_bind_result($stmt, $ID, $date, $title, $acc);
                                        for ($i = 0; mysqli_stmt_fetch($stmt); $i++) {
                                            echo '<tr>
                                                <td>' . $ID . '</td>
                                                <td>' . $title . '</td>
                                                <td class="min">' . $date . '</td>
                                                <td class="min">' . $acc . '</td>
                                                <td class="min">
                                                <a href="../discussion/readdiscussion.php?ID='. $ID .'&year=' . $_GET['year'] . '" title="View Discussion"><img src="..\images\view.png" class="actionicons"></img></a>&nbsp;&nbsp;
                                                <a href="../discussion/editdiscussion.php?ID='. $ID .'&year=' . $_GET['year'] . '" title="Edit Discussion"><img src="..\images\edit.png" class="actionicons"></img></a>&nbsp;&nbsp;
                                                <a href="deletediscussion.php?ID='. $ID .'&year=' . $_GET['year'] . '" title="Delete Discussion"><img src="..\images\delete.png" class="actionicons"></img></a>
                                                </td>
                                            </tr>';
                                        }
                                        echo '</tbody>
                                    </table>
                                </div>
                            </div>';
                                    }
                                } else {
                                    echo "Something has gone wrong with the internal database connection. Please try again later.";
                                }
                            }
                            mysqli_close($link);
            ?>
            <script type="text/javascript" src="clue.js"></script>
        </div>
    </body>
</html>
