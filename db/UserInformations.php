<?php
ini_set('display_errors','On');
//var_dump("in info page");
error_reporting(E_ALL | E_STRICT);
session_start();
$request = $_POST['type'];

switch($request)
{
    case 'isValidUser':
    {
        isValidUser($_POST['pfno']);
        break;
    }
    case 'isValidADMSUser':
    {
        isValidADMSUser($_POST['pfno']);
        break;
    }
    case 'GetUserName':
    {
        GetUserName($_POST['pfno']);
        break;
    }
    case 'GetUserRole':
    {
        GetUserRole($_POST['pfno']);
        break;
    }
    case 'GetUserBranchCode':
    {
        GetUserBranchCode($_POST['pfno']);
        break;
    }
    case 'GetUserBranchName':
    {
        GetUserBranchName($_POST['pfno']);
        break;
    }
    case 'GetUserStatus':
    {
        //GetUserStatus($_POST['pfno']);
        break;
    }
    case 'GetUserPhone':
    {
        GetUserPhone($_POST['pfno']);
        break;
    }
    case 'GetUserEmail':
    {
        GetUserEmail($_POST['pfno']);
        break;
    }
    case 'GetADMSUserRole':
    {
        GetADMSUserRole($_POST['pfno']);
        break;
    }
    case 'GetUserRacpcName':
    {
        GetUserRacpcName($_POST['pfno']);    
        break;
   }
   case 'validate_racpc_user':
   {
       validate_racpc_user($_POST['pfno'],$_POST['login_pfno']);
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
function isValidUser($pfNumber)
{
    $con = NULL;
    db_prelude($con);  
    $query=mysqli_query($con,"select pf_index from user_mstr where pf_index = '$pfNumber'");
    $row = mysqli_fetch_array($query);
    if($row['pf_index'] != "")
    {
        echo json_encode(TRUE);
    }
    else
    {
        echo json_encode(FALSE);
    }
     mysqli_close($con);
}

function isValidADMSUser($pfNumber)
{
    $con = NULL;
    db_prelude($con);  
    $query=mysqli_query($con,"select pf_index from adms_user_mstr where pf_index = '$pfNumber'");
    $row = mysqli_fetch_array($query);
    if($row['pf_index'] != "")
    {
        echo json_encode(TRUE);
    }
    else
    {
        echo json_encode(FALSE);
    }
    mysqli_close($con);
}
// validate racpc of two users
function validate_racpc_user($pfNumber,$login_pf)
{
    $con = NULL;
    db_prelude($con);  
    //$colname = "racpc_code";
    $query=mysqli_query($con,"select b.racpc_code as racpc_code
    from adms_user_mstr au, user_mstr u, branch_mstr b, user_mstr u1, branch_mstr b1
    where au.pf_index = '$login_pf'
    and au.pf_index = u.pf_index 
    and u.branch_code = b.branch_code
    and u1.pf_index = '$pfNumber'
    and u1.branch_code = b1.branch_code
    and b.racpc_code = b1.racpc_code");

    $row = mysqli_fetch_array($query);
    if($row['racpc_code'] != "")
    {
        echo json_encode(TRUE);
        //echo json_encode($row['racpc_code']);
    }
    else
    {
        echo json_encode(FALSE);
    }
    mysqli_close($con);
}
function GetUserName($pfNumber)
{
    $con = NULL;
    db_prelude($con);  
    $colname = "UserName";
    $query=mysqli_query($con,"select emp_name as '$colname' from user_mstr where pf_index = '$pfNumber'");
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
function GetADMSUserRole($pfNumber)
{
    $con = NULL;
    db_prelude($con); 
    $colname = "UserRole"; 
    $query=mysqli_query($con,"select adms_role as '$colname' from adms_user_mstr where pf_index  = '$pfNumber'");
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

function GetUserRole($pfNumber)
{
    $con = NULL;
    db_prelude($con); 
    $colname = "branch_role"; 
    $query=mysqli_query($con,"select ur.description as '$colname' from user_mstr u, user_role_mstr ur where pf_index  = '$pfNumber' and u.branch_role = ur.role");
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


function GetUserBranchCode($pfNumber)
{
    $con = NULL;
    db_prelude($con);  
    $colname = "branchcode";
    $query=mysqli_query($con,"select branch_code as '$colname' from user_mstr where pf_index = '$pfNumber'");
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

function GetUserRacpcName($pfNumber)
{
    $con = NULL;
    db_prelude($con);  
    $colname = "racpc_name";

    $query=mysqli_query($con,"select racpc_name as '$colname' from racpc_mstr r, branch_mstr b, user_mstr u
    where r.racpc_code = b.racpc_code 
    and b.branch_code = u.branch_code
    and u.pf_index = '$pfNumber'");

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

function GetUserBranchName($pfNumber)
{
    $con = NULL;
    db_prelude($con);  
    $colname = "branchname";
    $query=mysqli_query($con,"select branch_name as '$colname' from user_mstr u, branch_mstr b where b.branch_code = u.branch_code and pf_index = '$pfNumber'");
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

function GetUserPhone($pfNumber)
{
    $con = NULL;
    db_prelude($con);  
    $colname = "phone_no";
    $query=mysqli_query($con,"select phone_no as '$colname' from user_mstr where pf_index = '$pfNumber'");
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
function GetUserEmail($pfNumber)
{
    $con = NULL;
    db_prelude($con);  
    $colname = "branchcode";
    $query=mysqli_query($con,"select email as '$colname' from user_mstr where pf_index = '$pfNumber'");
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
/*function getAccountDetails($pfNumber)
{
    $con = NULL;
    db_prelude($con);  
    $query=mysqli_query($con,"select password_hash,role from user_mstr where pf_index = '$pfNumber'");
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