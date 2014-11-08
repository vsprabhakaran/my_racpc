<html>
<body>
<form action= 'barcode.php?text=<?php echo $number?>'>
Number:<input type='text' name='text'>
<input type='submit'>
</form>
<?php
$number=$_GET["text"];
echo "<img alt='testing' src='barcode.php?text=$number' />";
?>
</body>
</html>