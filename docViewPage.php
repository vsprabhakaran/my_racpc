<!doctype html>
<html lang=''>
<head>
   <title>Document View</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="menustyles.css">
   <script src="jquery-latest.min.js" type="text/javascript"></script>
   <script src="script.js"></script>
    <?php
        session_start();
        if( !($_SESSION["role"] == "BRANCH_USER" || $_SESSION["role"] == "RACPC_VIEW" || $_SESSION["role"] == "RACPC_ADMIN" ))
        {
           $_SESSION["role"] = "";
           $_SESSION["pfno"] = "";
        ?>
<meta http-equiv="refresh" content="0;URL=login.php"><?php
        }
    ?>
</head>
<body>

<div>
    
<table border="0" style="width:100%;height:100%;border-width:2px;">
<tr>
<td colspan="3"> <div>
<iframe scrolling="no" frameBorder="0" src="header.php" style="width: 100%;height: 90px;" marginheight="0" marginwidth="0" frameborder="0"></iframe></div>
</td>
</tr>
<tr><td colspan="3">
<div id='cssmenu'>
<ul>
   
   <li id="viewDocumentTab" class='active' style="width: 14%"><a href='#'  onclick="displayPanel('viewDocumentTab')"><span>View Document</span></a></li>
  
   <li style="width: 70%; text-align:right; visibility: hidden;">&nbsp;</li>
   <li style="text-align:center;"><a href='logout.php' ><span>Logout</span></a></li>
</ul>
</div>
    <!--div>
<iframe scrolling="no" frameBorder="0" src="cssmenu/viewUserMenu.php" style="width: 100%;height: 85px;"></iframe></div-->
</td></tr>
<tr>
<td style="width: 10%"><br/></td>
<td><br/>
<div>
<iframe scrolling="no" frameBorder="0" src="viewDoc.php" style="width: 100%;height: 500px;"></iframe>
</div>
</td>
<td style="width: 10%"><br/></td>
</tr>
<tr><td colspan="3"><br/></td></tr>
</table>
</div>

</body>
</html>
