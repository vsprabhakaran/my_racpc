<?php
ini_set('display_errors','On');
//var_dump("in info page");
error_reporting(E_ALL | E_STRICT);
session_start();
$request = $_POST['type'];
switch($request)
{
	case 'getPfno':
	{
		getPfno();
		break;
	}
	case 'getRole':
	{
		getRole();
		break;
	}
}
function getPfno()
{	
	$pfno = $_SESSION["pfno"];
	if($pfno!="")
	echo json_encode($_SESSION["pfno"]);
	else
	echo json_encode(FALSE);
}
function getRole()
{
	$role=$_SESSION["role"];
	if($role!="")
	echo json_encode($role);
	else
	echo json_encode(FALSE);
}
?>