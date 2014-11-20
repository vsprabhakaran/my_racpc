<?php
session_start();
        if( !( $_SESSION["role"] == "RACPC_DM" || $_SESSION["role"] == "RACPC_ADMIN" ))
        {
           $_SESSION["role"] = "";
           $_SESSION["pfno"] = "";
        ?><meta http-equiv="refresh" content="0;URL=../login.html"><?php
        }
		else 
		{
?>
<html>
<head>
<script type="text/javascript">
function printPage()
{
	document.getElementById("printImg").style.display="none";
	window.print();
	document.getElementById("printImg").style.display="block";
}
</script>
</head>
<body onclick='printPage()'>
<table border="0">
<tr>
<?php
$number=$_GET["text"];
echo "<td><img alt='testing' src='barcode.php?text=$number' height=150px width=250px /> </td>";
echo "<td><img alt='print' id=\"printImg\" src='../img/print_icon.jpg' height=50px width=50px style='margin-top: -60px;'/></td>";
?>
</tr>
</table>
</body>
</html>
<?php
}
?>
