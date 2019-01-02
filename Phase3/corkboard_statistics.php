<?php

include('lib/common.php');

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

$query = "select distinct ".
                           "name ".
                         ", email".
                         ", sum(case when visibility=0 then number_of_cbs else 0 end) pub_cb_cnt ".
                         ", sum(case when visibility=0 then number_of_pps else 0 end) pub_pp_cnt ".
                         ", sum(case when visibility>0 then number_of_cbs else 0 end) pri_cb_cnt ".
                         ", sum(case when visibility>0 then number_of_pps else 0 end) pri_pp_cnt ".
                    "from ( ".
                        "SELECT u.name ".
                             ", u.email".
                             ", cb.visibility ".
                             ", count(distinct cb.corkboardID) as number_of_cbs ".
                             ", count(distinct pp.pushpinID) as number_of_pps ".
                        "FROM user u ".
                        "left join corkboard cb on u.userID = cb.userID ".
                        "left join pushpin pp on cb.corkboardID = pp.corkboardID ".
                        "group by 1,2,3".
                    ") a ".
                    "group by 1, 2 order by 2 desc, 4 desc, 3 desc, 5 desc";
$result = mysqli_query($db, $query);
include('lib/show_queries.php');

if ( !is_bool($result) && (mysqli_num_rows($result) > 0) ) {
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $count = mysqli_num_rows($result);
} else {
    array_push($error_msg,  "Query ERROR: Failed to get corkBoard statistics...<br>" . __FILE__ ." line:". __LINE__ );
}
?>

<?php include("lib/header.php"); ?>
<title>CorkBoard Statistics</title>
</head>

<body>
<div id="main_container">
    <?php include("lib/menu.php"); ?>

    <div class="center_content">
        <div class="center_left">
            <div class="features">
                <div class="profile_section">
                    <div class="subtitle">CorkBoard Statistics</div>
                </div>
            </div>

            <div class="features">
                <div class="profile_section">
                    <?php
                    print '<table>';
                    print '<tr>';
                    print '<td class="heading"><u>User</u></td>';
                    print '<td class="heading"><u>Public CorkBoards</u></td>';
                    print '<td class="heading"><u>Public PushPins</u></td>';
                    print '<td class="heading"><u>Private CorkBoards</u></td>';
                    print '<td class="heading"><u>Private PushPins</u></td>';
                    print '</tr>';
                    while ($row) {
                        print '<tr>';
                        print '<td>' . ($_SESSION['email'] == $row['email'] ? '<font color="red">': '') . $row['name'] . '</font></td>';
                        print '<td>' . ($_SESSION['email'] == $row['email'] ? '<font color="red">': '') . $row['pub_cb_cnt'] . '</font></td>';
                        print '<td>' . ($_SESSION['email'] == $row['email'] ? '<font color="red">': '') . $row['pub_pp_cnt'] . '</font></td>';
                        print '<td>' . ($_SESSION['email'] == $row['email'] ? '<font color="red">': '') . $row['pri_cb_cnt'] . '</font></td>';
                        print '<td>' . ($_SESSION['email'] == $row['email'] ? '<font color="red">': '') . $row['pri_pp_cnt'] . '</font></td>';
                        print '</tr>';
                        $row =  mysqli_fetch_array($result, MYSQLI_ASSOC);
                    }
                    print '</table>';
                    ?>
                </div>
            </div>
        </div>

        <?php include("lib/error.php"); ?>

        <div class="clear"></div>
    </div>

</div>
</body>
</html>

