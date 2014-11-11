<?php
ini_set('display_errors','On');
//var_dump("in info page");
error_reporting(E_ALL | E_STRICT);
session_start();
$request = $_POST['type'];

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
    case 'GetBranchCodeOfAccount':
    {
        GetBranchCodeOfAccount($_POST['accNo']);
        break;
    }
    case 'GetBranchNameOfAccount':
    {
        GetBranchNameOfAccount($_POST['accNo']);
        break;
    }
    case 'GetLoanProductOfAccount':
    {
        GetLoanProductOfAccount($_POST['accNo']);
        break;
    }
    case 'GetLoanStatusOfAccount':
    {
        GetLoanStatusOfAccount($_POST['accNo']);
        break;
    }
    case 'GetDocumentStatusOfAccount':
    {
        GetDocumentStatusOfAccount($_POST['accNo']);
        break;
    }
    case 'GetFolioNumberOfAccount':
    {
        GetFolioNumberOfAccount($_POST['accNo']);
        break;
    }
    case 'GetRackNumberOfAccount':
    {
        GetRackNumberOfAccount($_POST['accNo']);
        break;
    }
	case 'GetNoOfFilesForAccount':
    {
        GetNoOfFilesForAccount($_POST['accNo']);
        break;
    }
}

function db_prelude(&$con)
{
    $con = new mysqli("localhost", "root", "", "racpc_automation_db");
    if ($con->connect_errno) {
        die("Connection failed: " . $conn->connect_error);
    }
}
function isValidAccount($accountNumber)
{
    $con = NULL;
    db_prelude($con);  
    $query=mysqli_query($con,"select loan_acc_no from loan_account_mstr where loan_acc_no = '$accountNumber'");
    $row = mysqli_fetch_array($query);
    if($row['loan_acc_no'] != "")
    {
        echo json_encode(TRUE);
    }
    else
    {
        echo json_encode(FALSE);
    }
}

function isLoanAccountInADMS($accountNumber)
{
    $con = NULL;
    db_prelude($con);  
    $query=mysqli_query($con,"select loan_acc_no from adms_loan_account_mstr where loan_acc_no = '$accountNumber'");
    $row = mysqli_fetch_array($query);
    if($row['loan_acc_no'] != "")
    {
        echo json_encode(TRUE);
    }
    else
    {
        echo json_encode(FALSE);
    }
}

function GetAccountNameOfAccount($accountNumber)
{
    $con = NULL;
    db_prelude($con);  
    $colname = "name";
    $query=mysqli_query($con,"SELECT acc_holder_name AS '$colname' FROM loan_account_mstr WHERE loan_acc_no = '$accountNumber'");
    $row = mysqli_fetch_array($query);
    if($row[$colname] != "")
    {
        echo json_encode($row[$colname]);
    }
    else
    {
        echo json_encode(FALSE);
    }
}
function GetBranchCodeOfAccount($accountNumber)
{
    $con = NULL;
    db_prelude($con); 
    $colname = "branch_code"; 
    $query=mysqli_query($con,"SELECT branch_code AS '$colname' FROM loan_account_mstr WHERE loan_acc_no = '$accountNumber'");
    $row = mysqli_fetch_array($query);
    if($row[$colname] != "")
    {
        echo json_encode($row[$colname]);
    }
    else
    {
        echo json_encode(FALSE);
        //(FALSE);
    }
}

function GetBranchNameOfAccount($accountNumber)
{
    $con = NULL;
    db_prelude($con);  
    $colname = "branch_name";
    $query=mysqli_query($con,"SELECT b.branch_name AS '$colname' FROM loan_account_mstr l, branch_mstr b WHERE b.branch_code=l.branch_code and loan_acc_no= '$accountNumber'");
    $row = mysqli_fetch_array($query);
    if($row[$colname] != "")
    {
        echo json_encode($row[$colname]);
    }
    else
    {
        echo json_encode(FALSE);
    }
}
function GetLoanProductOfAccount($accountNumber)
{
    $con = NULL;
    db_prelude($con);  
    $colname = "loanprod_name";
    $query=mysqli_query($con,"SELECT product_name AS '$colname' FROM loan_account_mstr l, loan_product_mstr p WHERE p.product_code=l.product_code and loan_acc_no='$accountNumber'");
    $row = mysqli_fetch_array($query);
    if($row[$colname] != "")
    {
        echo json_encode($row[$colname]);
    }
    else
    {
        echo json_encode(FALSE);
    }
}

function GetLoanStatusOfAccount($accountNumber)
{
    $con = NULL;
    db_prelude($con);  
    $colname = "loan_status";
    $query=mysqli_query($con,"SELECT loan_status AS '$colname' FROM adms_loan_account_mstr WHERE loan_acc_no ='$accountNumber'");
    $row = mysqli_fetch_array($query);
    if($row[$colname] != "")
    {
        echo json_encode($row[$colname]);
    }
    else
    {
        echo json_encode(FALSE);
    }
}
function GetDocumentStatusOfAccount($accountNumber)
{
    $con = NULL;
    db_prelude($con);  
    $colname = "doc_status";
    $query=mysqli_query($con,"SELECT document_status AS '$colname' FROM adms_loan_account_mstr WHERE loan_acc_no ='$accountNumber'");
    $row = mysqli_fetch_array($query);
    if($row[$colname] != "")
    {
        echo json_encode($row[$colname]);
    }
    else
    {
        echo json_encode(FALSE);
    }
}
function GetFolioNumberOfAccount($accountNumber)
{
    $con = NULL;
    db_prelude($con);  
    $colname = "folio_num";
    $query=mysqli_query($con,"SELECT folio_no AS '$colname' FROM adms_loan_account_mstr WHERE loan_acc_no ='$accountNumber'");
    $row = mysqli_fetch_array($query);
    if($row[$colname] != "")
    {
        echo json_encode($row[$colname]);
    }
    else
    {
        echo json_encode(FALSE);
    }
}
function GetRackNumberOfAccount($accountNumber)
{
    $con = NULL;
    db_prelude($con);  
    $colname = "rack_num";
    $query=mysqli_query($con,"SELECT rack AS '$colname' FROM adms_loan_account_mstr WHERE loan_acc_no ='$accountNumber'");
    $row = mysqli_fetch_array($query);
    if($row[$colname] != "")
    {
        echo json_encode($row[$colname]);
    }
    else
    {
        echo json_encode(FALSE);
    }
}
function GetNoOfFilesForAccount($accountNumber)
{
    $con = NULL;
    db_prelude($con);  
    $colname = "files";
    $query=mysqli_query($con,"SELECT no_of_files AS '$colname' FROM adms_loan_account_mstr WHERE loan_acc_no ='$accountNumber'");
    $row = mysqli_fetch_array($query);
    if($row[$colname] != "")
    {
        echo json_encode($row[$colname]);
    }
    else
    {
        echo json_encode(FALSE);
    }
}
/*function getAccountDetails($accountNumber)
{
    $con = NULL;
    db_prelude($con);  
    $query=mysqli_query($con,"select password_hash,role from user where pf_index = '$accountNumber'");
    $row = mysqli_fetch_array($query);
    $role = $row['role'];
    echo json_encode($role);
}*/

function debug_to_console( $data ) {

    if ( is_array( $data ) )
        $output = "<script>console.log( 'Debug Objects: " . implode( ',', $data) . "' );</script>";
    else
        $output = "<script>console.log( 'Debug Objects: " . $data . "' );</script>";

    echo $output;
}

?>