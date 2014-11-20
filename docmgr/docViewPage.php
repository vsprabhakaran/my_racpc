<?php
        session_start();
        if( !($_SESSION["role"] == "BRANCH_VIEW" || $_SESSION["role"] == "RACPC_VIEW"))
        {
           $_SESSION["role"] = "";
        ?><meta http-equiv="refresh" content="0;URL=../login.html"><?php
        }
		else
		{
    ?>
<!doctype html>
<html lang=''>
<head>
   <title>Admin</title>
   <link rel="stylesheet" href="../menustyles.css">
   <script src="../jquery-latest.min.js" type="text/javascript"></script>
   <script src="../script.js"></script>
   
</head>
<body>

<div>
    
<table border="0" style="width:100%;height:100%;border-width:2px;">
<tr>
<td colspan="3"> <div>
<iframe scrolling="no" frameBorder="0" src="../header.php" style="width: 100%;height: 90px;" marginheight="0" marginwidth="0" frameborder="0"></iframe></div>
</td>
</tr>
<tr><td colspan="3">
<div id='cssmenu'>
<!--iframe scrolling="no" frameBorder="0" src="../cssmenu/viewUserMenu.php" style="width: 100%;height: 85px;"></iframe-->
	<ul>
	<li class="active" id="viewDocumentTab" style="width: 14%"><a href='#' ><span>View Document</span></a></li>
	<li style="width: 68%; text-align:right; visibility: hidden;">&nbsp;</li>
	<li style="text-align:center;"><a href='../logout.php' ><span>Logout</span></a></li>
	</ul>
 </div>
</td></tr>
<tr>
<td style="width: 10%"><br/></td>
<td><br/>
<iframe id="contentFrame" frameBorder="0" scrolling="no" src="../viewDoc.php" style="width: 100%;height: 500px;" marginheight="0" marginwidth="0" frameborder="0"></iframe>
</td>
<td style="width: 10%"><br/></td>
</tr>
<tr><td colspan="3"><br/></td></tr>
</table>
</div>

</body>
</html>
<?php 
}
?>