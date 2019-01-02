<?php

include('lib/common.php');

if (!isset($_SESSION['email'])) {
	header('Location: login.php');
	exit();
}

    $query = "SELECT name " .
		 "FROM User " .
		 "WHERE User.email='{$_SESSION['email']}'";

    $result = mysqli_query($db, $query);
    include('lib/show_queries.php');
 
    if ( !is_bool($result) && (mysqli_num_rows($result) > 0) ) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    } else {
        array_push($error_msg,  "Query ERROR: Failed to get User profile...<br>" . __FILE__ ." line:". __LINE__ );
    }
?>

<?php include("lib/header.php"); ?>
<title>CorkBoardIt Home Page</title>
</head>

<body>
		<div id="main_container">
    <?php include("lib/menu.php"); ?>

    <div class="center_content">
        <div class="center_left">
            <div class="title_name">
                <?php print $row['name'] ; ?>
            </div>          
            <div class="features">   
            
                <div class="profile_section">
                    <table>
                        <tr>
                            <td><div class="subtitle">Recent Corkboard Updates</div></td>
                            <td><a href="popular_tags.php">Popular Tags</a></td>
                            <td><a href="popular_sites.php">Popular Sites</a></td>
                            <td><a href="corkboard_statistics.php">CorkBoard Statistics</a></td>
                        </tr>
                    </table>
                    <?php
                    $query = "SELECT userID FROM User WHERE email ='{$_SESSION['email']}'";
                    $result = mysqli_query($db, $query);
                    include('lib/show_queries.php');
                    if ( !is_bool($result) && (mysqli_num_rows($result) > 0) ) {
                        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    } else {
                        array_push($error_msg,  "Query ERROR: Failed to get user profile...<br>" . __FILE__ ." line:". __LINE__ );
                    }

                    $query = "SELECT distinct Corkboard.corkboardID, Corkboard.title, User.name, Corkboard.userID, Corkboard.visibility, max(ifnull(Pushpin.updatedtime, Corkboard.LastUpdates)) as LastUpdates ".
                        "FROM Corkboard ".
                        "INNER JOIN User ".
                        "on Corkboard.userID = User.userID ".
                        "LEFT JOIN Pushpin ".
                        "ON Corkboard.corkboardID = Pushpin.corkboardID ".
                        "LEFT JOIN Follow ".
                        "ON FolloweduserID = Corkboard.userID ".
                        "LEFT JOIN Watchcorkboard ".
                        "ON Watchcorkboard.watchedID = Corkboard.userID ".
                        "where Corkboard.userID = '{$row['userID']}' ".
                        "or Follow.FolloweruserID ='{$row['userID']}' ".
                        "or WatchcorkBoard.userID = '{$row['userID']}' ".
                        "group by 1,2,3,4,5 ".
                        "ORDER BY 6 DESC ".
                        "LIMIT 4";
                    $result = mysqli_query($db, $query);
                    if ( is_bool($result) && (mysqli_num_rows($result) == 0) ) {
                        array_push($error_msg,  "Query ERROR: Failed to get user profile...<br>" . __FILE__ ." line:". __LINE__ );
                    }

                    $recent_update = mysqli_fetch_array($result, MYSQLI_ASSOC);
                    if ($recent_update) {

                        print '<table>';
                        print '<tr>';
                        print '<td class="heading">CorkBoard</td>';
                        print '<td class="heading">Owner</td>';
                        print '<td class="heading">Last Updated</td>';
                        print '<td class="heading">Status</td>';
                        print '</tr>';

                        while ($recent_update) {
                            print '<tr>';
                            print '<td><a href="view_corkboard.php?corkboardID=' . $recent_update['corkboardID'] . '&userID=' .$row['userID']. ' ">' .$recent_update['title'] .'</a></td>';
                            print '<td>' . $recent_update['name'] . '</td>';
                            print '<td>' . $recent_update['LastUpdates'] . '</td>';
                            if ($recent_update['visibility']) {
                                print '<td class="label label-important">Private</td>';
                            } else {
                                print '<td>Public</td>';
                            }
                            print '</tr>';

                            $recent_update = mysqli_fetch_array($result, MYSQLI_ASSOC);
                        }
                        print '</table>';
                    } else {
                        print "<br/>None!";
                    }
                    ?>

                </div>

                <div class="profile_section">
                    <table>
                        <tr>
                            <td><div class="subtitle">My CorkBoards</div></td>
                            <td><a href="add_corkboard.php">Add CorkBoard</a></</td>
                        </tr>
                        <?php
                        $query = "SELECT Corkboard.corkboardID, Corkboard.title, COUNT(Pushpin.pushpinID) as Pushpins, CorkBoard.visibility ".
                            "FROM Corkboard ".
                            "LEFT JOIN Pushpin ".
                            "ON Corkboard.corkboardID = Pushpin.CorkboardID ".
                            "WHERE CorkBoard.userID = '{$row['userID']}' ".
                            "GROUP BY Corkboard.corkboardID, Corkboard.title ".
                            "ORDER BY CorkBoard.title ASC";
                        $result = mysqli_query($db, $query);
                        if ( is_bool($result) && (mysqli_num_rows($result) == 0) ) {
                            array_push($error_msg,  "Query ERROR: Failed to get user profile...<br>" . __FILE__ ." line:". __LINE__ );
                        }

                        $my_corkboards = mysqli_fetch_array($result, MYSQLI_ASSOC);
                        if ($my_corkboards) {

                            print '<table>';
                            print '<tr>';
                            print '<td class="heading">CorkBoard</td>';
                            print '<td class="heading">PushPins</td>';
                            print '<td class="heading">Status</td>';
                            print '</tr>';

                            while ($my_corkboards) {
                                print '<tr>';
                                print '<td><a href="view_corkboard.php?corkboardID=' . $my_corkboards['corkboardID'] . '&userID=' .$row['userID']. ' ">' .$my_corkboards['title'] .'</a></td>';
                                print '<td>' . $my_corkboards['Pushpins'] . '</td>';
                                if ($my_corkboards['visibility']) {
                                    print '<td class="label label-important">Private</td>';
                                } else {
                                    print '<td>Public</td>';
                                }
                                print '</tr>';

                                $my_corkboards = mysqli_fetch_array($result, MYSQLI_ASSOC);
                            }
                            print '</table>';
                        } else {
                            print "<br/>None!";
                        }

                        ?>
                    </table>
                </div>

                <form name="searchform" action="search.php" method="post">
                    <table >
                        <tr>
                            <td><input type="textbox" name="terms" /></td>
                            <td><a href="javascript:searchform.submit();">Search</a></td>
                        </tr>
                    </table>

                </form>
            </div>
        </div> 

                <?php include("lib/error.php"); ?>
                    
				<div class="clear"></div> 		
			</div>    

               <?php include("lib/footer.php"); ?>
				 
		</div>
	</body>
</html>
