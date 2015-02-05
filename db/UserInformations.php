<?php
ini_set('display_errors','On');
//var_dump("in info page");
error_reporting(E_ALL | E_STRICT);
session_start();
$request = $_POST['type'];

interface userStatusEnum
{
    const UserCreated = 0;
    const UserApproved = 1;
    const UserDisabled = 2;
    const UserActive = 4;
}
interface userRoleEnum
{
    const branchView = 0;
    const racpcView = 1;
    const racpcAdmin = 2;
    const racpcDocMgr = 4;
    const racpcUCO = 8;
}
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
    case 'isUserRoleBranchView':
    {
        GetRequiredUserRole($_POST['pfno'],userRoleEnum::branchView);
        break;
    }
    case 'isUserRoleRacpcView':
    {
        GetRequiredUserRole($_POST['pfno'],userRoleEnum::racpcView);
        break;
    }
    case 'isUserRoleDocumentManager':
    {
        GetRequiredUserRole($_POST['pfno'],userRoleEnum::racpcDocMgr);
        break;
    }
    case 'isUserRoleAdmin':
    {
        GetRequiredUserRole($_POST['pfno'],userRoleEnum::racpcAdmin);
        break;
    }
    case 'isUserRoleUCO':
    {
        GetRequiredUserRole($_POST['pfno'],userRoleEnum::racpcUCO);
        break;
    }
    case 'isUserActive':
    {
        GetRequiredUserStatus($_POST['pfno'],userStatusEnum::UserActive);
        break;
    }
    case 'isUserDisabled':
    {
        GetRequiredUserStatus($_POST['pfno'],userStatusEnum::UserDisabled);
        break;
    }
    case 'isUserApproved':
    {
        GetRequiredUserStatus($_POST['pfno'],userStatusEnum::UserApproved);
        break;
    }
    case 'isUserCreated':
    {
        GetRequiredUserStatus($_POST['pfno'],userStatusEnum::UserCreated);
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
   case 'checkOldPassword':
   {
       checkOldPassword($_POST['pwd']);
       break;
   }
   case 'updateNewPassword':
   {
       updateNewPassword($_POST['pwd']);
       break;
   }
   case 'getNewUserDetails':
   {
       getNewUserDetails();
       break;
   }
    
}
function getNewUserDetails()
{
  $con=NULL;
  db_prelude($con);
  $query=mysqli_query($con,"SELECT adms.pf_index,user.emp_name  from adms_user_mstr as adms, user_mstr as  user
                              where adms.pf_index= user.pf_index and adms.status_flag='C'");
  while($query_row=mysqli_fetch_assoc($query))
  {
    $users[]=$query_row;
  }
  echo json_encode($users);
}
function db_prelude(&$con)
{
    $con = new mysqli("localhost", "root", "", "racpc_automation_db");
    if ($con->connect_errno) {
        die("Connection failed: " . $conn->connect_error);
    }
}
function checkOldPassword($old)
{
    $con=NULL;
    db_prelude($con);
    $pfNumber=$_SESSION["pfno"];
    $query=mysqli_query($con,"select adms_password from adms_user_mstr where pf_index = '$pfNumber'");
    $row = mysqli_fetch_array($query);
    if($row["adms_password"]== md5($old))
    echo json_encode(TRUE);
    else
    echo json_encode(FALSE);
    mysqli_close($con);
}

function updateNewPassword($new)
{
    $con=NULL;
    db_prelude($con);
    $pfNumber=$_SESSION["pfno"];
    $newHash=md5($new);
    $udpate=mysqli_query($con,"update adms_user_mstr set adms_password='$newHash' where pf_index = '$pfNumber'");
	$rowcount=mysqli_affected_rows($con);
    if($rowcount>0)
    echo json_encode(TRUE);
    else
    echo json_encode(FALSE);
    mysqli_close($con);  
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
  /*  $query=mysqli_query($con,"select b.racpc_code as racpc_code
    from adms_user_mstr au, user_mstr u, branch_mstr b, user_mstr u1, branch_mstr b1
    where au.pf_index = '$login_pf'
    and au.pf_index = u.pf_index 
    and u.branch_code = b.branch_code
    and u1.pf_index = '$pfNumber'
    and u1.branch_code = b1.branch_code
    and b.racpc_code = b1.racpc_code");*/


    /*old query $query=mysqli_query($con,"select branch_code from user_mstr where pf_index='$login_pf'
                        and branch_code =(select racpc_code from branch_mstr
                        where branch_code=(select branch_code from user_mstr
                        where pf_index='$pfNumber')) ");*/

//the query below checks whether the  logged in user and the requesting branch user belongs to same racpc or the logged in user and the requesting racpc user have same branch code
    $query=mysqli_query($con,"SELECT branch_code from user_mstr
                              where pf_index='$login_pf' and (branch_code IN
                                (select distinct racpc_code from loan_account_mstr
                                 where branch_code=
                                    (select branch_code from user_mstr where pf_index='$pfNumber') ) ||
                                        (branch_code IN (select branch_code from user_mstr   where pf_index='$pfNumber')))");
    $row = mysqli_fetch_array($query);
    //var_dump("branch code: ".$row['branch_code']);
    if($row['branch_code'] != "")
    {
        echo json_encode(true);
        //echo json_encode($row['racpc_code']);
    }
    else
    {
        echo json_encode(false);
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

//********Since GetUserRacpcName/number are used to get the corresponding name/number of a racpc user,
// these functions are changed to work only with a racpc user pfID. DONT PASS A NON RACPC USER ID TO THESE FUNCTIONS ********
function GetUserRacpcName($pfNumber)
{
    $con = NULL;
    db_prelude($con);  
    $colname = "racpc_name";

    // $query=mysqli_query($con,"select racpc_name as '$colname' from racpc_mstr r, branch_mstr b, user_mstr u
    // where r.racpc_code = b.racpc_code
    // and b.branch_code = u.branch_code
    // and u.pf_index = '$pfNumber'");

    $query=mysqli_query($con,"SELECT racpc_name as '$colname' from racpc_mstr where racpc_code=
                                (select branch_code  from user_mstr where pf_index='$pfNumber')");
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

function GetUserRacpcCode($pfNumber)
{
    $con = NULL;
    db_prelude($con);  
    $colname = "racpc_code";

    // $query=mysqli_query($con,"select b.racpc_code as '$colname' from racpc_mstr r, branch_mstr b, user_mstr u
    // where r.racpc_code = b.racpc_code
    // and b.branch_code = u.branch_code
    // and u.pf_index = '$pfNumber'");
    $query=mysqli_query($con,"SELECT racpc_code as '$colname' from racpc_mstr where racpc_code=
    (select branch_code  from user_mstr where pf_index='$pfNumber')");
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
function GetRequiredUserStatus($pfNumber,$requiredStatus)
{
    $con = NULL;
    db_prelude($con);  
    $query=mysqli_query($con,"select status_flag from adms_user_mstr where pf_index = '$pfNumber'");
    $row = mysqli_fetch_array($query);
    $statusResult = FALSE;
    if(($requiredStatus == userStatusEnum::UserActive) && (strcmp($row['status_flag'],"A") == 0))
    {
        $statusResult = TRUE;
    }
    else if(($requiredStatus == userStatusEnum::UserDisabled) && (strcmp($row['status_flag'],"D") == 0))
    {
        $statusResult = TRUE;
    }
    else if(($requiredStatus == userStatusEnum::UserApproved) && (strcmp($row['status_flag'],"E") == 0))
    {
        $statusResult = TRUE;
    }
    else if(($requiredStatus == userStatusEnum::UserCreated) && (strcmp($row['status_flag'],"C") == 0))
    {
        $statusResult = TRUE;
    }
    echo json_encode($statusResult);
    mysqli_close($con);
}
function GetRequiredUserRole($pfNumber,$requiredRole)
{
    $con = NULL;
    db_prelude($con);  
    $query=mysqli_query($con,"select adms_role from adms_user_mstr where pf_index = '$pfNumber'");
    $row = mysqli_fetch_array($query);
    $statusResult = FALSE;
    if(($requiredRole == userRoleEnum::branchView) && (strcmp($row['adms_role'],"BRANCH_VIEW") == 0))
    {
        $statusResult = TRUE;
    }
    else if(($requiredRole == userRoleEnum::racpcView) && (strcmp($row['adms_role'],"RACPC_VIEW") == 0))
    {
        $statusResult = TRUE;
    }
    else if(($requiredRole == userRoleEnum::racpcAdmin) && (strcmp($row['adms_role'],"RACPC_ADMIN") == 0))
    {
        $statusResult = TRUE;
    }
    else if(($requiredRole == userRoleEnum::racpcDocMgr) && (strcmp($row['adms_role'],"RACPC_DM") == 0))
    {
        $statusResult = TRUE;
    }
    else if(($requiredRole == userRoleEnum::racpcUCO) && (strcmp($row['adms_role'],"RACPC_UCO") == 0))
    {
        $statusResult = TRUE;
    }
    echo json_encode($statusResult);
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
