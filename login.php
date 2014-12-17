<?php
	session_start();
	require ("inc/setting.inc.php");
	$mode = (isset($_GET['mode'])) ? $_GET['mode'] : NULL;
	$url = (isset($_GET['url'])) ? $_GET['url'] : NULL;
	$error[0] = false;
	$error[1] = false;
	$error[2] = false;
	if($mode == "logout") {
		session_destroy();
		$error[2] = true;
	}
	if($mode == "login") {
	$mysqli = new mysqli($config['host'], $config['user'], $config['pass'], $config['dbname']);
	if ($mysqli->connect_errno) {
		echo $mysqli->connect_error;
		exit;
	}
	$mysqli->query("SET NAMES UTF8");
	$username = $_POST['login-username'];
	$password = $_POST['login-password'];
	$sql = "SELECT * FROM `member_staff`WHERE username='$username' AND password='$password'";
	$result = $mysqli->query($sql);
	$check =$result->num_rows;
	if($check == 0){
			$error[0] = true;
	} else { //login success
		$record = $result->fetch_object();
		//check position
		if($record->position == "A" || $record->position == "M") {
			$mid = $record->memberid;
			$fn = $record->nickname;
			$_SESSION["memberid"] = $mid;
			$_SESSION["firstname"] = $fn;
			$_SESSION["adminmode"] = true;
			if($_POST['redirect'] == NULL) header("location:index.php");
			else header("location:{$_POST['redirect']}.php");
		} else {
			$error[1] = true;
		}
	}
	}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">
    <meta charset="utf-8">
    <!-- Title and other stuffs -->
    <title>Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="description" content="">
    <meta name="keywords" content="">
    <meta name="author" content="">

    <!-- Stylesheets -->
    <link href="admin/style/bootstrap.css" rel="stylesheet">
    <link rel="stylesheet" href="admin/style/font-awesome.css">
    <link href="admin/style/style.css" rel="stylesheet">
    <!--[if lte IE 9]>
    <link href="admin/style/styleie.css" rel="stylesheet">
    <![endif]-->
    <!-- Bootstrap toggle -->
    <link rel="stylesheet" href="admin/style/bootstrap-switch.css">


    <!-- HTML5 Support for IE -->
    <!--[if lt IE 9]>
    <script src="admin/js/html5shim.js"></script>
    <![endif]-->

    <!-- Favicon -->
    <link rel="shortcut icon" href="admin/img/favicon/favicon.png">
	<style>
	a{
		color:#ff981f;
	}
	</style>
</head>

<body class="login">

<div id="login-container" style="border: 5px solid #ffa53b;">
    <a href="index.php" class="navbar-brand" style="font-family:'rsu'; font-size:2em; color:#ffa53b;"><?php echo $info['name'];?></a>
	<br>
	<?php if($error[0]) { ?>
	<div class="alert alert-danger">
               ชื่อผู้ใช้ / รหัสผ่านไม่ถูกต้องค่ะ
   </div>
   <?php } if($error[1]) { ?>
   <div class="alert alert-info">
			ขออภัยค่ะ หน้านี้เข้าได้เฉพาะผู้จัดการร้าน หรือผู้ดูแลระบบเท่านั้นค่ะ
   </div>
   <?php } if($error[2]) { ?>
   <div class="alert alert-success">
			ท่านได้ออกจากระบบเรียบร้อยแล้วค่ะ
   </div>
   <?php } ?>
    <form id="login-form" class="form-horizontal" method="post" action="login.php?mode=login" style="display: block;">
        <div class="form-group">
            <div class="input-group col-xs-12">
                <input id="login-email" class="form-control" type="text" placeholder="Username.." name="login-username">
<span class="input-group-addon">
<i class="icon-user icon-fixed-width"></i>
</span>
            </div>
        </div>
        <div class="form-group">
            <div class="input-group col-xs-12">
                <input id="login-password" class="form-control" type="password" placeholder="Password.."
                       name="login-password">
<span class="input-group-addon">
<i class="icon-asterisk icon-fixed-width"></i>
</span>
            </div>
        </div>
        <div class="clearfix">
            <div class="btn-group btn-group-sm pull-right">
                <button id="login-button-pass" class="btn btn-warning" title="" data-toggle="tooltip" type="button" data-original-title="Forgot pass?">
                    <i class="icon-lock"></i>
                </button>
                <button class="btn btn-primary" type="submit" style="background:#ed8000;border: 1px solid #ed8000;">
                    <i class="icon-arrow-right"></i>
                    Login
                </button>
            </div>
            <!--div class="make-switch pull-left" data-on="primary" data-off="danger">
                <input type="checkbox" checked>
            </div-->
        </div><br>
		 Powered by <a href="#">CoinCoin*</a> 
		 <input type="hidden" name="redirect" value="<?php echo $url;?>">
    </form>
</div>

<!-- JS -->
<script src="admin/js/jquery.js"></script>
<script src="admin/js/bootstrap.js"></script>
<script src="admin/js/bootstrap-switch.min.js"></script>
</body>
</html>