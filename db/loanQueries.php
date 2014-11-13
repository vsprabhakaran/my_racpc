<?php
ini_set('display_errors','On');
//var_dump("in info page");
error_reporting(E_ALL | E_STRICT);
if(!isset($_SESSION)) session_start();

function db_prelude(&$con)
{
    $con = new mysqli("localhost", "root", "", "racpc_automation_db");
    if ($con->connect_errno) {
        die("Connection failed: " . $conn->connect_error);
    }
}

function UpdateLoanDocuments($accountNumber,$documentUploaded,$folioNumber,$rackNumber)
{
    $con = NULL;
    db_prelude($con);  
    if($documentUploaded == TRUE)
        $query=mysqli_query($con,"update adms_loan_account_mstr set document_status='IN', folio_no = '$folioNumber', rack = '$rackNumber' where loan_acc_no = '$accountNumber'");
    
    if($query)
        return TRUE;
    else
        return FALSE;
}
function InsertLoanDocuments($accountNumber,$documentUploaded,$folioNumber,$rackNumber)
{
    $con = NULL;
    db_prelude($con);  
    if($documentUploaded == TRUE)
        $query=mysqli_query($con,"insert into adms_loan_account_mstr(loan_acc_no,loan_status,document_status,folio_no,rack,no_of_files)  values ('$accountNumber','A','IN','$folioNumber','$rackNumber','1')");
    
    if($query)
        return TRUE;
    else
        return FALSE;
}
function getBranchCode($accNo)
{
	$con = NULL;
    db_prelude($con);
	$colname = "branch_code"; 
    $query=mysqli_query($con,"SELECT branch_code AS '$colname' FROM loan_account_mstr WHERE loan_acc_no = '$accNo'");
    $row=mysqli_fetch_array($query);
	return ($row[0]);
}
?>