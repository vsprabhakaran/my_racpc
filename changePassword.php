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
	<script type="text/javascript">
	function validateForm()
	{
	var formnpassword= document.forms["changePassword1"]["npassword"].value;
	var formcpassword= document.forms["changePassword1"]["cpassword"].value;
		if(formnpassword!=formcpassword)
		{
			alert("Password does not match");
			return false;
		}
		
		if(formnpassword=="12345")
		{
			alert("New Password should not be default password");
			return false;
		}
		
			if(formnpassword.length<6)
		{
			alert("Password length should be minimum 6 characters");
			return false;
		}
	}
	
	</script>
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
	<div id="container">
	<br><br>
		<form class="pure-form pure-form-aligned" onsubmit = "return validateForm()" action="changePassword1.php" method="POST" name="changePassword1">
		<div class="pure-control-group1">
		<label id="labels" for="npassword" >New Password</label>
		<input type="password" name="npassword"/>
		</div>
		<br><br>
		<div class="pure-control-group1">
		<label id="labels" for="cpassword">Confirm Password</label>
		<input type="password" name="cpassword"/>
		</div>
		<br>
		<div id="lower">
		
		<!--input type="checkbox"><label class="check" for="checkbox">Keep me logged in</label-->
		
		<input type="submit" value="Submit"/>
		
		
		</div>
		
		</form>
		
	</div>
	
	
	<!-- End Page Content -->
	
</body>

</html>

	
	
	
		
	