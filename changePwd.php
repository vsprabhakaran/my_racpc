<?php
    session_start();
    if(!($_SESSION["role"] == "BRANCH_VIEW" || $_SESSION["role"] == "RACPC_VIEW" || $_SESSION["role"] == "RACPC_ADMIN" || $_SESSION["role"]=="RACPC_DM" || $_SESSION["role"]=="RACPC_UCO"))
    {
       $_SESSION["role"] = "";
       $_SESSION["pfno"] = "";
 
    }
    else
    {
?>
<!doctype html>
<head>

	<!-- Basics -->
	
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1">
	
	<title>Change Password</title>

	<!-- CSS -->
	
	<!--link rel="stylesheet" href="css/reset.css"-->
	<!--link rel="stylesheet" href="css/animate.css"-->
	
	<link rel="stylesheet" href="css/pure-min.css">
    <script type="text/javascript" src="jquery-latest.min.js"></script>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#formid').bind("keyup keypress", function (e) {
                var code = e.keyCode || e.which;
                if (code == 13) {
                    e.preventDefault();
                    return false;
                }
            });
            $('#formid').bind('submit', function () {
                submitFunction();
            });
        });
        function doPOST_Request(phpURL, password, typeCall) {
            var returnmsg;
            $.ajax({
                type: 'POST',
                url: phpURL,
                data: { pwd: password, type: typeCall },
                success: function (msg) {
                    returnmsg = msg;
                },
                error: function (msg) { alert("fail : " + msg); },
                async: false
            });
            return returnmsg;
        }
        function resetForm() {
            $("#oldPassword").val("");
            $("#newPassword").val("");
            $("#confirmPassword").val("");
        }

        function submitFunction() {
            var oldPwd = $("#oldPassword").val();
            var newPwd = $("#newPassword").val();
            var confirmPwd = $("#confirmPassword").val();
            var oldPwdMsg = doPOST_Request('db/UserInformations.php', oldPwd, 'checkOldPassword');
            if (oldPwdMsg == "true") {
                if (newPwd == confirmPwd) {
                    if (newPwd.length > 5) {
                        var changePwdMsg = doPOST_Request('db/UserInformations.php', newPwd, 'updateNewPassword');
                        if (changePwdMsg == "true") {
                            alert("Password changed successfully!");
                            window.close();
                        }
                        else
                            alert("Something went wrong. Can't change your password!");
                    }
                    else {
                        alert("Password should contain atleast 6 characters");
                        resetForm();
                    }

                }
                else {
                    alert("New passwords didn't match.");
                    resetForm();
                }
            }
            else {
                alert("Old password didn't match.");
                resetForm();
            }

        }
    </script>
</head>

	<!-- Main HTML -->
	
<body style="background-image:url('img/greyzz.png')">
	
	<!-- Begin Page Content -->
	<!--div id="header">
		<img src="img\header.png" height=80 width=300\>
		<img id="title" src="img\title.png" height=80 width=300\>
	</div-->
	<div id="container">
	<br><br>
		<form id="formid" class="pure-form pure-form-aligned" style="padding-left: 40px;" method="POST">
		<div class="pure-control-group">
		<label id="labels" for="oldPassword" >Old Password</label>
		<input id="oldPassword" type="password" name="oldPassword"/>
		</div>
		<div class="pure-control-group">
		<label id="labels" for="newPassword">New Password</label>
		<input id="newPassword" type="password" name="newPassword" />
		</div>
        <div class="pure-control-group">
		<label id="labels" for="confirmPassword">Confirm Password</label>
		<input id="confirmPassword" type="password" name="confirmPassword"/>
		</div>
		<br>
		<!--div id="lower">
		<input type="submit" value="Change Password"/-->
		<div  class="pure-controls" style="padding-bottom:20px;">
            <button class="pure-button pure-button-primary" type="submit" id="formButton">Change Password</button>
        </div>
		</div>
		</form>
		
	</div>
	<!-- End Page Content -->
	
</body>

</html>
	
<?php
    }
?>
	
	
	
		
	