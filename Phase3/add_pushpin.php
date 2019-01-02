<?php

include('lib/common.php');

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

if (!empty($_GET['corkboardID'])) {

    $_SESSION['cb_id'] = mysqli_real_escape_string($db, $_GET['corkboardID']);

    $query = "SELECT userID " .
        "FROM User " .
        "WHERE User.email='{$_SESSION['email']}'";
    $result = mysqli_query($db, $query);
    include('lib/show_queries.php');
    if (!is_bool($result) && (mysqli_num_rows($result) > 0)) {
        $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        $_SESSION['add_pushpin_userID'] = $row['userID'];
    } else {
        array_push($error_msg, "Query ERROR: Failed to get User profile...<br>" . __FILE__ . " line:" . __LINE__);
    }

}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $url = mysqli_real_escape_string($db, $_POST['url']);
    $description = mysqli_real_escape_string($db, $_POST['description']);
    $tags = mysqli_real_escape_string($db, $_POST['tags']);
    $scheme =  parse_url(url, scheme);
//    array_push($error_msg,  'aa '.$_SESSION['cb_id'] . $url . $description);

        if (empty($url) || empty($description)) {
            array_push($error_msg,  "Error: please provide both url and description.");
        } else{
            if (parse_url($url, PHP_URL_SCHEME)=='http' || parse_url($url, PHP_URL_SCHEME)=='https') {
                if (strpos(get_headers($url)[0], '200') == false) {
                    echo "Error: cannot successfully get url.";
                    array_push($error_msg,  "Error: cannot successfully get url.");
                } else {
                    if (strpos(get_headers($url)[2], 'image') == true || strpos($url, 'jpg') == true || strpos($url, 'png') == true || strpos($url, 'jpeg') == true) {
                        $query = "INSERT INTO Pushpin (corkboardID, url, description, updatedtime) " .
                                 "VALUES ('{$_SESSION['cb_id']}', '$url', '$description', NOW())";
                        $pushpin = mysqli_query($db, $query);
                        include('lib/show_queries.php');
                        if (mysqli_affected_rows($db) == -1) {
                            array_push($error_msg,  "Query ERROR: Failed to add to pushpin table...<br>" . __FILE__ ." line:". __LINE__ );
                        }
                    } else {
                        print "not valid image";
                        array_push($error_msg, "Error: the url does not refer to an image.");
                    }
                }
            } else {
                echo "Error: the url should start with http or https";
                array_push($error_msg,  "Error: the url should start with http or https");
            }
        }
        $pushpinID = mysqli_insert_id($db);

    if (!empty($tags)) {
        foreach (explode(',', $tags) as $tag) {
            $query_tags = sprintf("insert into Tags (pushpinTag, pushpinID) values ('%s', '%s')", $tag, $pushpinID);
            $result_tags = mysqli_query($db, $query_tags);
            if ($result_tags  == False) {  //INSERT, UPDATE, DELETE, DROP return True on Success  / False on Error
                array_push($error_msg, "INSERT ERROR: Tags " . __FILE__ ." line:". __LINE__ );
            }
        }
    }

    header(sprintf("Location: view_corkboard.php?corkboardID=%s&userID=%s", $_SESSION['cb_id'], $_SESSION['add_pushpin_userID']));
    die();

}

?>

<?php include("lib/header.php"); ?>
<title>Add PushPin</title>
</head>

<body>
<div id="main_container">
    <?php include("lib/menu.php"); ?>

    <div class="center_content">
        <div class="center_left">
            <table>
                <tr>
                    <td><div class="title_name">
                           Add PushPin
                        </div></td>
                </tr>
            </table>
            <div class="features">
                <div class="profile_section">
                    <form name="requestform" action="add_pushpin.php" method="POST">
                    <table>
                        <tr>
                            <td class="item_label">URL</td>
                            <td><input type="text" name="url" /></td>
                        </tr>
                        <tr>
                            <td class="item_label">Description</td>
                            <td><input type="textbox" name="description" /></td>
                        </tr>
                        <tr>
                            <td class="item_label">Tags</td>
                            <td><input type="text" name="tags" /></td>
                        </tr>
                    </table>
                        <td><a href="javascript:requestform.submit();">Add</a></td>
                    </form>
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

