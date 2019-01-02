<?php
include('lib/common.php');

if($showQueries){
  array_push($query_msg, "showQueries currently turned ON, to disable change to 'false' in lib/common.php");
}

//Note: known issue with _POST always empty using PHPStorm built-in web server: Use *AMP server instead
if( $_SERVER['REQUEST_METHOD'] == 'POST') {

	$enteredEmail = mysqli_real_escape_string($db, $_POST['email']);
	$enteredPassword = mysqli_real_escape_string($db, $_POST['password']);

    if (empty($enteredEmail)) {
            array_push($error_msg,  "Please enter an email address.");
    }

	if (empty($enteredPassword)) {
			array_push($error_msg,  "Please enter a password.");
	}
	
    if ( !empty($enteredEmail) && !empty($enteredPassword) )   { 

        $query = "SELECT * FROM User WHERE email='$enteredEmail' AND pin='$enteredPassword'";
        
        $result = mysqli_query($db, $query);
        include('lib/show_queries.php');
        $count = mysqli_num_rows($result); 
        
        if (!empty($result) && ($count > 0) ) {
            $_SESSION['email'] = $enteredEmail;
            array_push($query_msg, "logging in... ");
            header(REFRESH_TIME . 'url=view_profile.php');		//to view the password hashes and login success/failure

        } else {
            array_push($error_msg, "Login failed: Invalid username or password. ");
        }
    }
}
?>

<?php include("lib/header.php"); ?>
<title>CorkBoardIt Login</title>
</head>
<body>
    <div id="main_container">
        <div id="header">
            <div class="logo">
                <img src="img/gtonline_logo.png" style="opacity:0.5;background-color:E9E5E2;" border="0" alt="" title="GT Online Logo"/>
            </div>
        </div>

        <div class="center_content">
            <div class="text_box">

                <form action="login.php" method="post" enctype="multipart/form-data">
                    <div class="title">CorkBoardIt Login</div>
                    <div class="login_form_row">
                        <label class="login_label">Email:</label>
                        <input type="text" name="email" value="norasmith@gmail.com" class="login_input"/>
                    </div>
                    <div class="login_form_row">
                        <label class="login_label">PIN:</label>
                        <input type="password" name="password" value="1314" class="login_input" maxlength="4"/>
                    </div>
                    <input type="image" src="img/login.gif" class="login"/>
                    <form/>
                </div>

                <?php include("lib/error.php"); ?>

                <div class="clear"></div>
            </div>
   
            <!-- 
			<div class="map">
			<iframe style="position:relative;z-index:999;" width="820" height="600" src="https://maps.google.com/maps?q=801 Atlantic Drive, Atlanta - 30332&t=&z=14&ie=UTF8&iwloc=B&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"><a class="google-map-code" href="http://www.embedgooglemap.net" id="get-map-data">801 Atlantic Drive, Atlanta - 30332</a><style>#gmap_canvas img{max-width:none!important;background:none!important}</style></iframe>
			</div>
             -->
					<?php include("lib/footer.php"); ?>

        </div>
    </body>
</html>
