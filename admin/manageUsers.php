<!doctype html>
<html lang=''>
<head>
   <link rel="stylesheet" href="../css/my_styles.css">
   <link rel="stylesheet" href="../css/pure-min.css">
	 <?php
        session_start();
        if( $_SESSION["role"] != "RACPC_ADMIN")
        {
           $_SESSION["role"] = "";
        ?><!--meta http-equiv="refresh" content="0;URL=../login.html"--><?php
        }
    ?>
	<script src="../tab-content/tabcontent.js" type="text/javascript"></script>
    <link href="../tab-content/template6/tabcontent.css" rel="stylesheet" type="text/css" />
    <script src="../jquery-latest.min.js" type="text/javascript"></script>
    <script type="text/javascript" id="createUserValidationScript">
        function createPanelShowUserDetails() {
            var enteredUserPf = $('#cpfid').val();
            var popup = window.open("../UserDetailsWindow.php?pfNo=" + enteredUserPf, "Details", "resizable=1,scrollbars=1,height=325,width=280,left = " + (document.documentElement.clientWidth - 300) + ",top = " + (225));
            $(popup).blur(function () { this.close(); });
        }

        function pfNumCreateFormEnter() {
            var pfNumber = $('#cpfid').val();
            //alert(pfNumber);
            if (pfNumber == "") {
                nullpfNumberEnterredCreateForm();
                return;
            }
            $.post('../db/UserInformations.php', { pfno: pfNumber, type: 'isValidUser' }, function (msg) {
                if (msg == "true") {
                    validpfNumberEnterredCreateForm();
                }
                else if (msg == "false") {
                    invalidpfNumberEnterredCreateForm();
                }
            }).fail(function (msg) {
                alert("fail : " + msg);
            });
        }

        function nullpfNumberEnterredCreateForm(form) {
            document.getElementById('cViewDetailsURL').style.visibility = "hidden";
            document.getElementById('cpfid').style.backgroundColor = "";
            $('#crole').prop('disabled', true);
            $('#cSubmitButton').prop('disabled', true);
        }
        function validpfNumberEnterredCreateForm() {
            document.getElementById('cViewDetailsURL').style.visibility = "visible";
            document.getElementById('cpfid').style.backgroundColor = "#CCFFCC";
            $('#crole').prop('disabled', false);
            $('#cSubmitButton').prop('disabled', false);
        }
        function invalidpfNumberEnterredCreateForm() {
            document.getElementById('cViewDetailsURL').style.visibility = "hidden";
            document.getElementById('cpfid').style.backgroundColor = "#FFC1C1";
            $('#crole').prop('disabled', true);
            $('#cSubmitButton').prop('disabled', true);
        }

    </script>
    <script type="text/javascript" id="resetUserValidationScript">
        function resetPanelShowUserDetails() {
            var enteredUserPf = $('#rpfid').val();
            var popup = window.open("../UserDetailsWindow.php?pfNo=" + enteredUserPf, "Details", "resizable=1,scrollbars=1,height=325,width=280,left = " + (document.documentElement.clientWidth - 300) + ",top = " + (225));
            $(popup).blur(function () { this.close(); });
        }

        function pfNumResetFormEnter() {
            var pfNumber = $('#rpfid').val();
            //alert(pfNumber);
            if (pfNumber == "") {
                nullpfNumberEnterredResetForm();
                return;
            }
            $.post('../db/UserInformations.php', { pfno: pfNumber, type: 'isValidUser' }, function (msg) {
                if (msg == "true") {
                    validpfNumberEnterredResetForm();
                }
                else if (msg == "false") {
                    invalidpfNumberEnterredResetForm();
                }
            }).fail(function (msg) {
                alert("fail : " + msg);
            });

        }

        function nullpfNumberEnterredResetForm(form) {
            document.getElementById('rViewDetailsURL').style.visibility = "hidden";
            document.getElementById('rpfid').style.backgroundColor = "";
            $('#rSubmitButton').prop('disabled', true);
        }
        function validpfNumberEnterredResetForm() {
            document.getElementById('rViewDetailsURL').style.visibility = "visible";
            document.getElementById('rpfid').style.backgroundColor = "#CCFFCC";
            $('#rSubmitButton').prop('disabled', false);
        }
        function invalidpfNumberEnterredResetForm() {
            document.getElementById('rViewDetailsURL').style.visibility = "hidden";
            document.getElementById('rpfid').style.backgroundColor = "#FFC1C1";
            $('#rSubmitButton').prop('disabled', true);
        }

    </script>
</head>

<body style="background-image:url('..\\img\\greyzz.png')">
    <script type="text/javascript">
        $(document).ready(function () {
            $('#createUserForm,#resetUserForm,#manageUserForm').bind("keyup keypress", function (e) {
                var code = e.keyCode || e.which;
                if (code == 13) {
                    e.preventDefault();
                    return false;
                }
            });
            /*$('#createUserForm').bind('submit', function () {
                var isFormValid = true;
                alert("submit");
                if (!$('#file').val()) isFormValid = false;
                else if (!$('#folio_no').val()) isFormValid = false;
                else if (!$('#rack_no').val()) isFormValid = false;
                if (!isFormValid) alert('Please Fill all the details to proceed');
                return isFormValid;
            });*/
        });
    </script>
<br/>
<div style="margin: 0 auto;">
        <ul class="tabs" data-persist="true" style="float:left">
            <li><a href="#view1">Create User</a></li>
            <li><a href="#view2">Reset Password</a></li>
            <li><a href="#view3">Disable User</a></li>
        </ul>
        <div class="tabcontents">
		<br/><br/>
            <div id="view1">
                <form id="createUserForm" class="pure-form pure-form-aligned" action="UserManager.php" method="post">
                    <input type="hidden" value="UserCreationForm" name="actionType"/>
				<fieldset>
					<div class="pure-control-group">
						<label for="cpfid">PF ID</label>
						<input id="cpfid" type="text" name="cpfid" onkeydown="if (event.keyCode == 13) pfNumCreateFormEnter()" onblur="pfNumCreateFormEnter()"/>
                        <a href="javascript:createPanelShowUserDetails()" id="cViewDetailsURL" style="visibility: hidden">View Details</a>
					</div>
					<div class="pure-control-group">
					<div class="pure-u-1 pure-u-md-1-3">
					<label for="role">User type</label>
					<select id="crole" name="crole" class="pure-input-1-2" style="width:20%;" disabled="disabled">
						  <option value="queryUser">Query User</option>
						  <option value="branchUser">Branch User</option>
					</select>
					</div>	
					</div>
					<div class="pure-controls">
					<button class="pure-button pure-button-primary" id="cSubmitButton" name="cSubmitButton" disabled="disabled" type="submit">Create</button>
					</div>
				</fieldset>
				</form>
            </div>
            <div id="view2">
				<form id="resetUserForm" class="pure-form pure-form-aligned" action="UserManager.php" method="post">
                    <input type="hidden" value="UserResetForm" name="actionType"/>
					<div class="pure-control-group">
						<label for="rpfid">PF ID</label>
						<input id="rpfid" type="text" name="rpfid" onkeydown="if (event.keyCode == 13) pfNumResetFormEnter()" onblur="pfNumResetFormEnter()"/>
                        <a href="javascript:resetPanelShowUserDetails()" id="rViewDetailsURL" style="visibility: hidden">View Details</a>
					</div>

					<div class="pure-controls">
					<button class="pure-button pure-button-primary" id="rSubmitButton" name="rSubmitButton" type="submit" disabled="disabled" >Reset</button>
					</div>
				</form>               
            </div>
            <div id="view3">
               <form id="manageUserForm" class="pure-form pure-form-aligned" action="UserManager.php" method="post">
                   <input type="hidden" value="UserDisableForm" name="actionType"/>
					<div class="pure-control-group">
						<label for="mpfid">PF ID</label>
						<input id="mpfid" type="text" name="mpfid" onkeydown="if (event.keyCode == 13) pfNumEnter(this.form)" />
                        <a href="javascript:showUserDetails()" id="mViewDetailsURL" style="visibility: hidden" >View Details</a>
					</div>
					<div class="pure-controls">
					<button class="pure-button pure-button-primary" name="mSubmitButton" type="submit" disabled="disabled" >Disable</button>
					</div>
				</form>   
            </div>
        </div>
    </div>


</body>
</html>
