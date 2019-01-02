<?php

include('lib/common.php');
if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $terms = mysqli_real_escape_string($db, $_POST['terms']);
    if (empty($terms)) {
        array_push($error_msg,  "Error: You must provide a search term ");
    }
    $searchTerms = '%' .$terms  .'%';
    $query = "SELECT Pushpin.pushpinID as PP_ID, CorkBoard.corkboardID as CB_ID, description AS PushPin_Description, URL AS URL, CorkBoard.title AS CorkBoard, User.name AS Owner, category.category_type, User.userID ".
        "FROM Pushpin ".
        "inner JOIN CorkBoard ".
        "ON Pushpin.corkboardID = CorkBoard.corkboardID ".
        "INNER JOIN User ".
        "ON CorkBoard.userID = User.userID ".
        "inner join category ".
        "on category.categoryID = corkboard.categoryID ".
        "LEFT JOIN Tags ".
        "ON Tags.pushpinID = Pushpin.pushpinID ".
        "WHERE (Pushpin.description LIKE '$searchTerms' ".
        "OR category.category_type LIKE '$searchTerms' ".
        "OR Tags.pushpinTag LIKE '$searchTerms')  ".
        "AND Corkboard.visibility = 0 ".
        "ORDER BY Pushpin.description ASC";
    $result = mysqli_query($db, $query);
    include('lib/show_queries.php');
    if ( !is_bool($result) && (mysqli_num_rows($result) > 0) ) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $count = mysqli_num_rows($result);
    } else {
        array_push($error_msg,  "Query ERROR: Failed to search...<br>" . __FILE__ ." line:". __LINE__ );
    }
}
?>

<?php include("lib/header.php"); ?>
<title>PushPin Search Results</title>
</head>

<body>
<div id="main_container">
    <?php include("lib/menu.php"); ?>

    <div class="center_content">
        <div class="center_left">
            <div class="features">
                <div class="profile_section">
                    <div class="subtitle">
                        <?php print 'PushPin Search Results for ' .$terms .' ' .$searchTerms; ?>
                    </div>
                </div>
            </div>

            <div class="features">
                <div class="profile_section">
                    <?php
                    print '<table>';
                    print '<tr>';
                    print '<td class="heading"><u>PushPin Description</u></td>';
                    print '<td class="heading"><u>CorkBoard</u></td>';
                    print '<td class="heading"><u>Owner</u></td>';
                    print '</tr>';
                    while ($row) {
                        print '<tr>';
                        // print '<td>'.$row['PushPin_Description'].'</td>';                  
                        print '<td><a href="view_pushpin.php?pushpinID=' . $row['PP_ID'] . '&userID=' .$row['userID']. ' ">' .$row['PushPin_Description'] .'</a></td>';
                        print '<td><a href="view_corkboard.php?corkboardID=' . $row['CB_ID'] . '&userID=' .$row['userID']. ' ">' .$row['CorkBoard'] .'</a></td>';
                        print '<td>'.$row['Owner'].'</td>';
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
