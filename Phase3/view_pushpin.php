<?php

include('lib/common.php');

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}


if (!empty($_GET['pushpinID']) && !empty($_GET['userID'])) {

    $pp_id = mysqli_real_escape_string($db, $_GET['pushpinID']);
    $userid = mysqli_real_escape_string($db, $_GET['userID']);
    $_SESSION['pp_id']  = $pp_id;
    $_SESSION['userid']  = $userid;

    $query = "select Pushpin.pushpinID, Corkboard.title, Corkboard.visibility, Pushpin.description, Pushpin.url, Pushpin.updatedtime as FirstPinned, Corkboard.corkboardID, User.email, User.name, User.userID as followUserID, count(Follow.FolloweruserID) as IsFollowing,".
		              "if(exists(select * from likepushpin where likepushpin.PushpinID = '$pp_id' and likepushpin.UserID = '$userid'), 1, 0) as IsLiked ".
		              "from Pushpin ".
		              "inner join Corkboard ON Pushpin.CorkboardID = Corkboard.corkboardID ".
		              "inner join User ON Corkboard.UserID = User.userID ".
		              "left join Follow on Follow.FolloweruserID = '$userid' and Follow.FolloweduserID = Corkboard.userID ".
		              "where Pushpin.pushpinID = '$pp_id' ";

    $result = mysqli_query($db, $query);

    include('lib/show_queries.php');

    if (mysqli_affected_rows($db) == -1) {
        array_push($error_msg, "SELECT ERROR: falied to load database... <br>" . __FILE__ ." line:". __LINE__ );
    } else {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $_SESSION['followUserID'] = $row['followUserID'];
    }
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $comment = mysqli_real_escape_string($db, $_POST['comment']);

    if (empty($comment)) {
        array_push($error_msg, "Error: please add a non-empty comment.");
    } else {
        $comment_query = "insert into Comment (pushpinID, userID, content, date_time)".
            "values ('{$_SESSION['pp_id']}', '{$_SESSION['userid']}', '$comment', NOW())";

        $comment_result = mysqli_query($db, $comment_query);
        include('lib/show_queries.php');
        if (mysqli_affected_rows($db) == -1) {
            array_push($error_msg, "Query ERROR: Failed to insert to comment table...<br>" . __FILE__ . " line:" . __LINE__);
        }
    }

    header(sprintf("Location: view_pushpin.php?pushpinID=%s&userID=%s", $_SESSION['pp_id'], $_SESSION['userid']));
    die();
}

if (!empty($_GET['like']) || !empty($_GET['unlike'])) {

    $query_like = "select distinct l.* ".
                      "from Likepushpin l ".
                      "left join pushpin p using(pushpinID) ".
                      "left join CorkBoard c using(corkboardID) ".
                      "where l.userID = '{$_SESSION['userid']}' and pushpinID = '{$_SESSION['pp_id']}' ";
    $result_like = mysqli_query($db, $query_like);

    if (mysqli_affected_rows($db) == -1) {
        array_push($error_msg,  "ERROR: failed to get likepushpin table ... <br>".  __FILE__ ." line:". __LINE__ );
    }
    $count_like = mysqli_num_rows($result_like);
    if ($count_like > 0) {
        $query_a = "delete from likepushpin where pushpinID = '{$_SESSION['pp_id']}' and userid ='{$_SESSION['userid']}' ";
        $result_a = mysqli_query($db, $query_a);
    } else {
        $query_b = "insert into Likepushpin (pushpinID, userID) values ('{$_SESSION['pp_id']}', '{$_SESSION['userid']}')";
        $result_b = mysqli_query($db, $query_b);
    }

    header(sprintf("Location: view_pushpin.php?pushpinID=%s&userID=%s", $_SESSION['pp_id'], $_SESSION['userid']));
    die();

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
        // $query_c = "delete from Follow where FolloweruserID = '{$_SESSION['userid']}' and FolloweduserID = '{$_SESSION['followUserID']}' ";
        // $result_c = mysqli_query($db, $query_c);
    } else {
        $query_d = "insert into Follow (FolloweruserID, FolloweduserID) values ('{$_SESSION['userid']}', '{$_SESSION['followUserID']}')";
        $result_d = mysqli_query($db, $query_d);
    }

    header(sprintf("Location: view_pushpin.php?pushpinID=%s&userID=%s", $_SESSION['pp_id'], $_SESSION['userid']));
    die();

}

?>

<?php include("lib/header.php"); ?>
<title>View PushPin</title> 
</head>

<body>
<div id="main_container">
    <?php include("lib/menu.php"); ?>

    <div class="center_content">
        <div class="center_left">
            <table>
                <tr>
                    <td>
                        <div class="title_name">
                            <?php print $row['name'] ; ?>
                        </div>
                    </td>
<!--                    add Follow-->
                    <td>
                        <?php
                        if ($row['email'] != $_SESSION['email']) {
                            if ($row['IsFollowing']) {
                                print '<td><a href="view_pushpin.php?unfollow=unfollow">Followed</a></td>';
                            } else {
                                print '<td><a href="view_pushpin.php?follow=follow">Follow</a></td>';
                            }
                        }
                        ?>
                    </td>
                </tr>
            </table>

            <div class="features">
                <div class="profile_section">
                    <table>
                        <tr>
                            <td><?php print 'Pinned ' .$row['FirstPinned'] .' on Corkboard'?>

                                <?php
                                    print '<td><a href="view_corkboard.php?corkboardID=' . $row['corkboardID'] . '&userID=' .$_SESSION['userid']. ' ">' .$row['title'] .'</a></td>';
                                ?>
                            </td>
                        </tr>
                    </table>
                </div>
            </div>

<!--            image short site like, image and image link to original site-->
            <div class="features">
                <div class="profile_section">
                    <table>
                        <?php
                        $pos = strpos($row['url'], '://');
                        $string2 = substr($row['url'], $pos + 3);
                        $pos2 = strpos($string2, '/');
                        $string3 = substr($string2, 0, $pos2);
                        print '<tr><ul align="right"><b>from ' .$string3 .'</b></ul></tr>';
                        ?>

                        <tr>
                            <?php
                            print '<a class="thumbnail" href=" ' .$row['url']  .' ">';
                            print '<img src="' .$row['url'] .'"' .' alt="' .$row['description'] .'"' .'width="220px"' .'/></a>';
                            ?>
                        </tr>
                    </table>
                </div>
            </div>

<!--            description and tags-->
            <div class="features">
                <div class="profile_section">
                    <table>
                        <tr>
                            <?php print '<td>' .$row['description'] .'</td>' ?>
                        </tr>
                        <tr>
                            <td class="item_label">Tags:</td>
                            <?php
                            $query_tags = "SELECT pushpinTag ".
                                "FROM Tags ".
                                "WHERE Tags.pushpinID = '{$_SESSION['pp_id']}' ".
                                "ORDER BY pushpinTag ASC";
                            $result_tags = mysqli_query($db, $query_tags);
                            $row_tags = mysqli_fetch_array($result_tags, MYSQLI_ASSOC);
                            $tags = "";
                            while ($row_tags) {
                                $tmp = $row_tags['pushpinTag'];
                                $tags = $tags .$tmp .',';
                                $row_tags = mysqli_fetch_array($result_tags, MYSQLI_ASSOC);
                            }
                            if (empty($tags)) {
                                print '<td> No tags!</td>';
                            } else {
                                $tags = substr($tags, 0, -1);
                                print '<td>' .$tags .'</td>';
                            }
                            ?>
                        </tr>
                    </table>
                </div>
            </div>

<!--            Like-->
            <div class="features">
                <div class="profile_section">
                    <table>
                        <tr>
                            <?php
                            $query_like =  "select u.name ".
                                             "from likepushpin l ".
                                             "inner join user u using(userID) ".
		                                     "where l.pushpinID = '{$_SESSION['pp_id']}' and u.userID != '{$_SESSION['userid']}' ";

                            $result_like = mysqli_query($db, $query_like);
                            if (mysqli_affected_rows($db) == -1) {
                                array_push($error_msg, "SELECT ERROR: falied to load likepushpin... <br>" . __FILE__ ." line:". __LINE__ );
                            } else {
                                $like = mysqli_fetch_array($result_like, MYSQLI_ASSOC);
                            }
                            $likes = "";
                            if ($row['IsLiked']) {
                                $tmp_query = "select name from User where User.userID = '{$_SESSION['userid']}' ";
                                $tmp_result = mysqli_query($db, $tmp_query);
                                if (mysqli_affected_rows($db) == -1) {
                                    array_push($error_msg, "SELECT ERROR: falied to load User... <br>" . __FILE__ ." line:". __LINE__ );
                                }
                                $tmp_user = mysqli_fetch_array($tmp_result, MYSQLI_ASSOC);

                                $likes = $likes . $tmp_user['name']. ',';
                            }
                            while ($like) {
                                $likes = $likes . $like['name'] . ',';
                                $like = mysqli_fetch_array($result_like, MYSQLI_ASSOC);
                            }
                            if (empty($likes)) {
                                print '<td>No Likes yet!</td>';
                            } else {
                                $likes = substr($likes, 0, -1);
                                print '<td>Liked by: ' .$likes .'</td>';
                            }
                            ?>
                        </tr>

<!--                        like or unlike-->
                        <tr>
                            <?php
                            if ($row['email'] != $_SESSION['email']) {
                                if ($row['IsLiked']) {
                                    print '<td><a href="view_pushpin.php?unlike=unlike">Unlike</a></td>';
                                } else {
                                    print '<td><a href="view_pushpin.php?like=like">Like</a></td>';
                                }
                            }
                            ?>
                        </tr>
                    </table>
                </div>
            </div>

<!--            comment-->
            <div class="features">
                <div class="profile_section">
                    <form name="requestform" action="view_pushpin.php" method="POST">
                        <table>
                            <tr>
                                <td><input type="text" name="comment" /></td>
                            </tr>
                        </table>
                        <td><a href="javascript:requestform.submit();">Post Comment</a></td>
                    </form>
                </div>
            </div>

            <div class="features">
                <div class="profile_section">
                    <table>
                        <tr>
                            <?php
                            $query_comment = " SELECT content, user.name, date_time ".
                                "FROM Comment ".
                                "INNER JOIN User ".
                                "ON Comment.userID = User.userID ".
                                "WHERE PushpinID = '{$_SESSION['pp_id']}' ".
                                "ORDER BY date_time DESC";
                            $result_comment = mysqli_query($db, $query_comment);
                            if (mysqli_affected_rows($db) == -1) {
                                array_push($error_msg, "SELECT ERROR: falied to load comments... <br>" . __FILE__ ." line:". __LINE__ );
                            } else {
                                $comment = mysqli_fetch_array($result_comment, MYSQLI_ASSOC);
                                $count = mysqli_num_rows($result_comment);
                            }
                            if ($count > 0) {
                                while ($comment) {
                                    print '<p><strong>' .$comment['name'] .'</strong>: ' .$comment['content'] .'</p>';
                                    $comment = mysqli_fetch_array($result_comment, MYSQLI_ASSOC);
                                }
                            } else {
                                print '<b>No Comments yet!</b>!';
                            }
                            ?>
                        </tr>
                    </table>
                </div>
            </div>



        </div>

        <?php include("lib/error.php"); ?>

        <div class="clear"></div>
    </div>
</body>
</html>