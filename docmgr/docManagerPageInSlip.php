<!doctype html>
<html lang=''>
<head>
   <title>Document Manager</title>

     <?php
        session_start();
        if( $_SESSION["role"] != "RACPC_DM")
        {
           $_SESSION["role"] = "";
        ?>
        <meta http-equiv="refresh" content="0;URL=../login.html">
    <?php
        }
    ?>
    <script type="text/javascript" src="../jquery-latest.min.js"></script>
    <link rel="stylesheet" href="../css/my_styles.css">
    <link rel="stylesheet" href="../css/pure-min.css">
<script type="text/javascript" id="resetUserValidationScript">
        function resetPanelShowUserDetails() {
            var enteredUserPf = $('#pfnogiver').val();
            var popup = window.open("../UserDetailsWindow.php?pfNo=" + enteredUserPf, "Details", "resizable=1,scrollbars=1,height=325,width=280,left = " + (document.documentElement.clientWidth - 300) + ",top = " + (225));
            $(popup).blur(function () { this.close(); });
        }

        function pfNumResetFormEnter() {
            var pfNumber = $('#pfnogiver').val();
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
            document.getElementById('pfnogiver').style.backgroundColor = "";
            $('#rSubmitButton').prop('disabled', true);
        }
        function validpfNumberEnterredResetForm() {
            document.getElementById('rViewDetailsURL').style.visibility = "visible";
            document.getElementById('pfnogiver').style.backgroundColor = "#CCFFCC";
            $('#rSubmitButton').prop('disabled', false);
        }
        function invalidpfNumberEnterredResetForm() {
            document.getElementById('rViewDetailsURL').style.visibility = "hidden";
            document.getElementById('pfnogiver').style.backgroundColor = "#FFC1C1";
            $('#rSubmitButton').prop('disabled', true);
        }
</script>
<script>
    function InUpdateDocStatus() {
        DeActivateGenButton();
        $('#reason').prop('disabled', true);
		$('#pfnogiver').prop('disabled',true);
        var enteredAccNumber = document.getElementById('accountno').value;
        var phpURL = '../db/accountInformations.php';
        doPOST_Request(phpURL, enteredAccNumber, "InUpdateDocStatus");
            }
    function InActivityLogUpdate() {
        //ActivateGenButton();
        var phpURL = '../getPfnoFromSession.php';
        var login_pf = doPOST_Request_SessionUser(phpURL,'getPfno');
        var enteredAccNumber = document.getElementById('accountno').value;
        var borrower_pf_index = document.getElementById('pfnogiver').value;
        var slip_type = 'IN';
        var reason = document.getElementById('reason').value;
        var phpURL = '../db/UserInformations.php';
        var phno = doPOST_Request_GetUserPhone(phpURL, borrower_pf_index, "GetUserPhone");
        var phpURL1 = '../db/activityLog.php';
        doPOST_Request_InActivityLogInsert(phpURL1, enteredAccNumber, borrower_pf_index, login_pf, slip_type, reason, phno, "InActivityLogInsert");
        //DeActivateGenButton();
        //document.getElementById('genInslipButton').style.visibility = "hidden";
        //$('#genInslipButton').prop('disabled', true);
            }
    function showdetails() {
        var enteredAccNumber = $('#accountno').val();
        if (enteredAccNumber == "") {
            nullAccountNumberEnterred();
            return;
        }
        var phpURL = '../getPfnoFromSession.php';
        var login_pf = doPOST_Request_SessionUser(phpURL,'getPfno');
        phpURL = '../db/accountInformations.php';
        var msg = doPOST_Request_isValidAdmsAccount(phpURL, enteredAccNumber, login_pf, "isValidAdmsAccount");
        if (msg == "true") {
            validAccountNumberEnterred();
        }
        else if (msg == "false") {
            invalidAccountNumberEnterred();
            return;
        }
        document.getElementById('accountname').value = doPOST_Request(phpURL, enteredAccNumber, "GetAccountNameOfAccount");
        document.getElementById('brcode').value = doPOST_Request(phpURL, enteredAccNumber, "GetBranchCodeOfAccount");
        document.getElementById('foliono').value = doPOST_Request(phpURL, enteredAccNumber, "GetFolioNumberOfAccount").replace(/["'\\]/g, ""); 
        document.getElementById('brname').value = doPOST_Request(phpURL, enteredAccNumber, "GetBranchNameOfAccount");
        document.getElementById('productcode').value = doPOST_Request(phpURL, enteredAccNumber, "GetLoanProductOfAccount");
        $('#genInslipButton').prop('disabled', true);
        $('#reason').prop('disabled', true);
    }
    function showUdetails() {
        var enteredPFNumber = $('#pfnogiver').val();
        if (enteredPFNumber == "") {
            alert("Please Enter PF Number !");
            nullPfNumberEnterred();
            return;
        }
        var phpURL = '../db/UserInformations.php';
        $.post(phpURL, { pfno: enteredPFNumber, type: 'isValidUser' }, function (msg) {
            if (msg == "true") {
                validPFNumberEnterred();
            }
            else if (msg == "false") {
                invalidPFNumberEnterred();
                return;
            }

        }).fail(function (msg) {
            alert("fail : " + msg);
        });

		document.getElementById('nameofGiver').value = doPOST_RequestUser(phpURL, enteredPFNumber, "GetUserName"); 
        $('#genInslipButton').prop('disabled', true);
        $('#reason').prop('disabled', false);
        }
    function showAccountDetails() {
        var enteredAccNumber = document.getElementById('accountno').value;
        var popup = window.open("../AccountDetailsWindow.php?accNo=" + enteredAccNumber, "Details", "resizable=1,scrollbars=1,height=325,width=280,left = " + (document.documentElement.clientWidth - 300) + ",top = " + (225));
        $(popup).blur(function () { this.close(); });
        //document.getElementById('pfnogiver').disabled='true';
    }
	  function showUserDetails() {
        var enteredPFNumber = document.getElementById('pfnogiver').value;
        var popup = window.open("../UserDetailsWindow.php?pfNo=" + enteredPFNumber, "Details", "resizable=1,scrollbars=1,height=325,width=280,left = " + (document.documentElement.clientWidth - 300) + ",top = " + (225));
        $(popup).blur(function () { this.close(); });
    }

    function resetForm() {
        $('#genInslip').prop('disabled', true);
        $('#reason').prop('disabled', true);
        $('#accountname').val("");
        $('#brcode').val("");
        $('#productcode').val("");
        $('#brname').val("");
        $('#foliono').val("");
		$('#reason').val(""); 
		$('#pfnogiver').val("");
		$('#pfnogiver').prop('disabled', true);
		$('#accountno').val("");
		$('#nameofGiver').val("");
		$('#pfnogiver').val("");
        //$('#nameofGiver').val("");
        document.getElementById('slip_upload_frame').src = "";
        $('#genInslipButton').prop('disabled', true);
		document.getElementById('getAccountDetailsSpan').style.visibility = "hidden";
		document.getElementById('getUserDetailsSpan').style.visibility = "hidden";
    }
    function invalidAccountNumberEnterred() {
        document.getElementById('accountno').style.backgroundColor = "#FFC1C1";
        document.getElementById('getAccountDetailsSpan').style.visibility = "hidden";
        resetForm();
    }
    function nullAccountNumberEnterred() {
        document.getElementById('accountno').style.backgroundColor = "";
        document.getElementById('getAccountDetailsSpan').style.visibility = "hidden";
        alert("Please Enter Account Number");
        resetForm();
    }
    function validAccountNumberEnterred() {
        document.getElementById('getAccountDetailsSpan').style.visibility = "visible";
        document.getElementById('accountno').style.backgroundColor = "#CCFFCC";
        $('#pfnogiver').prop('disabled', false);
        $('#genInslip').prop('disabled', false);
        $('#reason').prop('disabled', false);
    }
    function invalidPFNumberEnterred() {
        document.getElementById('pfnogiver').style.backgroundColor = "#FFC1C1";
        document.getElementById('getUserDetailsSpan').style.visibility = "hidden";
		$('#nameofGiver').prop('disabled', true); 
		$('#reason').prop('disabled', true);
    }
	function nullPFNumberEnterred() {
        document.getElementById('pfnogiver').style.backgroundColor = "";
        document.getElementById('getUserDetailsSpan').style.visibility = "hidden";
    }
    function validPFNumberEnterred() {
        document.getElementById('getUserDetailsSpan').style.visibility = "visible";
        document.getElementById('pfnogiver').style.backgroundColor = "#CCFFCC";
        }
    function doPOST_Request_isValidAdmsAccount(phpURL, enteredAccNumber, login_pf, typeCall) {
        var returnMsg = '';
        $.ajax({
            type: 'POST',
            url: phpURL,
            data: { accNo: enteredAccNumber, login_pf_index: login_pf, type: typeCall },
            success: function (msg) {
                if (msg != "") { returnMsg = msg.replace(/["']/g, ""); }
                else alert("pf number not Found");
            },
            error: function (msg) { alert("fail : " + msg); },
            async: false
        });
        return returnMsg;

    }
    function doPOST_Request(phpURL, pfNumber, typeCall) {
        var returnMsg = '';
        $.ajax({
            type: 'POST',
            url: phpURL,
            data: { accNo: pfNumber, type: typeCall },
            success: function (msg) {
                if (msg != "") { returnMsg = msg.replace(/["']/g, ""); }
                else alert("pf number not Found");
            },
            error: function (msg) { alert("fail : " + msg); },
            async: false
        });
        return returnMsg;
    
	}
    function doPOST_RequestUser(phpURL, enteredPFNumber, typeCall) {
        var returnMsg = '';
        $.ajax({
            type: 'POST',
            url: phpURL,
            data: { pfno: enteredPFNumber, type: typeCall },
            success: function (msg) {
                if (msg != "") { returnMsg = msg.replace(/["']/g, ""); }
                else alert("requested user not Found");
            },
            error: function (msg) { alert("fail : " + msg); },
            async: false
        });
        return returnMsg;
    
    }
    function doPOST_Request_SessionUser(phpURL, typeCall) {
        var returnMsg = '';
        $.ajax({
            type: 'POST',
            url: phpURL,
            data: {type: typeCall},
            success: function (msg) {
                if (msg != "") { returnMsg = msg.replace(/["']/g, ""); }
                else alert("session user not Found");
            },
            error: function (msg) { alert("fail : " + msg); },
            async: false
        });
        return returnMsg;
    }
    function doPOST_Request_GetUserPhone(phpURL, borrower_pf_index, typeCall) {
        var returnMsg = '';
        $.ajax({
            type: 'POST',
            url: phpURL,
            data: { pfno: borrower_pf_index, type: typeCall },
            success: function (msg) {
                if (msg != "") { returnMsg = msg.replace(/["']/g, ""); }
                else alert("phone number not Found");
            },
            error: function (msg) { alert("fail : " + msg); },
            async: false
        });
        return returnMsg;
    }
    function ActivateGenButton() { 
        $('#genInslipButton').prop('disabled', false);
    }
    function DeActivateGenButton() {
        $('#genInslipButton').prop('disabled', true);
    }
    function doPOST_Request_InActivityLogInsert(phpURL1, enteredAccNumber, borrower_pf_index, login_pf, slip_type, reason, phno, typeCall) {
        var returnMsg = '';
        $.ajax({
            type: 'POST',
            url: phpURL1,
            data: { accNo: enteredAccNumber, borrower: borrower_pf_index, doc_mgr: login_pf, entered_slip: slip_type, entered_reason: reason,
                entered_phone: phno, type: typeCall
            },
            success: function (msg) {
                if (msg != "") { returnMsg = msg.replace(/["']/g, ""); }
                else alert("Inslip activity insert failed");
            },
            error: function (msg) { alert("fail : " + msg); },
            async: false
        });
        return returnMsg;
    }
    function trimfield(str) {
        return str.replace(/^\s+|\s+$/g, '');
    }
    function validate() {
        var reason = document.getElementById('reason').value;
        if (trimfield(reason) == '') {
            alert("Please Provide Your Comments !");
            document.getElementById('reason').focus();
            return false;
        }
        else {
            ActivateGenButton();
            return true;
        }
    }
    function validate_racpc() {
        var phpURL = '../getPfnoFromSession.php';
        var login_pf = doPOST_Request_SessionUser(phpURL, 'getPfno');
        var enteredAccNumber = document.getElementById('accountno').value;
        var borrower_pf_index = document.getElementById('pfnogiver').value;
        var msg;

        var phpURL = '../db/UserInformations.php';
        if (login_pf != '' && borrower_pf_index != '') {
            msg = doPOST_Request_validate_racpc_user(phpURL, borrower_pf_index, login_pf, "validate_racpc_user");
            if (msg == true) { 
            }
            else {
                alert("Borrower does not belong to document manager RACPC"); resetForm(); return;
            }
        }
        var phpURL = '../db/accountInformations.php';
        if (enteredAccNumber != '' && borrower_pf_index != '') {
            msg = doPOST_Request_validate_racpc_acc_user(phpURL, borrower_pf_index, enteredAccNumber, "validate_racpc_acc_user");
            if (msg == true) { 
            }
            else {
                alert("Account does not belong to borrower RACPC"); resetForm(); return;
            }

        }

    }
    function doPOST_Request_validate_racpc_user(phpURL, borrower_pf_index, login_pf, typeCall) {
        var returnMsg = '';
        $.ajax({
            type: 'POST',
            url: phpURL,
            data: { pfno: borrower_pf_index, login_pfno: login_pf, type: typeCall },
            success: function (msg) {
                if (msg == "true") {
                    returnMsg = true;
                }
                else {
                    returnMsg = false;
                }
            },
            error: function (msg) { alert("fail : " + msg); },
            async: false
        });
        return returnMsg;
    }
    function doPOST_Request_validate_racpc_acc_user(phpURL, borrower_pf_index, enteredAccNumber, typeCall) {
        var returnMsg = '';
        $.ajax({
            type: 'POST',
            url: phpURL,
            data: { pfno: borrower_pf_index, accNo: enteredAccNumber, type: typeCall },
            success: function (msg) {
                if (msg == "true") {
                    returnMsg = true;
                }
                else {
                    returnMsg = false;
                }
            },
            error: function (msg) { alert("fail : " + msg); },
            async: false
        });
        return returnMsg;
    }
	function InputCheck(){
	var accountno = document.getElementById('accountno').value;
	var pfnogiver = document.getElementById('pfnogiver').value;
        if (trimfield(accountno) == '') {
            alert("please provide Account number");
	DeActivateGenButton();
            document.getElementById('slip_upload_frame').src = ""; resetForm();
	}
	}

</script>

</head>
<body>
    <script type="text/javascript">
        $(document).ready(function () {
            $('#genInSlip').bind("keyup keypress", function (e) {
                var code = e.keyCode || e.which;
                if (code == 13) {
                    e.preventDefault();
                    return false;
                }
            });
            $('#genInSlip').bind('submit', function () {
                /*var isFormValid = true;
                if (!$('#file').val())  isFormValid = false; 
                else if (!$('#folio_no').val()) isFormValid = false;
                else if (!$('#rack_no').val()) isFormValid = false;
                if (!isFormValid) alert('Please Fill all the details to proceed');
                return isFormValid;*/
            });
        });
    </script>
<div>
   <br/>
   <br/>
<table border="0" style="width: 100%">
	<tr>
	<td style="width: 50%">
<form name="genInSlip" id="genInSlip" action="genInSlip.php" class="pure-form pure-form-aligned" method="POST" target="slip_upload_frame">
    <div class="pure-control-group"> 
        <label for="accountno" >Account Number :</label>
        <input type="text" name="accountno" id="accountno" onKeyDown="if (event.keyCode == 13) showdetails()" /> 
        <a id="getAccountDetailsSpan" href="#"  style="visibility: hidden" onclick="showAccountDetails()">View Details</a>
    </div> 
        
    <div class="pure-control-group">
        <label for="accountname" >Account Holder Name:</label>
        <input type="text" id="accountname" name="accountname" readonly="true"/> 
    </div>
            
     <div class="pure-control-group">
        <label for="productcode" >Product Code:</label>
        <input type="text" id="productcode" name="productcode" readonly="true"/> 
    </div>    
            
    <div class="pure-control-group">
        <label for="brcode" >Branch Code :</label>
        <input type="text" id="brcode" name="brcode" readonly="true" /> 
     </div>
     <div class="pure-control-group">
        <label for="brcode" >Branch Name :</label> 
        <input type="text" id="brname" name="brname" readonly="true" /> 
     </div>
    <div class="pure-control-group">
        <label for="foliono" > Folio number :</label>
        <input type="text" id="foliono" name="foliono" readonly="true" />
    </div>
    <div class="pure-control-group">
		<label for="pfnogiver" >  Giver's PF Number :</label>
		<input type="text" name="pfnogiver" id="pfnogiver" onKeyDown="if (event.keyCode == 13) showUdetails()"/>
        <a id="getUserDetailsSpan" href="#"  style="visibility: hidden" onclick="showUserDetails()">View Details</a>
    </div>
    <div class="pure-control-group">
        <label for="nameofGiver" > Name of the Giver :</label>
        <input type="text" id="nameofGiver" name="nameofGiver" readonly="true" />
    </div>
	
    <div class="pure-control-group">
        <label for="reason" > Comments :</label>
        <textarea rows="4" cols="22" name="reason" id="reason" onKeyDown="if (event.keyCode == 13)if(validate()=='true'){ActivateGenButton();}"  disabled="disabled"> </textarea>  
     </div>      
    
    <div class="pure-controls">
         <input class="pure-button pure-button-primary" type="submit" value="Generate In Slip" id="genInslipButton" 
		 onclick="$('#genInSlip').submit();InputCheck();validate_racpc();InUpdateDocStatus();InActivityLogUpdate();" disabled="disabled"	/>  
		 <input class="pure-button pure-button-primary" type="button" value="Reset" id="reset" onClick="resetForm()" />  
    </div> 

</center>
<p style="color: #33089e"> ** Account Number and Giver should belong to same RACPC </p>
</form>
</td>
        <td style="width: 50%;height: 100%;">
            <iframe id="slip_upload_frame" name="slip_upload_frame" style="width: 100%;height:400px;" frameBorder="0"  marginheight="0" marginwidth="0" frameborder="0"></iframe>
        </td>
        </tr>
        </table>  
</div>

</body>
</html>
