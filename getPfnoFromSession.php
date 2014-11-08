<?php
session_start();
$pfno = $_SESSION["pfno"];
if($pfno!="")
echo json_encode($_SESSION["pfno"]);
else
echo json_encode(FALSE);
?>