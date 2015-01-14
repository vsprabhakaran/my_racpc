<?php
        session_start();
        if( $_SESSION["role"] != "RACPC_ADMIN")
        {
           $_SESSION["role"] = "";
		   $_SESSION["pfno"] = "";
        ?><meta http-equiv="refresh" content="0;URL=../login.php"><?php
        }
		else
		{
    ?>
<!doctype html>

<html lang=''>
<head>
   <link rel="stylesheet" href="../css/my_styles.css">
   <link rel="stylesheet" href="../css/pure-min.css">
	<script src="../tab-content/tabcontent.js" type="text/javascript"></script>
    <link href="../tab-content/template6/tabcontent.css" rel="stylesheet" type="text/css" />
    <script src="../jquery-latest.min.js" type="text/javascript"></script>
    <script type="text/javascript">
        var dbURL = "../db/UserInformations.php";
        var isPfNumberValid = false;
        function requestPFNumber_BooleanFor(callType, pfNumber) {
            var result = doPOST_Request(dbURL, pfNumber, callType);
            if (result == "true") return true;
            else false;
        }
        
        function PFNumberEnterred(thisObj) {
            pfNumber = $(thisObj).val();
            var className = $(thisObj).attr('class');
            resetForm(thisObj);
            isPfNumberValid = false;
            if (pfNumber == "") { nullPFNumberEnterred(thisObj); return; }
            /*Validations done when user pf number is enterrred
            1. is the user pf number is a proper one.
            2. does the user belong to the racpc that current admin is working on i.e, the RACPC ADMIN can manage users who are all under his racpc/branch.
            3. when pf no is enterred in user creation form, it should validate whether the user is already an ADMS user.
            4. When a user is disabled, he should not appear for user creation or reset.
            5. when user is reset/enabled/disabled, he should be already a memeber of ADMS.
            6. when user is disabled reset the password.
            7. --Admin cannot change access to his account.--
            8. Only active users can be reset.
            9. when the pfNumber is entered for enable/disable user, check his status and change the button text accordingly.
            10. admin can reset and disable only branch view users and racpc view users
            */
            //Validation 1:
            if (!requestPFNumber_BooleanFor("isValidUser", pfNumber)) {
                invalidPFNumberEnterred(thisObj);
                throwError(thisObj, "Invalid PF Number Enterred.");
                return;
        }
            var userRacpcNumber = doPOST_Request(dbURL, pfNumber, 'GetUserRacpcName');
            var adminPFNumber = doPOST_Request('../getPfnoFromSession.php', "", "getPfno");

            //validation 2:
            var adminRacpcNumber = doPOST_Request(dbURL, adminPFNumber, 'GetUserRacpcName');
            if (adminRacpcNumber != userRacpcNumber) {
                invalidPFNumberEnterred(thisObj);
                throwError(thisObj, " Admin cannot modify users belong to different RACPC.");
                return;
        }
            //validation 3:
            if (className == "createUser" && requestPFNumber_BooleanFor("isValidADMSUser", pfNumber)) {
                invalidPFNumberEnterred(thisObj);
                throwError(thisObj, "User already exists in ADMS!!!");
                return;
            }
            //validation 5:
            if (className != "createUser") {
                if (!requestPFNumber_BooleanFor("isValidADMSUser", pfNumber)) {
                    invalidPFNumberEnterred(thisObj);
                    throwError(thisObj, "User does not exists in ADMS!!!");
                    return;
                }
                //validation 10:
                else if (!(requestPFNumber_BooleanFor("isUserRoleBranchView", pfNumber) || requestPFNumber_BooleanFor("isUserRoleRacpcView", pfNumber))) {
                    invalidPFNumberEnterred(thisObj);
                    throwError(thisObj, "This account cannot be modified!!!");
                    return;
        }
            }
            //validation 4:
            var isUserDisabled = doPOST_Request(dbURL, pfNumber, 'isUserDisabled');
            if (className != "disableUser" && (isUserDisabled == "true")) {
                invalidPFNumberEnterred(thisObj);
                throwError(thisObj, 'The user is disabled!!!');
                return;
            }
            //validation 8:
            var isUserActive = doPOST_Request(dbURL, pfNumber, 'isUserActive');
            if (className == "resetUser" && (isUserActive != "true")) {
                invalidPFNumberEnterred(thisObj);
                throwError(thisObj, 'The user is not active to reset password!!!');
                return;
            }
            //validation 9:
            if (className == "disableUser") {
                if (isUserDisabled == "true") {
                    $("." + className + " :button").text('Enable User');
                    $("#EnableOrDisable").val("Enable");
                }
                else {
                    $("." + className + " :button").text('Disable User');
                    $("#EnableOrDisable").val("Disable");
                }
            }
            //validation success:
            validPFNumberEnterred(thisObj);
        }
        function nullPFNumberEnterred(thisObj) {
            $(thisObj).css("background-color", "");
        }
        function validPFNumberEnterred(thisObj) {
            $(thisObj).css("background-color", "#CCFFCC");
            var className = $(thisObj).attr('class');
            $("." + className + " a").css('visibility', 'visible');
            //$("." + className + " select").prop('disabled', false);
            $("." + className + " :button").prop('disabled', false);
            isPfNumberValid = true;
                }
        function invalidPFNumberEnterred(thisObj) {
            $(thisObj).css("background-color", "#FFC1C1");
                }
        function resetForm(thisObj) {
            var className = $(thisObj).attr('class');
            $("." + className + " a").css('visibility', 'hidden');
            //$("." + className + " select").prop('disabled', true);
            $("." + className + " :button").prop('disabled', true);
            $("." + className + " .error").css('visibility', 'hidden');
        }
        function throwError(thisObj, errorMessage) {
            var className = $(thisObj).attr('class');
            $("." + className + " .error").css('visibility', 'visible');
            $("." + className + " #Error").text(errorMessage);
        }
        function doPOST_Request(dbURL, number, typeCall) {
            var returnMsg = '';
            $.ajax({
                type: 'POST',
                url: dbURL,
                data: { pfno: number, type: typeCall },
                success: function (msg) {
                    if (msg != "") returnMsg = msg.replace(/["']/g, "");
                    else alert("not Found");
                    if (msg == "false") returnMsg = "NA"; ;
                },
                error: function (msg) { alert("fail : " + msg); },
                async: false
            });
            return returnMsg;
        }
        function ShowUserDetails(thisObj) {
            var className = $(thisObj).attr('class');
            var pfNumber = $('.' + className + " :text").val();
            var popup = window.open("../UserDetailsWindow.php?pfNo=" + pfNumber, "Details", "resizable=1,scrollbars=1,height=325,width=280,left = " + (document.documentElement.clientWidth - 300) + ",top = " + (225));
            $(popup).blur(function () { this.close(); });
        }
    </script>
</head>

<body style="background-image:url('..\\img\\greyzz.png'); margin-right: 40%;">
    <script type="text/javascript">
        $(document).ready(function () {

            $("form a").css('visibility', 'hidden');
            //$("form select").prop('disabled', true);
            $("form :button").prop('disabled', true);
            $("form .error").css('visibility', 'hidden');

            $('form').bind("keyup keypress", function (e) {
                var code = e.keyCode || e.which;
                if (code == 13) {
                    e.preventDefault();
                    return false;
                }
            });
            $('#createUserForm').bind('submit', function () {
                if (!isPfNumberValid)
                    return false;
            });
            $(":text").keydown(function (e) {
                if (e.keyCode == 13) {
                    PFNumberEnterred(this);
                }
        });
            $(":text").blur(function (e) {
                PFNumberEnterred(this);
            });
            $(':button').click(function () {
            });
            $('form a').click(function () {
                ShowUserDetails(this);
            });
        });
    </script>
<br/>
<div style="margin-left:5%;">
        <ul class="tabs" data-persist="true" style="float:left">
            <li><a href="#view1">Create User</a></li>
            <li><a href="#view2">Reset Password</a></li>
            <li><a href="#view3">Enable/Disable User</a></li>
        </ul>
        <div class="tabcontents">
		<br/><br/>
            <div id="view1">
                <form id="createUserForm" class="pure-form pure-form-aligned createUser" action="manageUsersAction.php" method="post" enctype="multipart/form-data">
                    <input type="hidden" value="UserCreationForm" name="actionType"/>
				<fieldset>
					<div class="pure-control-group">
						<label for="cpfid">PF ID</label>
						<input class="createUser" id="cpfid" type="text" name="cpfid" />
                        <a class="createUser" href="#" id="cViewDetailsURL" style="visibility: hidden">View Details</a>
					</div>
                    <div class="pure-control-group create error">
                        <label for="Error" style="color: #ff6a00">Error :</label>
                        <span id="Error"  class="createUser" style="color: #ff6a00"></span>
                    </div>
					<div class="pure-controls">
					<button class="pure-button pure-button-primary createUser" id="cSubmitButton" name="cSubmitButton" type="submit">Create</button>
					</div>
				</fieldset>
				</form>
            </div>
            <div id="view2">
				<form id="resetUserForm" class="pure-form pure-form-aligned resetUser" action="manageUsersAction.php" method="post">
                    <input type="hidden" value="UserResetForm" name="actionType"/>
					<div class="pure-control-group">
						<label for="rpfid">PF ID</label>
						<input id="rpfid" class="resetUser" type="text" name="rpfid" />
                        <a class="resetUser"  href="#" id="rViewDetailsURL" style="visibility: hidden">View Details</a>
					</div>
                    <div class="pure-control-group create error">
                        <label for="Error" style="color: #ff6a00">Error :</label>
                        <span id="Error"  class="resetUser" style="color: #ff6a00"></span>
                    </div>
					<div class="pure-controls">
					<button class="pure-button pure-button-primary resetUser" id="rSubmitButton" name="rSubmitButton" type="submit" >Reset</button>
					</div>
				</form>               
            </div>
            <div id="view3">
               <form id="disableUserForm" class="pure-form pure-form-aligned disableUser" action="manageUsersAction.php" method="post">
                   <input type="hidden" value="UserDisableForm" name="actionType"/>
                   <input type="hidden" value="NULL" name="EnableOrDisable" id="EnableOrDisable"/>
					<div class="pure-control-group">
						<label for="dpfid">PF ID</label>
						<input class="disableUser" id="dpfid" type="text" name="dpfid"  />
                        <a class="disableUser" href="#" id="dViewDetailsURL" style="visibility: hidden" >View Details</a>
					</div>
                   <div class="pure-control-group create error">
                        <label for="Error" style="color: #ff6a00">Error :</label>
                        <span id="Error"  class="disableUser" style="color: #ff6a00"></span>
                    </div>
					<div class="pure-controls">
					<button class="pure-button pure-button-primary disableUser" name="dSubmitButton" type="submit" >Disable User</button>
					</div>
				</form>   
            </div>
        </div>
    </div>


</body>
</html>
<?php
}
?>
