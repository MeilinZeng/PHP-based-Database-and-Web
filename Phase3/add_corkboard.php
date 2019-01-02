<?php
include('lib/common.php');

if (!isset($_SESSION['email'])) {
    header('Location: login.php');
    exit();
}

$query = "SELECT userID " .
    "FROM User " .
    "WHERE User.email = '{$_SESSION['email']}'";
$result = mysqli_query($db, $query);
if (!is_bool($result) && (mysqli_num_rows($result) > 0) ) {
    $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
} else {
    array_push($error_msg,  "Query ERROR: add_corkboard select userID... <br>".  __FILE__ ." line:". __LINE__ );
}
$userID = $row['userID'];

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $title = mysqli_real_escape_string($db, $_POST['title']);
    $category = mysqli_real_escape_string($db, $_POST['category']);
    $visibility = mysqli_real_escape_string($db, $_POST['visibility']);

    if (empty($title)) {
        array_push($error_msg,  "Please enter a title.");
    }

    if (empty($category)) {
        array_push($error_msg,  "Please choose a category");
    }
    else {
        $query = "SELECT categoryID ".
            "FROM category " .
            "WHERE category.category_type='$category'";
        $result = mysqli_query($db, $query);
        if (!empty($result) && (mysqli_num_rows($result) > 0) ) {
            $row = mysqli_fetch_array($result, MYSQLI_ASSOC);
        } else {
            array_push($error_msg,  "SELECT ERROR: add_corkboard... <br>".  __FILE__ ." line:". __LINE__ );
        }
        $categoryID = $row['categoryID'];
    }

    if (empty($visibility)) {
        array_push($error_msg,  "Please choose either public or private.");
    } else {
        if ($visibility === "public") {
            $visibility = 0;
        } else {
            $visibility = 1;
            $txtPassword = mysqli_real_escape_string($db, $_POST['txtPassword']); ##test
        }
    }

//    $query = "INSERT INTO corkboard (title, interest) " .
//        "VALUES('{$_SESSION['email']}', '$interest')";
//    $query = "INSERT INTO corkboard SET title=$title, visibility=1, userID=2, categoryID=2, LastUpdates=2018-11-02"; //corkboardID=null,


    $query1 = "INSERT INTO corkboard (userID, categoryID, title, visibility, LastUpdates) 
  			  VALUES($userID, $categoryID, '$title', $visibility, NOW())";
    $result1 = mysqli_query($db, $query1);
   if (!($result1)) {
        array_push($error_msg,  "INSERT ERROR: add_corkboard... <br>".  __FILE__ ." line:". __LINE__ );
    }

    $corkboardID = mysqli_insert_id($db);
    if ($visibility === 0) {
        $query2 = "INSERT INTO publiccorkboard (corkboardID) 
                  VALUES($corkboardID)";    
    } else {
        $query2 = "INSERT INTO privatecorkboard (password, corkboardID) 
                  VALUES($txtPassword, $corkboardID)"; 
    }
    
    $result2 = mysqli_query($db, $query2);
   if (!($result2)) {
        array_push($error_msg,  "INSERT ERROR: add_corkboard... <br>".  __FILE__ ." line:". __LINE__ );
    }

}

?>

<?php include("lib/header.php"); ?>
<title>Add  CorkBoard</title>
</head>

<body>
<div id="main_container">
    <?php include("lib/menu.php"); ?>

    <div class="center_content">
        <div class="center_left">
            <div class="title_name"><?php print $row['firstname'] . ' ' . $row['lastname']; ?></div>
            <div class="features">

                <div class="profile_section">
                    <div class="subtitle">Add  CorkBoard</div>

                    <form name="profileform" action="add_corkboard.php" method="post">
                        <table>
                            <tr>
                                <td class="item_label">Title</td>
                                <td>
                                    <input type="text" name="title" />
                                </td>
                            </tr>
                            <tr>
                                <td class="item_label">Category</td>
                                <td>
                                    <select name="category">
                                        <option>Architecture</option>
                                        <option>Art</option>
                                        <option>Education</option>
                                        <option>Food & Drink</option>
                                        <option>Home & Garden</option>
                                        <option>Other</option>
                                        <option>People</option>
                                        <option>Pets</option>
                                        <option>Photography</option>
                                        <option>Sports</option>
                                        <option>Technology</option>
                                        <option>Travel</option>
                                    </select>
                                </td>
                            </tr>

                            <tr>
                                <td class="item_label">Visibility</td>
                                <td><input type="radio" name="visibility"
                                        <?php if (isset($visibility) && $visibility=="public") echo "checked";?>
                                           value="public">Public</td>
                            </tr>
                            <tr>
                                <td></td>
                                <td><input type="radio"  name="visibility"
                                        <?php if (isset($visibility) && $visibility=="private") echo "checked";?>
                                           value="private">Private</td>
                                <td><input type="text" name="txtPassword" value="Enter Password"
                                           onclick="if(this.value=='Enter Password'){this.value=''}"
                                           onblur="if(this.value==''){this.value='Enter Password'}"/></td>
                            </tr>

                            <tr></tr>
                            <tr></tr>
                        </table>
                        <a href="javascript:profileform.submit();">Add</a>

                    </form>
                </div>

            </div>
        </div>

        <?php include("lib/error.php"); ?>

        <div class="clear"></div>
    </div>

</div>
</body>
</html>

