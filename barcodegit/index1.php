<html>
<body>
<!--form action= <?php $_SERVER["PHP_SELF"]?>>
Number:<input type='text' name='text'>
<input type='submit'>
</form-->

<style>

#barcode
{
display:none;
}

</style>
<?php
//$number=$_GET["text"];
$myfile = fopen("accounts.txt", "r");

while(!feof($myfile)) {
  $number= fgets($myfile);
if($number!="")
	echo "<img id='barcode' alt='testing' src='barcode.php?text=$number' />";
//echo "<img src= \"Images\\".$number.".png\"/>";
}
?>
<h3>Barcodes generated for all accounts!</h3>
</body>

</html>