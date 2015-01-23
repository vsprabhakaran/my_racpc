<?php
ini_set('display_errors','On');

error_reporting(E_ALL | E_STRICT);
session_start();
if( !($_SESSION["role"] == "BRANCH_VIEW" || $_SESSION["role"] == "RACPC_VIEW" || $_SESSION["role"] == "RACPC_ADMIN" || $_SESSION["role"] == "RACPC_DM"))
{
  $_SESSION["role"] = "";
  $_SESSION["pfno"] = "";
  ?><meta http-equiv="refresh" content="0;URL=../login.php"><?php
}
else
{

  $request = $_POST['type'];
  require_once("../db/dbConnection.php");
  switch($request)
  {
    case 'isValidAccount':
    {
      isValidAccount($_POST['accNo']);
      break;
    }
    case 'isLoanAccountInADMS':
    {
      isLoanAccountInADMS($_POST['accNo']);
      break;
    }
    case 'GetAccountNameOfAccount':
    {
      GetAccountNameOfAccount($_POST['accNo']);
      break;
    }
  }
}
