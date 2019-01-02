<?php

include('lib/common.php');

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

$query = "select substring(url, 1, position('/' in url)-1) as Site, count(distinct pushpinID) as PushPins ".
                    "from ( ".
                     "   select substring(url, position('://' in url)+3) as url, pushpinID FROM pushpin ".
                    ") a ".
                    "group by 1 order by 2 desc";
$result = mysqli_query($db, $query);
include('lib/show_queries.php');

if ( !is_bool($result) && (mysqli_num_rows($result) > 0) ) {
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
    $count = mysqli_num_rows($result);
} else {
    array_push($error_msg,  "Query ERROR: Failed to get popular sites...<br>" . __FILE__ ." line:". __LINE__ );
}
?>

<?php include("lib/header.php"); ?>
<title>Popular Sites</title>
</head>

<body>
<div id="main_container">
    <?php include("lib/menu.php"); ?>

    <div class="center_content">
        <div class="center_left">
            <div class="features">
                <div class="profile_section">
                    <div class="subtitle">Popular Sites</div>
                </div>
            </div>

            <div class="features">
                <div class="profile_section">
                    <?php
                    print '<table>';
                    print '<tr>';
                    print '<td class="heading"><u>Site</u></td>';
                    print '<td class="heading"><u>PushPins</u></td>';
                    print '</tr>';
                    while ($row) {
                        print '<tr>';
                        print '<td>' . $row['Site'] . '</td>';
                        print '<td>' . $row['PushPins'] . '</td>';
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

