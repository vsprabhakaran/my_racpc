<?php
        session_start();
        if( $_SESSION["role"] != "RACPC_DM")
        {
           $_SESSION["role"] = "";
        ?>
        <meta http-equiv="refresh" content="0;URL=../login.php">
    <?php
        }
		else
		{
    ?>

<!doctype html>
<html lang=''>
<head>
   <title>Document Manager</title>

    <script type="text/javascript" src="../jquery-latest.min.js"></script>
    <link rel="stylesheet" href="../css/my_styles.css">
    <link rel="stylesheet" href="../css/pure-min.css">


<script>

    function outUpdateDocStatus() {
        DeActivateGenButton();
        $('#reason').prop('disabled', true);
		$('#pfnorcv').prop('disabled',true);
        var enteredAccNumber = document.getElementById('accountno').value;
        var phpURL = '../db/accountInformations.php';
        doPOST_Request(phpURL, enteredAccNumber, "OutUpdateDocStatus");
        
            }
    function outActivityLogUpdate() {
        var phpURL = '../getPfnoFromSession.php';
        var login_pf = doPOST_Request_SessionUser(phpURL, 'getPfno');
        var enteredAccNumber = document.getElementById('accountno').value;
        var borrower_pf_index = document.getElementById('pfnorcv').value;
        var slip_type = 'OUT';
        var reason = document.getElementById('reason').value;
        var phpURL = '../db/UserInformations.php';
        var phno = doPOST_Request_GetUserPhone(phpURL, borrower_pf_index, "GetUserPhone");
        var phpURL1 = '../db/activityLog.php';
        doPOST_Request_OutActivityLogInsert(phpURL1, enteredAccNumber, borrower_pf_index, login_pf, slip_type, reason, phno, "OutActivityLogInsert");
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
		
		if(msg == "true")
		{
		
		phpURL = '../db/accountInformations.php';
		msg = doPOST_Request_isValidForOutSlip(phpURL,enteredAccNumber,"isValidForOutSlip");
		
        if (msg == "true") {
            validAccountNumberEnterred();
        }
        else if (msg == "false") {
            invalidAccountNumberEnterred();
			alert(" Account Not Valid for Out Slip Generation");
            return;
        }
		}
		else 
		{
		alert("Acount Not Valid");  $('#accountno').val(""); return;
		}
        document.getElementById('accountname').value = doPOST_Request(phpURL, enteredAccNumber, "GetAccountNameOfAccount");
        document.getElementById('brcode').value = doPOST_Request(phpURL, enteredAccNumber, "GetBranchCodeOfAccount");
        document.getElementById('foliono').value = doPOST_Request(phpURL, enteredAccNumber, "GetFolioNumberOfAccount").replace(/["'\\]/g, "");
        document.getElementById('brname').value = doPOST_Request(phpURL, enteredAccNumber, "GetBranchNameOfAccount");
        document.getElementById('productcode').value = doPOST_Request(phpURL, enteredAccNumber, "GetLoanProductOfAccount");
         $('#genOutslipButton').prop('disabled', true);
        $('#reason').prop('disabled', true);
    }
    function showUdetails() {
        var enteredPFNumber = $('#pfnorcv').val();
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
            alert("User information fail : " + msg);
        });

        document.getElementById('nameofReciver').value = doPOST_RequestUser(phpURL, enteredPFNumber, "GetUserName");
         $('#genOutslipButton').prop('disabled', true);
        $('#reason').prop('disabled', false);

    }
    function showUserDetails() {
        var enteredPFNumber = document.getElementById('pfnorcv').value;
        var popup = window.open("../UserDetailsWindow.php?pfNo=" + enteredPFNumber, "Details", "resizable=1,scrollbars=1,height=325,width=280,left = " + (document.documentElement.clientWidth - 300) + ",top = " + (225));
        $(popup).blur(function () { this.close(); });
    }
    function showAccountDetails() {
        var enteredAccNumber = document.getElementById('accountno').value;
        var popup = window.open("../AccountDetailsWindow.php?accNo=" + enteredAccNumber, "Details", "resizable=1,scrollbars=1,height=325,width=280,left = " + (document.documentElement.clientWidth - 300) + ",top = " + (225));
        $(popup).blur(function () { this.close(); });
    }
    function resetForm() {
        $('#genInslip').prop('disabled', true);
        $('#reason').prop('disabled', true);
        $('#accountname').val("");
        $('#brcode').val("");
        $('#brname').val("");
        $('#foliono').val("");
        $('#reason').val("");
        $('#pfnorcv').val("");
        $('#pfnorcv').prop('disabled',false);
        $('#accountno').val("");
        $('#nameofReciver').val("");
		$('#productcode').val("");
        document.getElementById('slip_upload_frame').src = "";
        $('#genOutslipButton').prop('disabled', true);
		document.getElementById('getAccountDetailsSpan').style.visibility = "hidden";
		document.getElementById('getUserDetailsSpan').style.visibility = "hidden";
		document.getElementById('accountno').style.backgroundColor = "";
		document.getElementById('pfnorcv').style.backgroundColor = "";
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
        $('#pfnorcv').prop('disabled', false);
        $('#genInslip').prop('disabled', false);
        $('#reason').prop('disabled', false);
    }
    function invalidPFNumberEnterred() {
        document.getElementById('pfnorcv').style.backgroundColor = "#FFC1C1";
        document.getElementById('getUserDetailsSpan').style.visibility = "hidden";
		$('#pfnorcv').prop('disabled', false); 
		$('#reason').prop('disabled', true);
    }
    function nullPFNumberEnterred() {
        document.getElementById('pfnorcv').style.backgroundColor = "";
        document.getElementById('getUserDetailsSpan').style.visibility = "hidden";

    }
    function validPFNumberEnterred() {
        document.getElementById('getUserDetailsSpan').style.visibility = "visible";
        document.getElementById('pfnorcv').style.backgroundColor = "#CCFFCC";
    }
    function doPOST_Request(phpURL, pfNumber, typeCall) {
        var returnMsg = '';
        $.ajax({
            type: 'POST',
            url: phpURL,
            data: { accNo: pfNumber, type: typeCall },
            success: function (msg) {
                if (msg != "") returnMsg = msg.replace(/["']/g, "");
                else alert("pf number not Found");
            },
            error: function (msg) { alert("fail : " + msg); },
            async: false
        });
        return returnMsg;
    }
    function doPOST_Request_isValidAdmsAccount(phpURL, enteredAccNumber, login_pf, typeCall) {
    var returnMsg = '';
        $.ajax({
            type: 'POST',
            url: phpURL,
            data: { accNo: enteredAccNumber, login_pf_index: login_pf, type: typeCall },
            success: function (msg) {
                if (msg != "") { returnMsg = msg.replace(/["']/g, ""); }
                else alert("Account does not belong to RACPC");
            },
            error: function (msg) { alert("fail : " + msg); },
            async: false
        });
        return returnMsg;

    }
	function doPOST_Request_isValidForOutSlip(phpURL, enteredAccNumber, typeCall) {
	var returnMsg = '';
        $.ajax({
            type: 'POST',
            url: phpURL,
            data: { accNo: enteredAccNumber, type: typeCall },
            success: function (msg) {
                if (msg != "") {  returnMsg = msg.replace(/["']/g, ""); }
                else alert("Account Not valid for out slip");
            },
            error: function (msg) { alert("fail : " + msg); },
            async: false
        });
        return returnMsg;
	}
    function doPOST_Request_SessionUser(phpURL,typeCall) {
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
    function doPOST_Request_OutActivityLogInsert(phpURL1, enteredAccNumber, borrower_pf_index, login_pf, slip_type, reason, phno, typeCall) {
        var returnMsg = '';
        $.ajax({
            type: 'POST',
            url: phpURL1,
            data: { accNo: enteredAccNumber, borrower: borrower_pf_index, doc_mgr: login_pf, entered_slip: slip_type, entered_reason: reason,
                entered_phone: phno, type: typeCall
            },
            success: function (msg) {
                if (msg != "") { returnMsg = msg.replace(/["']/g, ""); }
                else alert("out slip Activity log insert failed");
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
                else alert("user not found");
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
                else alert("phone not found");
            },
            error: function (msg) { alert("fail : " + msg); },
            async: false
        });
        return returnMsg;

    }
    function ActivateGenButton() { $('#genOutslipButton').prop('disabled', false); }
    function DeActivateGenButton() { $('#genOutslipButton').prop('disabled', true); }    
    function trimfield(str){ 
    return str.replace(/^\s+|\s+$/g,''); 
}
    function validate_reason() {
	        var reason = document.getElementById('reason').value;
        if (trimfield(reason)== '') {
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
        var borrower_pf_index = document.getElementById('pfnorcv').value;
        var msg;

        var phpURL = '../db/UserInformations.php';
        if (login_pf != '' && borrower_pf_index != '') {
            msg = doPOST_Request_validate_racpc_user(phpURL, borrower_pf_index, login_pf, "validate_racpc_user");
            if (msg == true) {
            }
            else {
                alert("Borrower does not belong to document manager RACPC");
                resetForm();
                return;
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
	var pfnorcv = document.getElementById('pfnorcv').value;
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
            $('#genOutSlip').bind("keyup keypress", function (e) {
                var code = e.keyCode || e.which;
                if (code == 13) {
                    e.preventDefault();
                    return false;
                }
            });
            $('#genOutSlip').bind('submit', function () {
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
    <table border="0" style="width: 100%;height: 100%; font-size:15px">
	<tr>
	<td style="width:100%" style="font-size:12px">
<form name="genOutSlip" id="genOutSlip" action="genOutSlip.php" class="pure-form pure-form-aligned" method="POST" target="slip_upload_frame">
    <div class="pure-control-group"> 
        <label for="accountno" >Account Number :</label>
        <input type="text" name="accountno" id="accountno" onKeyDown="if (event.keyCode == 13) showdetails()" /> 
        <a id="getAccountDetailsSpan" href="#"  style="visibility: hidden" onClick="showAccountDetails()">View Details</a>
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
        <label for="pfnorcv" >  Receivers's PF Number :</label>

        <input type="text" name="pfnorcv" id="pfnorcv" onKeyDown="if (event.keyCode == 13) showUdetails()" />
        <a id="getUserDetailsSpan" href="#"  style="visibility: hidden" onClick="showUserDetails()">View Details</a>
    </div>

    <div class="pure-control-group">
        <label for="nameofReciver" > Name of the Receiver :</label>
        <input type="text" id="nameofReciver" name="nameofReciver" readonly="true" />
    </div>

<div class="pure-control-group">
<label for="reason" > Comments :</label>
    <textarea rows="4" cols="22" name="reason" id="reason" onKeyDown="if (event.keyCode == 13) if(validate_reason()=='true'){ActivateGenButton();}"  disabled="disabled"> </textarea>  
</div>      
    
<div class="pure-controls">
<input class="pure-button pure-button-primary" type="submit" 
    value="Generate Out Slip" id="genOutslipButton" 
    onclick="$('#genOutSlip').submit();InputCheck();validate_racpc();outUpdateDocStatus();outActivityLogUpdate();" disabled="disabled" />  
<input class="pure-button pure-button-primary" type="button" value="Reset" id="reset" onClick="resetForm()"/>  
            </div>

                
            </center>
<p style="color: #33089e"> ** Account Number and Receiver should belong to same RACPC </p>
</form>
</td>
<td style="width:75%;height: 100%;" >
<iframe id="slip_upload_frame" name="slip_upload_frame" style="width: 800px;height:400px;" marginheight="0" marginwidth="0" frameborder="0">
</iframe>
        </td>
    </tr>
</table>
</div>

</body>
</html>
<?php
}
?>
