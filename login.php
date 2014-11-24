<?php
session_start();
if(isset($_SESSION["role"]))
$role= $_SESSION["role"];
else 
$role="";

switch($role)
    {
        case "RACPC_ADMIN":
        {
            $_SESSION["role"] = "RACPC_ADMIN" ;
            ?><meta http-equiv="refresh" content="0;URL=admin/adminPage.php"><?php
            break;
        }
        case "RACPC_UCO":
        {
            $_SESSION["role"] = "RACPC_UCO";
            ?><meta http-equiv="refresh" content="0;URL=uco/ucoPage.php"><?php
            break;
        }
        case "RACPC_VIEW":
        {
            $_SESSION["role"] = "RACPC_VIEW";
            ?><meta http-equiv="refresh" content="0;URL=docmgr/docViewPage.php"><?php
            break;
        }
        case "BRANCH_VIEW":
        {
            $_SESSION["role"] = "BRANCH_VIEW";
            ?><meta http-equiv="refresh" content="0;URL=docmgr/docViewPage.php"><?php
            break;
        }
        case "RACPC_DM":
        {
            $_SESSION["role"] = "RACPC_DM";
            ?><meta http-equiv="refresh" content="0;URL=docmgr/docManagerPage.php"><?php
            break;
        }
        default:
        {
            
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
	
<body style="background-image:url('img/greyzz.png')">
	
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
		<form class="pure-form pure-form-aligned" action="authorize.php" method="POST">
		<div class="pure-control-group">
		<label id="labels" for="pfno" >PF Index</label>
		<input type="text" name="pfno"/>
		</div>
		<br><br>
		<div class="pure-control-group">
		<label id="labels" for="password">Password</label>
		<input type="password" name="password"/>
		</div>
		<br>
		<div id="lower">
		
		<!--input type="checkbox"><label class="check" for="checkbox">Keep me logged in</label-->
		
		<input type="submit" value="Login"/>
		
		
		</div>
		
		</form>
		
	</div>
	
	<iframe frameBorder="0" scrolling="no" src='footer.php' style="width: 100%;height: 5%; position:absolute; bottom:0; background-color: #0f71ba;" marginheight="0" marginwidth="0" frameborder="0"/>
	<!-- End Page Content -->
	
</body>

</html>
	
<?php
        break;
        }
    }
?>
	
	
	
		
	