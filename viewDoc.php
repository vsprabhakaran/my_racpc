<?php
        session_start();
        if( !($_SESSION["role"] == "BRANCH_VIEW" || $_SESSION["role"] == "RACPC_VIEW" || $_SESSION["role"] == "RACPC_ADMIN" || $_SESSION["role"] == "RACPC_DM" ))
        {
           $_SESSION["role"] = "";
           $_SESSION["pfno"] = "";
        ?><meta http-equiv="refresh" content="0;URL=../login.php"><?php
        }
		else
		{
    ?>
<html>
    <head>
	<link rel="stylesheet" href="css/pure-min.css">
	<script type="text/javascript" src="/my_racpc/jquery-latest.min.js"></script>
    <script type="text/javascript">
        function getPDF() {
            var enteredAccNumber = document.getElementById('accNumber').value;
            if (checkFileAccess(enteredAccNumber)) {
                document.getElementById("pdfFile").setAttribute('src', "docBuffer.php?accNo=" + enteredAccNumber);
                document.getElementById("pdfFile").style.visibility = "visible";
            }
            else {
                invalidAccountNumberEnterred();
                throwError("You dont have access to view this file!");
            }
        }
        function checkFileAccess(enteredAccNumber) {
            var pfno = doPOST_RequestExt('getPfnoFromSession.php', "", '', 'getPfno');
            var role = doPOST_RequestExt('getPfnoFromSession.php', "", '', 'getRole');
            var branchCode = doPOST_RequestExt('db/accountInformations.php', enteredAccNumber, '', 'GetBranchCodeOfAccount');
            if (role == "BRANCH_VIEW") {
                var status = doPOST_RequestExt('db/accountInformations.php', enteredAccNumber, pfno, 'checkBranchViewAccess');
            }
            else if (role == "RACPC_VIEW" || role == "RACPC_ADMIN" || role == "RACPC_DM") {
                var status = doPOST_RequestExt('db/accountInformations.php', enteredAccNumber, pfno, 'checkRacpcViewAccess');
            }
            else {
                return false;
            }
            if (status == 'RACPC_VIEW_GRANTED' || status == 'BRANCH_VIEW_GRANTED') {
                return true;
            }
            else {
                return false;
            }
        }
        function doPOST_RequestExt(phpURL, accNumber, pfnum, typeCall) {
            var returnMsg = '';
            $.ajax({
                type: 'POST',
                url: phpURL,
                data: { accNo: accNumber, pfno: pfnum, type: typeCall },
                success: function (msg) {
                    //alert(typeCall+" " +msg);
                    if (msg != "") returnMsg = msg.replace(/["']/g, "");
                    else alert("msg is empty");
                    if (msg == "false") {
                        returnMsg = "NA";
                    }
                },
                error: function (msg) { alert("fail : " + msg); },
                async: false
            });
            return returnMsg;
        }

        function accountNumButtonClick() {
            resetForm();
            var enteredAccNumber = document.getElementById('accNumber').value;
            if (enteredAccNumber == "") {
                nullAccountNumberEnterred();
                return;
            }
            $.post('db/accountInformations.php', { accNo: enteredAccNumber, type: 'isValidAccount' }, function (msg) {
                if (msg == "true") {
                    validAccountNumberEnterred();
                }
                else if (msg == "false") {
                    invalidAccountNumberEnterred();
                }
            }).fail(function (msg) {
                alert("fail : " + msg);
            });

        }
        function doPOST_Request(phpURL, accNumber, typeCall) {
            var returnMsg = '';
            $.ajax({
                type: 'POST',
                url: phpURL,
                data: { accNo: accNumber, type: typeCall },
                success: function (msg) {
                    returnMsg = msg.replace(/["']/g, "");

                },
                error: function (msg) { alert("fail : " + msg); },
                async: false
            });
            return returnMsg;
        }
        function validAccountNumberEnterred() {
            var enteredAccNumber = document.getElementById('accNumber').value;
            if (!checkFileAccess(enteredAccNumber)) {
                invalidAccountNumberEnterred();
                throwError("You dont have access to view this file!");
                return;
            }
            var loanStatus = doPOST_Request('db/accountInformations.php', enteredAccNumber, 'isLoanActive');
            if (loanStatus == 'true') {
                document.getElementById('getAccountDetailsSpan').style.visibility = "visible";
                document.getElementById('accNumber').style.backgroundColor = "#CCFFCC";
                $('#viewButton').prop('disabled', false);
            }
            else {
                document.getElementById('accNumber').style.backgroundColor = "#FFC1C1";
                throwError("Loan is not active.");
            }
        }
        function resetForm() {
            document.getElementById('getAccountDetailsSpan').style.visibility = "hidden";
            $('#pdfFile').css("visibility", "hidden");
            $('#viewButton').prop('disabled', true);
            $(".error").css('visibility', 'hidden');
        }
        function invalidAccountNumberEnterred() {
            document.getElementById('accNumber').style.backgroundColor = "#FFC1C1";
        }
        function nullAccountNumberEnterred() {
            document.getElementById('accNumber').style.backgroundColor = "";
        }
        function showAccountDetails() {
            var enteredAccNumber = document.getElementById('accNumber').value;
            var popup = window.open("AccountDetailsWindow.php?accNo=" + enteredAccNumber, "Details", "resizable=1,scrollbars=1,height=325,width=280,left = " + (document.documentElement.clientWidth - 300) + ",top = " + (225));
            $(popup).blur(function () { this.close(); });
        }
        function throwError(errorMessage) {
            $(".error").css('visibility', 'visible');
            $("#Error").text(errorMessage);
        }
        </script>
    </head>
<body style="background-image:url('img/greyzz.png')">
<script type="text/javascript">
        $(document).ready(function () {
			resetForm();
            $('#formid').bind("keyup keypress", function(e) {
              var code = e.keyCode || e.which; 
              if (code  == 13) {               
                e.preventDefault();
                return false;
              }
            });
			
        });
    </script>
<!--object data="myfile.pdf" type="application/pdf" width="100%" height="100%"-->
  <!-- Account Number :  <input type="text" id="accno"/>
    <button id="submit" onclick="getPDF()">View</button> -->
	<br><br>
	<form id="formid" class="pure-form pure-form-aligned">
	<div class="pure-control-group">
            <label for="accNumber" >Account Number</label>
            <input type="text" id="accNumber" name="accNumber"  autocomplete="off" onkeydown="if (event.keyCode == 13) accountNumButtonClick()" onblur="accountNumButtonClick()" />
			<a id="getAccountDetailsSpan" href="#"  style="visibility: hidden" onclick="showAccountDetails()">View Details</a> 
			<br/>
			<div class="pure-control-group error">
				<label for="Error" style="color: #ff6a00">Error :</label>
				<span id="Error"  class="createUser" style="color: #ff6a00"></span>
			</div>
    </div>
	</form>
	<button id="viewButton" class="pure-button pure-button-primary" onclick="getPDF()" style="margin-left:180px" disabled="disabled">View</button>
			<br><br>
		<iframe  id="pdfFile" height="85%" width="100%" style="visibility: hidden"> </iframe>
</body>
</html>
<?php 
}
?>