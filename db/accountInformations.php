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
	case 'OutUpdateDocStatus':
    {
     OutUpdateDocStatus($_POST['accNo']);
     break;  
    }
    case 'InUpdateDocStatus':
    {
    InUpdateDocStatus($_POST['accNo']);
	break;
    }
    case 'isValidAdmsAccount':
    {
    isValidAdmsAccount($_POST['accNo'],$_POST['login_pf_index']);
	break;        
    }
	case 'checkBranchViewAccess':
	{
	checkBranchViewAccess($_POST['accNo'],$_POST['pfno']);	
	break;
	}
	case 'checkRacpcViewAccess':
	{
	checkRacpcViewAccess($_POST['accNo'],$_POST['pfno']);	
	break;
	}
	case 'isLoanActive':
	{
		isLoanActive($_POST['accNo']);
		break;
	}
	case 'validate_racpc_acc_user':
    {
    validate_racpc_acc_user($_POST['pfno'],$_POST['accNo']);
    break;    
    }
	case 'isValidForOutSlip':
	{
	isValidForOutSlip($_POST['accNo']);
	break;
	}
	case 'isValidForInSlip':
	{
	isValidForInSlip($_POST['accNo']);
	break;
	}
    case 'ViewReport':
    {
    ViewReport($_POST['login_pf_index']);
    break;
    }
}
}

function db_prelude(&$con)
{
    $con = new mysqli("localhost", "root", "", "racpc_automation_db");
    if ($con->connect_errno) {
        die("Connection failed: " . $conn->connect_error);
    }
}
function checkBranchViewAccess($accountNumber,$pfno)
{
	$con = NULL;
    db_prelude($con);  
    $query=mysqli_query($con,"select l.branch_code from loan_account_mstr as l where l.loan_acc_no='$accountNumber' and  (l.branch_code= (select u.branch_code from user_mstr as u where pf_index='$pfno'))");
    $row = mysqli_fetch_array($query);
	if($row['branch_code'] != "")
    {
        echo json_encode("BRANCH_VIEW_GRANTED");
    }
    else
    {
        echo json_encode(FALSE);
    }
     mysqli_close($con);
}
function isValidForOutSlip($accountNumber)
{
	$con = NULL;
    db_prelude($con);  
    $query=mysqli_query($con,"select loan_acc_no from adms_loan_account_mstr where loan_acc_no = '$accountNumber' and 
	document_status NOT IN('OUT','A','C') and loan_status != 'C' ");
    $row = mysqli_fetch_array($query);
	if($row['loan_acc_no'] != "")
    {
        echo json_encode(TRUE);
    }
    else
    {
        echo json_encode(FALSE);
    }
     mysqli_close($con);
}

function isValidForInSlip($accountNumber)
{
	$con = NULL;
    db_prelude($con);  
    $query=mysqli_query($con,"select loan_acc_no from adms_loan_account_mstr where loan_acc_no = '$accountNumber' and 
	document_status NOT IN('IN','A','C') and loan_status != 'C' ");
    $row = mysqli_fetch_array($query);
	if($row['loan_acc_no'] != "")
    {
        echo json_encode(TRUE);
    }
    else
    {
        echo json_encode(FALSE);
    }
     mysqli_close($con);
}

function checkRacpcViewAccess($accountNumber,$pfno)
{
	$con = NULL;
    db_prelude($con);  
	$query=mysqli_query($con,"SELECT racpc_code
										FROM loan_account_mstr AS l, branch_mstr AS b
										WHERE l.branch_code = b.branch_code AND l.loan_acc_no ='$accountNumber'");
	$racpcCodeofAcc=mysqli_fetch_array($query);
    $query=mysqli_query($con,"SELECT branch_code
								FROM user_mstr
								WHERE pf_index ='$pfno'");
    $branchCodeofUser = mysqli_fetch_array($query);
	//echo json_encode($racpcCodeofAcc['racpc_code']." ".$branchCodeofUser['branch_code']);
	//if($racpcCodeofAcc['racpc_code'] != "" && $branchCodeofUser['branch_code'] !="" && $racpcCodeofAcc['racpc_code'] == $branchCodeofUser['branch_code'])
	if(strcmp($racpcCodeofAcc['racpc_code'] , $branchCodeofUser['branch_code'])==0)
    {
        echo json_encode("RACPC_VIEW_GRANTED");
    }
    else
    {
		
        echo json_encode(FALSE);
    }
     mysqli_close($con);
}
function isLoanActive($accountNumber)
{
	$con = NULL;
    db_prelude($con);  
    $query=mysqli_query($con,"select loan_status from adms_loan_account_mstr where loan_acc_no = '$accountNumber'");
    $row = mysqli_fetch_array($query);
    if($row['loan_status'] == "A")
    {
        echo json_encode(TRUE);
    }
    else
    {
        echo json_encode(FALSE);
    }
     mysqli_close($con);
}
//validate branch code and racpc of account number and the doc requester
function validate_racpc_acc_user($pfno, $accountNumber){
    $con = NULL;
    db_prelude($con);  

    $query=mysqli_query($con,"select b.racpc_code as racpc_code
    from user_mstr u, branch_mstr b, adms_loan_account_mstr am, loan_account_mstr l, branch_mstr b1
    where u.pf_index = '$pfno'
    and am.loan_acc_no = '$accountNumber' 
    and am.loan_acc_no = l.loan_acc_no 
    and l.branch_code = b1.branch_code
    and u.branch_code = b.branch_code
    and b.branch_code = b1.branch_code
    and b.racpc_code = b1.racpc_code");

    $row = mysqli_fetch_array($query);
    if($row['racpc_code'] != "")
    {
         echo json_encode(TRUE);
    }
    else
    {
        echo json_encode(FALSE);
    }
     mysqli_close($con);
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
     mysqli_close($con);
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
	mysqli_close($con);
}
// checking whether account belongs corresponding adms user 
function isValidAdmsAccount($accountNumber,$login_pf_index)
{
    $con = NULL;
    db_prelude($con);  
    $query=mysqli_query($con,"select l.loan_acc_no
from loan_account_mstr l,branch_mstr b,racpc_mstr r,adms_loan_account_mstr am
where l.loan_acc_no='$accountNumber' 
and l.loan_acc_no = am.loan_acc_no
and  l.branch_code =  b.branch_code
and b.racpc_code = r.racpc_code 
and b.racpc_code in
( select b1.racpc_code from user_mstr u, branch_mstr b1 
  where u.pf_index = '$login_pf_index' and u.branch_code = b1.branch_code)");
    $row = mysqli_fetch_array($query);
    if($row['loan_acc_no'] != "")
    {
        echo json_encode(TRUE);
    }
    else
    {
        echo json_encode(FALSE);
    }
     mysqli_close($con);   
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
     mysqli_close($con);
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
     mysqli_close($con);
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
     mysqli_close($con);
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
     mysqli_close($con);
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
     mysqli_close($con);
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
     mysqli_close($con);
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
     mysqli_close($con);
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
     mysqli_close($con);
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
     mysqli_close($con);
}

function OutUpdateDocStatus($accountNumber)
{
   
    $con = NULL;
    db_prelude($con);  
   
    
    $query=mysqli_query($con,"UPDATE adms_loan_account_mstr set document_status='OUT' where loan_acc_no ='$accountNumber' ");
    $rowcount=mysqli_num_rows($query);
   
    if ($rowcount > 0)
    {
       echo json_encode(TRUE);
    }
    else
    {
    echo json_encode(FALSE);
    }
     mysqli_close($con);
}

function InUpdateDocStatus($accountNumber)
{
   $con = NULL;
    db_prelude($con);  
    
    
    $query=mysqli_query($con,"UPDATE adms_loan_account_mstr set document_status='IN' where loan_acc_no ='$accountNumber' ");
    $rowcount=mysqli_num_rows($query);
    
    if ($rowcount > 0)
    {
       echo json_encode(TRUE);
    }
    else
    {
    echo json_encode(FALSE);
    } 
     mysqli_close($con); 
}
function ViewReport($login_pf_index)
{
    $con = NULL;
    db_prelude($con);  
    $colname = "borrower";
    $query=mysqli_query($con,"SELECT MAX( dl.timestamp ) as '$colname' 
FROM adms_loan_account_mstr am, loan_account_mstr l, document_activity_log dl
WHERE am.document_status =  'OUT'
AND am.loan_status =  'A'
AND am.loan_acc_no = l.loan_acc_no
AND l.loan_acc_no = dl.loan_acc_no
AND dl.slip_type =  'OUT' 
AND dl.docmgr_pf_index = '$login_pf_index' ");
    
    $row = mysqli_fetch_array($query);

    if ( $row[$colname] != '')
    {
       //echo $row[$time]; echo $row[$account]; 
       //echo "hello";
       //echo $row[$colname];
       //echo json_encode(TRUE);
       echo json_encode(hello);
    }
    else
    {
        echo "fail";
    echo json_encode(FALSE);
    } 
     mysqli_close($con); 
}

/*
function OutActivityLogInsert($accountNumber,$borrower_pf,$login_pf,$s_type,$rson,$phno)
{
   $con = NULL;
    db_prelude($con);  
    $query=mysqli_query($con,"INSERT INTO document_activity_log (loan_acc_no,borrower_pf_index,docmgr_pf_index,slip_type,reason,phone_no)
	values ('$accountNumber','$borrower_pf','$login_pf','$s_type','$rson','$phno')");
    //$rowcount=mysqli_num_rows($query);
    //var_dump($rowcount);
    if ($query)
    {
       echo json_encode(TRUE);
    }
    else
    {
    echo json_encode(FALSE);
    }  

}
function getAccountDetails($accountNumber)
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