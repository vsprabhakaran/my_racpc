<?php
$number=$_GET["text"];
echo "<img alt='testing' src='barcode.php?text=$number' height=150px width=250px onclick='window.print()'/> ";
?>
