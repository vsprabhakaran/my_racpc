<html>
    <head>
        <link rel="stylesheet" href="../css/pure-min.css">
         <style type="text/css">
          p {
           font-family: 'Trebuchet MS';
           font-size: large; 
          }
            .button-success,
            .button-error,
            .button-warning,
            .button-secondary {
                color: white;
                border-radius: 4px;
                text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
            }

            .button-success {
                background: rgb(28, 184, 65); /* this is a green */
            }

            .button-error {
                background: rgb(202, 60, 60); /* this is a maroon */
            }

            .button-warning {
                background: rgb(223, 117, 20); /* this is an orange */
            }

            .button-secondary {
                background: rgb(66, 184, 221); /* this is a light blue */
            }
          </style>
        <script type="text/javascript">
            function goBack() {
                window.location = "manageUsers.php";
            }
        </script>
    </head>
<body>
    <br/><br/>
<?php
    if(!isset($_SESSION)) session_start();
    require_once("../db/userQueries.php");
    
    $actionType = $_POST['actionType'];
    if(strcmp($actionType,"UserCreationForm") == 0)
    {
        $pfNumber = $_POST['cpfid'];
        $accountCreated = FALSE;
        if(isValidUser($pfNumber) && !isValidADMSUser($pfNumber))
        {
            if(isUserRacpcUser($pfNumber))
                $accountCreated = InsertNewRacpcViewUser($pfNumber);
            else
                $accountCreated = InsertNewBranchViewUser($pfNumber);
        }
        ($accountCreated)?(printSuccessPage("New User Created")):(printErrorPage("Error creating new user!!!"));
    }
    else if(strcmp($actionType,"UserResetForm") == 0)
    {
        $pfNumber = $_POST['rpfid'];
        $userResetDone = FALSE;
        if(isValidUser($pfNumber) && isValidADMSUser($pfNumber) && GetRequiredUserStatus($pfNumber,'isUserActive') && (GetRequiredUserRole($pfNumber,'isUserRoleBranchView') || GetRequiredUserRole($pfNumber,'isUserRoleRacpcView')))
        {
            $userResetDone = ResetADMSUserPassword($pfNumber);
        }
        ($userResetDone)?(printSuccessPage("Password reset succesful")):(printErrorPage("Error resetting user password!!!"));
    }
    else if(strcmp($actionType,"UserDisableForm") == 0)
    {
        $pfNumber = $_POST['dpfid'];
        $TODO = $_POST['EnableOrDisable'];
        $userDisableOrEnableDone = FALSE;
        //Basic validation
        //echo "Branch ".GetRequiredUserRole($pfNumber,'isUserRoleBranchView');
        //echo "Racpc ".GetRequiredUserRole($pfNumber,'isUserRoleRacpcView');
       
        $validationToProceed = (isValidUser($pfNumber) && isValidADMSUser($pfNumber) && (GetRequiredUserRole($pfNumber,'isUserRoleBranchView') || GetRequiredUserRole($pfNumber,'isUserRoleRacpcView')));
        //Checking if the user has to be enabled, he should be disabled.
        $validationToProceed  &=  (strcmp($TODO,"Enable") == 0)?GetRequiredUserStatus($pfNumber,'isUserDisabled'):TRUE;
        //When the user is disabled, the password is automatically reset.
        if($validationToProceed)
        {
            if((strcmp($TODO,"Disable") == 0))
                $userDisableOrEnableDone = DisableADMSUser($pfNumber);
            elseif ((strcmp($TODO,"Enable") == 0))
            {
                ResetADMSUserPassword($pfNumber);
                $userDisableOrEnableDone = EnableADMSUser($pfNumber);
            }
        }
        ($userDisableOrEnableDone)?(printSuccessPage("User $TODO succesful")):(printErrorPage("Error in user $TODO!!!"));
    }
      
function printSuccessPage($message)
{
    printf('<div class="pure-controls"><p>%s</p><button class="button-success pure-button" id="backButton1" name="backButton1" onclick="goBack()">Back</button>', $message);
}
function printErrorPage($message)
{
    printf('<div class="pure-controls"><p>%s</p><button class="button-error pure-button" id="backButton2" name="backButton2" onclick="goBack()">Back</button>', $message);
}
?>
</body>
</html>
