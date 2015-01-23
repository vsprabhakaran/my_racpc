<?php
//ini_set('display_errors','On');
//var_dump("in info page");
error_reporting(E_ALL | E_STRICT);
if(!isset($_SESSION)) 
session_start();
if( !($_SESSION["role"] == "BRANCH_VIEW" || $_SESSION["role"] == "RACPC_VIEW" || $_SESSION["role"] == "RACPC_ADMIN" || $_SESSION["role"] == "RACPC_DM"))
        {
           $_SESSION["role"] = "";
           $_SESSION["pfno"] = "";
        ?><meta http-equiv="refresh" content="0;URL=../login.php"><?php
        }
require_once("../db/dbConnection.php");
// function to get the ip address of machine
function get_ip_address(){
            if (isset($_SERVER))
            {
                if (isset($_SERVER)) 
                {
                if (isset($_SERVER["HTTP_X_FORWARDED_FOR"]) && ip2long($_SERVER["HTTP_X_FORWARDED_FOR"]) !== false) 
                {
                $ipadres = $_SERVER["HTTP_X_FORWARDED_FOR"];  
                }
                elseif (isset($_SERVER["HTTP_CLIENT_IP"])  && ip2long($_SERVER["HTTP_CLIENT_IP"]) !== false) 
                {
                $ipadres = $_SERVER["HTTP_CLIENT_IP"];  
                }
                else 
                {
                $ipadres = $_SERVER["REMOTE_ADDR"]; 
                }
                }
            } 
            else 
            {
                if (getenv('HTTP_X_FORWARDED_FOR') && ip2long(getenv('HTTP_X_FORWARDED_FOR')) !== false) 
                {
                $ipadres = getenv('HTTP_X_FORWARDED_FOR'); 
                }
                elseif (getenv('HTTP_CLIENT_IP') && ip2long(getenv('HTTP_CLIENT_IP')) !== false) 
                {
                $ipadres = getenv('HTTP_CLIENT_IP');  
                }
                else 
                {
                $ipadres = getenv('REMOTE_ADDR'); 
                }
            }
        return $ipadres;
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
    $pf_index = $_SESSION["pfno"];
    $ipaddress = get_ip_address();
    db_prelude($con);  
    if($documentUploaded == TRUE)
        $query=mysqli_query($con,"insert into adms_loan_account_mstr(loan_acc_no,loan_status,document_status,folio_no,rack,no_of_files)  values ('$accountNumber','A','IN','$folioNumber','$rackNumber','1')");
    
    if($query)
    {
        // log update on successful parent table update
        $query=mysqli_query($con,"insert into admin_loan_activity_log(loan_acc_no,pf_index,status,ip_address) 
        values ('$accountNumber','$pf_index','InsertLoanDocuments','$ipaddress')");
        if($query) 
        return TRUE;
    else
        return FALSE;
}
    else
        return FALSE;
}

function UpdateRackDetails($accountNumber,$rackNumber)
{
    $con = NULL;
    db_prelude($con);  
    $pf_index = $_SESSION["pfno"];
    $ipaddress = get_ip_address();  
    $query=mysqli_query($con,"update adms_loan_account_mstr set  rack = '$rackNumber' where loan_acc_no = '$accountNumber'");
    
    if($query)
    {
        // log update on successful parent table update
        $query=mysqli_query($con,"insert into admin_loan_activity_log(loan_acc_no,pf_index,status,ip_address) 
        values ('$accountNumber','$pf_index','Rack Update','$ipaddress')");
        if($query) 
        return TRUE;
    else
        return FALSE;
}
    else
        return FALSE;
}
function UpdateFolioDetails($accountNumber,$folioNumber)
{
    $con = NULL;
    db_prelude($con);  
    $pf_index = $_SESSION["pfno"];
    $ipaddress = get_ip_address();
    $query=mysqli_query($con,"update adms_loan_account_mstr set folio_no = '$folioNumber' where loan_acc_no = '$accountNumber'");
    
    if($query)
    {
        // log update on successful parent table update
        $query=mysqli_query($con,"insert into admin_loan_activity_log(loan_acc_no,pf_index,status,ip_address) 
        values ('$accountNumber','$pf_index','Folio Update','$ipaddress')");
        if($query) 
        return TRUE;
    else
        return FALSE;
}
    else
        return FALSE;
}
function UpdateDocumentUploadDetails($accountNumber,$folioNumber)
{
    $con = NULL;
    db_prelude($con);  
    $pf_index = $_SESSION["pfno"];
    $ipaddress = get_ip_address(); 
    $query=mysqli_query($con,"update adms_loan_account_mstr set document_status='IN' , no_of_files='1' where loan_acc_no = '$accountNumber'");
    
    if($query)
    {
        // log update on successful parent table update
        $query=mysqli_query($con,"insert into admin_loan_activity_log(loan_acc_no,pf_index,status,ip_address) 
        values ('$accountNumber','$pf_index','Document Upload','$ipaddress')");
        if($query) 
        return TRUE;
        else 
        return FALSE;
    }
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
    mysqli_close($con);
	return ($row[0]);
}
function getRacpcNameofAccount($accountNumber)
{
	$con = NULL;
    db_prelude($con);
	$colname = "racpc_name"; 
    $query=mysqli_query($con,"SELECT r.racpc_name AS '$colname' FROM racpc_mstr as r where racpc_code=(select l.racpc_code FROM loan_account_mstr as l where  l.loan_acc_no ='$accountNumber')");
    $row=mysqli_fetch_array($query);
    mysqli_close($con);
	return ($row[0]);
}
function getRacpcCodeofAccount($accountNumber)
{
	$con = NULL;
    db_prelude($con);
	$colname = "racpc_code"; 
    $query=mysqli_query($con,"SELECT racpc_code AS '$colname' FROM loan_account_mstr  where  loan_acc_no ='$accountNumber'");
    $row=mysqli_fetch_array($query);
    mysqli_close($con);
	return ($row[0]);
}
function isValidAccount($accountNumber)
{
    $con = NULL;
    db_prelude($con);  
    $query=mysqli_query($con,"select loan_acc_no from loan_account_mstr where loan_acc_no = '$accountNumber'");
    $row = mysqli_fetch_array($query);
    mysqli_close($con);
    if($row['loan_acc_no'] != "")
    {
        return TRUE;
    }
    else
    {
        return FALSE;
    }
}

function isLoanAccountInADMS($accountNumber)
{
    $con = NULL;
    db_prelude($con);  
    $query=mysqli_query($con,"select loan_acc_no from adms_loan_account_mstr where loan_acc_no = '$accountNumber'");
    $row = mysqli_fetch_array($query);
    mysqli_close($con);
    if($row['loan_acc_no'] != "")
    {
        return TRUE;
    }
    else
    {
        return FALSE;
    }
}
function isLoanActive($accountNumber)
{
	$con = NULL;
    db_prelude($con);  
    $query=mysqli_query($con,"select loan_status from adms_loan_account_mstr where loan_acc_no = '$accountNumber'");
    $row = mysqli_fetch_array($query);
    mysqli_close($con);
    if($row['loan_status'] == "A")
    {
        return TRUE;
    }
    else
    {
        return FALSE;
    }
}
function GetDocumentStatusOfAccount($accountNumber)
{
    $con = NULL;
    db_prelude($con);  
    $colname = "doc_status";
    $query=mysqli_query($con,"SELECT document_status AS '$colname' FROM adms_loan_account_mstr WHERE loan_acc_no ='$accountNumber'");
    $row = mysqli_fetch_array($query);
	mysqli_close($con);
    if($row[$colname] != "")
    {
        return $row[$colname];
    }
    else
    {
        return FALSE;
    }
}
function GetRackNumberOfAccount($accountNumber)
{
    $con = NULL;
    db_prelude($con);  
    $colname = "rack_num";
    $query=mysqli_query($con,"SELECT rack AS '$colname' FROM adms_loan_account_mstr WHERE loan_acc_no ='$accountNumber'");
    $row = mysqli_fetch_array($query);
    mysqli_close($con);
    return ($row[0]);
}
function GetAccountNameOfAccount($accountNumber)
{
    $con = NULL;
    db_prelude($con);  
    $colname = "name";
    $query=mysqli_query($con,"SELECT acc_holder_name AS '$colname' FROM loan_account_mstr WHERE loan_acc_no = '$accountNumber'");
    $row = mysqli_fetch_array($query);
    mysqli_close($con);
    return ($row[0]);
}
function GetUserName($pfNumber)
{
    $con = NULL;
    db_prelude($con);  
    $colname = "UserName";
    $query=mysqli_query($con,"select emp_name as '$colname' from user_mstr where pf_index = '$pfNumber'");
    $row = mysqli_fetch_array($query);
    mysqli_close($con);
    return ($row[0]);
     
}

function OutUpdateDocStatus($accountNumber,$borrower_pf,$login_pf,$rson,$phno)
{
    $con = NULL;
    db_prelude($con);  
    
    $query=mysqli_query($con,"UPDATE adms_loan_account_mstr set document_status='OUT' where loan_acc_no ='$accountNumber' ");
    $rowcount=$query;
    $query=mysqli_query($con,"INSERT INTO document_activity_log (loan_acc_no,borrower_pf_index,docmgr_pf_index,slip_type,reason,phone_no)
	values ('$accountNumber','$borrower_pf','$login_pf','OUT','$rson','$phno')");
    mysqli_close($con);
    if ($rowcount > 0)
    {
       return TRUE;
    }
    else
    {
        return FALSE;
    }
     
}
// for doc mgr
function InUpdateDocStatus($accountNumber,$borrower_pf,$login_pf,$rson,$phno)
{
   $con = NULL;
    db_prelude($con);  
    
    
    $query=mysqli_query($con,"UPDATE adms_loan_account_mstr set document_status='IN' where loan_acc_no ='$accountNumber' ");
    $rowcount=$query;
    
    $query=mysqli_query($con,"INSERT INTO document_activity_log (loan_acc_no,borrower_pf_index,docmgr_pf_index,slip_type,reason,phone_no)
	values ('$accountNumber','$borrower_pf','$login_pf','IN','$rson','$phno')");
    mysqli_close($con); 
    if ($rowcount > 0)
    {
       return TRUE;
    }
    else
    {
        return FALSE;
    } 
}
?>
