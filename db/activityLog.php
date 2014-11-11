<?php
ini_set('display_errors','On');
//var_dump("in info page");
error_reporting(E_ALL | E_STRICT);
session_start();
$request = $_POST['type'];

switch($request)
{
    case 'OutActivityLogInsert';
	{
	OutActivityLogInsert($_POST['accNo'],$_POST['borrower'],$_POST['doc_mgr'],$_POST['entered_slip'],$_POST['entered_reason'],$_POST['entered_phone']);
	break;
	}
    case 'InActivityLogInsert';
	{
	InActivityLogInsert($_POST['accNo'],$_POST['borrower'],$_POST['doc_mgr'],$_POST['entered_slip'],$_POST['entered_reason'],$_POST['entered_phone']);
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
    mysqli_close($con);
}
function InActivityLogInsert($accountNumber,$borrower_pf,$login_pf,$s_type,$rson,$phno)
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
    mysqli_close($con);
}
function debug_to_console( $data ) {

    if ( is_array( $data ) )
        $output = "<script>console.log( 'Debug Objects: " . implode( ',', $data) . "' );</script>";
    else
        $output = "<script>console.log( 'Debug Objects: " . $data . "' );</script>";

    echo $output;
}

?>