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
</head>
<body onclick='window.print()'>

<?php
$number=$_GET["text"];
echo "<img alt='testing' src='barcode.php?text=$number' height=150px width=250px /> ";

?>

</body>
</html>
<?php
}
?>
