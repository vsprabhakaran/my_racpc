<?php
session_start();
?>
<!doctype html>
<head>

	<!-- Basics -->
	
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	
	<title>Login</title>

	<!-- CSS -->
	
	<!--link rel="stylesheet" href="css/reset.css"-->
	<!--link rel="stylesheet" href="css/animate.css"-->
	<link rel="stylesheet" href="css/loginstyles.css">
	<link rel="stylesheet" href="css/pure-min.css">
</head>

	<!-- Main HTML -->
	
<body style="background-image:url('img\\greyzz.png')">
	
	<!-- Begin Page Content -->
	<!--div id="header">
		<img src="img\header.png" height=80 width=300\>
		<img id="title" src="img\title.png" height=80 width=300\>
	</div-->
	<table border="0" style="width:100%;border-width:2px;">
<tr>
<td> <div>
<iframe frameBorder="0" src="header.php" style="width: 100%;height: 90px;" marginheight="0" marginwidth="0" frameborder="0"></iframe></div>
</td>
</tr>
</table>
<?php

$npassword=$_POST['cpassword'];
$pfno=$_SESSION['pfno'];
$mpassword=md5($npassword);
?>
	<div id="container1">
	<br><br>
		<form action="logout.php">
		<?php
		$con = new mysqli("localhost", "root", "", "racpc_automation_db");
		if ($con->connect_errno) {
    		die("Connection failed: " . $conn->connect_error);
			}
		$query=mysqli_query($con,"update adms_user_mstr set adms_password='$mpassword'  where pf_index = '$pfno'");
		//echo $query;
		$query=mysqli_query($con,"update adms_user_mstr set status_flag='A' where pf_index='$pfno'");
		if($query)
		{
		echo "<b>Password updated successfully. <br><br>Please login with New Password.</b>";
		}
		else
		{
		echo "<b>Network error occurred. <br><br>Please try after some time.</b>";
		}
		?>
		</div>
			<div id="lower1">
		<input type="submit" value="Login"/>
		</div>
		</form>
		
	
	
	
	<!-- End Page Content -->
	
</body>

</html>
	
	
	
		
	