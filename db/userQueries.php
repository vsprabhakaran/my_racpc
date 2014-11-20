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

function ResetADMSUserPassword($pfNumber)
{
    $con = NULL;
    db_prelude($con);  
    $query=mysqli_query($con,"update adms_user_mstr set adms_password = MD5('12345'), status_flag = 'E' where pf_index =  '$pfNumber'");
    
    if($query)
        return TRUE;
    else
        return FALSE;
}
function DisableADMSUser($pfNumber)
{
    $con = NULL;
    db_prelude($con);  
    $query=mysqli_query($con,"update adms_user_mstr set status_flag = 'D' where pf_index =  '$pfNumber'");
    
    if($query)
        return TRUE;
    else
        return FALSE;
}
function EnableADMSUser($pfNumber)
{
    $con = NULL;
    db_prelude($con);  
    $query=mysqli_query($con,"update adms_user_mstr set status_flag = 'E' where pf_index =  '$pfNumber'");
    
    if($query)
        return TRUE;
    else
        return FALSE;
}
function InsertNewBranchViewUser($pfNumber)
{
    $con = NULL;
    db_prelude($con);  
    $query=mysqli_query($con,"insert into adms_user_mstr(pf_index ,adms_password ,adms_role ,status_flag)  values ('$pfNumber',MD5('12345'),'BRANCH_VIEW','C')");
    
    if($query)
        return TRUE;
    else
        return FALSE;
}
function InsertNewRacpcViewUser($pfNumber)
{
    $con = NULL;
    db_prelude($con);  
    $query=mysqli_query($con,"insert into adms_user_mstr(pf_index ,adms_password ,adms_role ,status_flag)  values ('$pfNumber',MD5('12345'),'RACPC_VIEW','C')");
    
    if($query)
        return TRUE;
    else
        return FALSE;
}
function isValidUser($pfNumber)
{
    $con = NULL;
    db_prelude($con);  
    $query=mysqli_query($con,"select pf_index from user_mstr where pf_index = '$pfNumber'");
    $row = mysqli_fetch_array($query);
    if($row['pf_index'] != "")
    {
        return TRUE;
    }
    else
    {
         return FALSE;
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
        return TRUE;
    }
    else
    {
        return FALSE;
    }
    mysqli_close($con);
}
function GetRequiredUserRole($pfNumber,$requiredRole)
{
    $con = NULL;
    db_prelude($con);  
    $query=mysqli_query($con,"select adms_role from adms_user_mstr where pf_index = '$pfNumber'");
    $row = mysqli_fetch_array($query);
    $statusResult = FALSE;
    if((strcmp($requiredRole, "isUserRoleBranchView") == 0) && (strcmp($row['adms_role'],"BRANCH_VIEW") == 0))
    {
        $statusResult = TRUE;
    }
    else if((strcmp($requiredRole, "isUserRoleRacpcView") == 0) && (strcmp($row['adms_role'],"RACPC_VIEW") == 0))
    {
        $statusResult = TRUE;
    }
    else if((strcmp($requiredRole, 'isUserRoleAdmin') == 0) && (strcmp($row['adms_role'],"RACPC_ADMIN") == 0))
    {
        $statusResult = TRUE;
    }
    else if((strcmp($requiredRole, 'isUserRoleDocumentManager') == 0) && (strcmp($row['adms_role'],"RACPC_DM") == 0))
    {
        $statusResult = TRUE;
    }
    else if((strcmp($requiredRole, 'isUserRoleUCO') == 0) && (strcmp($row['adms_role'],"RACPC_UCO") == 0))
    {
        $statusResult = TRUE;
    }
    mysqli_close($con);
    return $statusResult;
}
function GetRequiredUserStatus($pfNumber,$requiredStatus)
{
    $con = NULL;
    db_prelude($con);  
    $query=mysqli_query($con,"select status_flag from adms_user_mstr where pf_index = '$pfNumber'");
    $row = mysqli_fetch_array($query);
    $statusResult = FALSE;
    if((strcmp($requiredStatus, 'isUserActive') == 0) && (strcmp($row['status_flag'],"A") == 0))
    {
        $statusResult = TRUE;
    }
    else if((strcmp($requiredStatus, 'isUserDisabled') == 0) && (strcmp($row['status_flag'],"D") == 0))
    {
        $statusResult = TRUE;
    }
    else if((strcmp($requiredStatus, 'isUserApproved') == 0) && (strcmp($row['status_flag'],"E") == 0))
    {
        $statusResult = TRUE;
    }
    else if((strcmp($requiredStatus, 'isUserCreated') == 0) && (strcmp($row['status_flag'],"C") == 0))
    {
        $statusResult = TRUE;
    }
    mysqli_close($con);
    return $statusResult;
}
?>