<?php
ini_set('display_errors','On');

error_reporting(E_ALL | E_STRICT);
session_start();
//$q2 = mysqli_real_escape_string($con, $_POST['type']);
$request = $_POST['type'];

switch($request)
{
    case 'isValidAccount':
    {
        //var_dump($_POST['accNo']);
        isValidAccount($_POST['accNo']);
        break;
    }
    case 'GetNameOfAccount':
    {
        
        GetNameOfAccount($_POST['accNo']);
        break;
    }

    case 'GetBrCode':
    {
     GetBrCode($_POST['accNo']);
     break;  
    }

     case 'GetFolio':
    {
     GetFolio($_POST['accNo']);
     break;  
    }
     case 'GetBranchName':
    {
     GetBranchName($_POST['accNo']);
     break;  
    }
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

//$accountNumber = mysqli_real_escape_string($con, $_GET['q']);
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
function GetNameOfAccount($accountNumber)
{
    $con = NULL;
    db_prelude($con);  
    $query=mysqli_query($con,"SELECT acc_holder_name AS name FROM loan_account_mstr WHERE loan_acc_no = '$accountNumber'");
    $row = mysqli_fetch_array($query);
    if($row['name'] != "")
    {
        echo json_encode($row['name']);
    }
    else
    {
        echo json_encode("");
            }
}

function GetBrCode($accountNumber)
{
    $con = NULL;
    db_prelude($con);  
    $query=mysqli_query($con,"SELECT branch_code AS brcode FROM loan_account_mstr WHERE loan_acc_no = '$accountNumber'");
    $row = mysqli_fetch_array($query);
    if($row['brcode'] != "")
    {
        echo json_encode($row['brcode']);
    }
    else
    {
        echo json_encode("");
            }
}

function GetFolio($accountNumber)
{
    $con = NULL;
    db_prelude($con);  
    $query=mysqli_query($con,"SELECT folio_no AS folio FROM loan_account_mstr WHERE loan_acc_no = '$accountNumber'");

    //$q2 = mysqli_real_escape_string($con, $_GET['q']);

    $row = mysqli_fetch_array($query);
    if($row['folio'] != "")
    {
        //$q2 = mysqli_real_escape_string($con,$row['folio']);
        //alert($q2);
        //json_encode($q2);
        echo json_encode($row['folio']);
    }
    else
    {
        echo json_encode("");
            }
}

function GetBranchName($accountNumber)
{
    $con = NULL;
    db_prelude($con);  
    $query=mysqli_query($con,"SELECT b.branch_name AS brname FROM branch_mstr b, loan_account_mstr l
    WHERE l.branch_code = b.branch_code and l.loan_acc_no = '$accountNumber'");
        
    $row = mysqli_fetch_array($query);
    if($row['brname'] != "")
    {
        
        echo json_encode($row['brname']);
    }
    else
    {
        echo json_encode("");
            }
}

function OutUpdateDocStatus($accountNumber)
{
    var_dump("updatestat");
    $con = NULL;
    db_prelude($con);  
    //var_dump("karthik");
    
    $query=mysqli_query($con,"UPDATE adms_loan_account_mstr set document_status='OUT' where loan_acc_no ='$accountNumber' ");
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
    //var_dump("karthik");
    
    $query=mysqli_query($con,"UPDATE adms_loan_account_mstr set document_status='IN' where loan_acc_no ='$accountNumber' ");
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