<?php

include('lib/common.php');

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

if (!empty($_GET['corkboardID']) && !empty(['userID'])) {

    $cb_id = mysqli_real_escape_string($db, $_GET['corkboardID']);
    $userid = mysqli_real_escape_string($db, $_GET['userID']);
//    Global vaiable whch can be passed to view_pushpin.php
    $_SESSION['cb_id'] = $cb_id;
    $_SESSION['userid'] = $userid;

    $query = "SELECT Corkboard.corkboardID, Corkboard.title, CorkBoard.categoryID, Corkboard.LastUpdates, Category.category_type, Privatecorkboard.password, User.name, User.UserID as followUserID, User.email, Corkboard.visibility, MAX(pushpin.updatedtime) AS LastUpdate, COUNT(DISTINCT(Watchcorkboard.userID)) AS Watchers, COUNT(Follow.FolloweruserID) AS IsFollowing, ".
                    "IF(EXISTS(SELECT * ".
                        "FROM watchcorkboard ".
                        "WHERE watchedID = '$cb_id' ".
                        "AND userID ='$userid'), 1, 0) AS IsWatching ".
                    "FROM CorkBoard ".
                    "INNER JOIN User ".
                    "ON Corkboard.userID = user.userID ".
                    "inner join Category ".
                    "on category.categoryID = Corkboard.categoryID ".
                    "LEFT JOIN Watchcorkboard ".
                    "ON CorkBoard.corkboardID = WatchcorkBoard.watchedID ".
                    "LEFT JOIN Privatecorkboard ".
                    "USING (corkboardID) ".
                    "LEFT JOIN Follow ".
                    "ON Follow.FolloweruserID = '$userid' AND Follow.FolloweduserID = CorkBoard.userID ".
                    "LEFT JOIN Pushpin ".
                    "ON CorkBoard.corkboardID = Pushpin.CorkboardID ".
                    "WHERE CorkBoard.corkboardID = '$cb_id' ".
                    "Group by 1, 2, 3, 4, 5, 6, 7, 8";

    $result = mysqli_query($db, $query);

    include('lib/show_queries.php');

    if (mysqli_affected_rows($db) == -1) {
        array_push($error_msg, "DELETE ERROR: reject request... <br>" . __FILE__ ." line:". __LINE__ );
    } else {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $_SESSION['followUserID'] = $row['followUserID'];
    }
}

if (!empty($_GET['follow']) || !empty($_GET['unfollow'])) {

    $query_follow = "SELECT * ".
        "FROM Follow ".
        "WHERE FolloweruserID =  '{$_SESSION['userid']}' ".
        "AND FolloweduserID = '{$_SESSION['followUserID']}' ";
    $result_follow = mysqli_query($db, $query_follow);

    if (mysqli_affected_rows($db) == -1) {
        array_push($error_msg,  "ERROR: failed to get Follow table ... <br>".  __FILE__ ." line:". __LINE__ );
    }
    $count_follow = mysqli_num_rows($result_follow);
    if ($count_follow > 0) {
        $query_c = "delete from Follow where FolloweruserID = '{$_SESSION['userid']}' and FolloweduserID = '{$_SESSION['followUserID']}' ";
        $result_c = mysqli_query($db, $query_c);
    } else {
        $query_d = "insert into Follow (FolloweruserID, FolloweduserID) values ('{$_SESSION['userid']}', '{$_SESSION['followUserID']}')";
        $result_d = mysqli_query($db, $query_d);
    }

    header(sprintf("Location: view_corkboard.php?corkboardID=%s&userID=%s", $_SESSION['cb_id'], $_SESSION['userid']));
    die();

}

if (!empty($_GET['watch']) || !empty($_GET['unwatch'])) {

    $query_watch = "SELECT * ".
        "FROM Watchcorkboard ".
        "WHERE userID =  '{$_SESSION['userid']}' ".
        "AND watchedID = '{$_SESSION['cb_id']}' ";
    $result_watch = mysqli_query($db, $query_watch);

    array_push($error_msg, $_SESSION['cb_id']);
    array_push($error_msg, $_SESSION['userid']);

    if (mysqli_affected_rows($db) == -1) {
        array_push($error_msg,  "ERROR: failed to get watch Watchcorkboard ... <br>".  __FILE__ ." line:". __LINE__ );
    }
    $count_watch= mysqli_num_rows($result_watch);
    array_push($error_msg,  'count: '.$count_watch);
    if ($count_watch > 0) {
        $query_a = "delete from Watchcorkboard where userID = '{$_SESSION['userid']}' and watchedID = '{$_SESSION['cb_id']}' ";
        $result_a = mysqli_query($db, $query_a);
    } else {
        $query_b = "insert into Watchcorkboard (userID, watchedID) values ('{$_SESSION['userid']}', '{$_SESSION['cb_id']}')";
        $result_b = mysqli_query($db, $query_b);

        include('lib/show_queries.php');

        if ($result_b  == False) {  //INSERT, UPDATE, DELETE, DROP return True on Success  / False on Error
            array_push($error_msg, "INSERT ERROR: Watchcorkboard: " . __FILE__ ." line:". __LINE__ );
        }
    }

    header(sprintf("Location: view_corkboard.php?corkboardID=%s&userID=%s", $_SESSION['cb_id'], $_SESSION['userid']));
    die();

}

?>

<?php include("lib/header.php"); ?>
<title>View CorkBoard</title>
</head>

<body>
<div id="main_container">
    <?php include("lib/menu.php"); ?>

    <div class="center_content">
        <div class="center_left">
            <table>
                <tr>
                    <td><div class="title_name">
                            <?php print $row['name'] ; ?>
                        </div>
                    </td>

<!--                    follow-->
                    <td>
                        <?php
                        if ($row['email'] != $_SESSION['email']) {
                            if ($row['IsFollowing']) {
                                print '<td><a href="view_corkboard.php?unfollow=unfollow">Unfollow</a></td>';
                            } else {
                                print '<td><a href="view_corkboard.php?follow=follow">Follow</a></td>';
                            }
                        }
                        ?>
                    </td>

                    <td><div class="item_label">
                            <?php print $row['category_type'] ; ?>
                        </div>
                    </td>
                </tr>
            </table>

            <div class="features">
                <div class="profile_section">
                    <table>
                        <tr>
                            <td class="item_label">
                                <?php print $row['title'] ; ?>
                            </td>
                        </tr>
                        <tr>
                            <td class="item_large_label">
                                <?php print 'Last Updated ' .$row['LastUpdates'] ?>
                            </td>
                            <td>
                                <?php
                                if ( $row['email'] == $_SESSION['email']) {
                                    // $_SESSION['userid'] can also be passed in as userID, then we can remove fetching it from DB in add_pushpin.php
                                    print '<td><a href="add_pushpin.php?corkboardID=' . $row['corkboardID'] .' ">' .'Add PushPin' .'</a></td>';
                                }
                                ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

            <div>
                <div class="profile_section">
                    <?php
                        $query = "SELECT * ".
                            "FROM Pushpin ".
                            "WHERE corkboardID = '$cb_id'";
                        $result = mysqli_query($db, $query);
                        if (mysqli_affected_rows($db) == -1) {
                            array_push($error_msg, "ERROR: fetching pushpin table... <br>" . __FILE__ ." line:". __LINE__ );
                        } else {
                            $pushpin_row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                            $count = mysqli_num_rows($result);
                        }

                        if ($count > 0) {
                            while ($pushpin_row) {
                                print '<a class="thumbnail" href="view_pushpin.php?pushpinID=' .$pushpin_row['pushpinID'] .'&userID=' .$_SESSION['userid']  .' ">';
                                print '<img src="' .$pushpin_row['url'] .'"' .' alt="' .$pushpin_row['description'] .'"' .'width="220px"' .'/></a>';
                                $pushpin_row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                            }
                        } else {
                            print '<b>' .$row['name'] .'</b>' .' hasn\'t added any PushPin to this CorkBoard yet!';
                        }
                    ?>
                </div>

                <div class="features">
                    <div class="profile_section">
                        <table>
                            <tr>
                                <td class="item_label">
                                    <?php
//                                    only show public corkboard
                                    if ($row['visibility'] == 0) {
                                        print 'This CorkBoard has <b>'.$row['Watchers'] .'</b> Watchers.';
                                    }
                                    ?>
                                </td>
                            </tr>

                            <tr>
                                <td>
                                    <?php
                                    if ($row['email'] != $_SESSION['email']) {
                                        if ($row['IsWatching']) {
                                            print '<td><a href="view_corkboard.php?unwatch=unwatch">Unwatch</a></td>';
                                        } else {
                                            print '<td><a href="view_corkboard.php?watch=watch">Watch</a></td>';
                                        }
                                    }
                                    ?>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>

            </div>

        </div>

        <?php include("lib/error.php"); ?>

        <div class="clear"></div>
    </div>

    <?php include("lib/footer.php"); ?>

</div>
</body>
</html>