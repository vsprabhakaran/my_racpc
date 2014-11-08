<!doctype html>
<html lang=''>
<head>
   <title>Admin</title>
</head>
<body>

<div>
    <?php
        session_start();
        if( $_SESSION["role"] != "RACPC_UCO")
        {
           $_SESSION["role"] = "";
        ?><meta http-equiv="refresh" content="0;URL=login.html"><?php
        }
    ?>
<table border="0" style="width:100%;height:100%;border-width:2px;">
<tr>
<td> <div>
<iframe scrolling="no" frameBorder="0" src="header.php" style="width: 100%;height: 90px;" marginheight="0" marginwidth="0" frameborder="0"></iframe></div>
</td>
</tr>
<tr><td colspan="3"><div>
<iframe scrolling="no" frameBorder="0" src="cssmenu/ucoMenu.php" style="width: 100%;height: 85px;"></iframe></div>
</td></tr>
<tr>
<td><br/></td>
<td><br/></td>
<td><br/></td>
</tr>
<tr><td colspan="3"><br/></td></tr>
</table>
</div>

</body>
</html>
