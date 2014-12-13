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
<body>

<?php
$number=$_GET["text"];
//added variable check for hidding print button and reducing the size of barcode for slip generation
if(isset($_GET["check"]))
$check =$_GET["check"];
else 
$check="";
if($check == '') 
{
echo "<img alt='testing' src='barcode.php?text=$number' height=150px width=250px /> ";
}
else if($check=='2')
{
    echo "<img alt='testing' src='barcode.php?text=$number' height=35px width=176px /> ";
}
else
{
echo "<img alt='testing' src='barcode.php?text=$number' height=150px width=150px />";
}
?>

</body>
</html>
<?php
}
?>
