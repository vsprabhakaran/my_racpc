<?php
ini_set('display_errors','On');
//var_dump("in info page");
error_reporting(E_ALL | E_STRICT);
if(!isset($_SESSION)) session_start();

$request = $_POST['type'];

switch($request)
{
    case 'OutUpdateDocStatus':
    {
     OutUpdateDocStatus($_POST['accNo']);
     break;  
    }
    case 'InUpdateDocStatus':
    {
    InUpdateDocStatus($_POST['accNo']);
    }
}

function db_prelude(&$con)
{
    $con = new mysqli("localhost", "root", "", "racpc_automation_db");
    if ($con->connect_errno) {
        die("Connection failed: " . $conn->connect_error);
    }
}

function OutUpdateDocStatus($accountNumber)
{
    var_dump("updatestat");
    $con = NULL;
    db_prelude($con);  
    
    $query=mysqli_query($con,"UPDATE loan_account_mstr set document_status='O' where loan_acc_no ='$accountNumber' ");
    $rowcount=mysqli_num_rows($query);
    var_dump($rowcount);
    if ($rowcount > 0)
    {
       echo json_encode(TRUE);
    }
    else
    {
    echo json_encode(FALSE);
    }
   
    
}

function InUpdateDocStatus($accountNumber)
{
   $con = NULL;
    db_prelude($con);  
    
    $query=mysqli_query($con,"UPDATE loan_account_mstr set document_status='I' where loan_acc_no ='$accountNumber' ");
    $rowcount=mysqli_num_rows($query);
    //var_dump($rowcount);
    if ($rowcount > 0)
    {
       echo json_encode(TRUE);
    }
    else
    {
    echo json_encode(FALSE);
    }  
}
?>