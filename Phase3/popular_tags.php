<?php

include('lib/common.php');

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

$query = " SELECT pushpinTag, COUNT(distinct Tags.pushpinID) AS PushPins, COUNT(DISTINCT PushPin.corkboardID) AS UniqueCorkBoards ".
                    "FROM Tags ".
                    "LEFT JOIN Pushpin ".
                    "ON Tags.PushPinID = Pushpin.pushpinID ".
                    "GROUP BY PushpinTag ".
                    "ORDER BY PushPins DESC, UniqueCorkBoards DESC ".
                    "LIMIT 5 ";
$result = mysqli_query($db, $query);
include('lib/show_queries.php');

if ( !is_bool($result) && (mysqli_num_rows($result) > 0) ) {
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $count = mysqli_num_rows($result);
} else {
    array_push($error_msg,  "Query ERROR: Failed to get popular tags...<br>" . __FILE__ ." line:". __LINE__ );
}
?>

<?php include("lib/header.php"); ?>
<title>Popular Tags</title>
</head>

<body>
<div id="main_container">
    <?php include("lib/menu.php"); ?>

    <div class="center_content">
        <div class="center_left">
            <div class="features">
                <div class="profile_section">
                    <div class="subtitle">Popular Tags</div>
                </div>
            </div>

            <div class="features">
                <div class="profile_section">
                    <?php
                    print '<table>';
                    print '<tr>';
                    print '<td class="heading"><u>Tags</u></td>';
                    print '<td class="heading"><u>PushPins</u></td>';
                    print '<td class="heading"><u>Unique CorkBoards</u></td>';
                    print '</tr>';
                    while ($row) {
                        print '<tr>';
                        print '<td>' . $row['pushpinTag'] . '</td>';
                        print '<td>' . $row['PushPins'] . '</td>';
                        print '<td>' . $row['UniqueCorkBoards'] . '</td>';
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

