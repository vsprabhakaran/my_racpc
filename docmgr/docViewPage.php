<?php
        session_start();
        if( !($_SESSION["role"] == "BRANCH_VIEW" || $_SESSION["role"] == "RACPC_VIEW"))
        {
           $_SESSION["role"] = "";
		   $_SESSION["pfno"] = "";
        ?><meta http-equiv="refresh" content="0;URL=../login.php"><?php
        }
		else
		{
    ?>
<!doctype html>
<html lang=''>
<head>
   <title>View Document</title>
   <link rel="stylesheet" href="../menustyles.css">
   <script src="../jquery-latest.min.js" type="text/javascript"></script>
   <script src="../script.js"></script>
   
</head>
<body style="background-image:url('../img/greyzz.png'); margin: 0;">

<div>
    
<table border="0" style="width:100%;height:100%;border-spacing:0px;">
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
	<li style="text-align:center;float:right;"><a href='../logout.php' ><span>Logout</span></a></li>
	</ul>
 </div>
</td></tr>
<tr>
<td style="width: 5%"><br/></td>
<td><br/>
<iframe id="contentFrame" frameBorder="0" scrolling="no" src="../viewDoc.php" style="width: 100%;height: 50em;padding-bottom:2em;" marginheight="0" marginwidth="0" frameborder="0"></iframe>
</td>
<td style="width: 5%"><br/></td>
</tr>
</table>
</div>
<div>
  <iframe frameBorder="0" scrolling="no" src='../footer.php' style="width: 100%;height: 2em; position:fixed; bottom:0; background-color: #0f71ba;vertical-align:bottom;" marginheight="0" marginwidth="0" frameborder="0"/>
</div>
</body>
</html>
<?php 
}
?>
