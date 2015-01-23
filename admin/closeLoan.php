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
   <link rel="stylesheet" href="css/styles.css">
   <link rel="stylesheet" href="../css/pure-min.css">
   <script type="text/javascript" src="../jquery-latest.min.js"></script>
   <script type="text/javascript" src="../ValidationMethods.js"></script>
	<script type="text/javascript">
        function doPOST_Request(phpURL, accNumber, typeCall) {
            var returnMsg = '';
            $.ajax({
                type: 'POST',
                url: phpURL,
                data: { accNo: accNumber, type: typeCall },
                success: function (msg) {
                  if (msg != "") returnMsg = msg.replace(/["'\\]/g, "");
                  else alert("Pf number not Found");
                  if (msg == "false") returnMsg = "NA";
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
            var checkADMSAccountMstr=true;
            var checkCloseAccount=true;
            var adminPfNumber=doPOST_Request('../getPfnoFromSession.php', "", "getPfno");
            var url='../db/accountInformations.php';
            if (VerifyAccountandValidateAccess(url,enteredAccNumber,adminPfNumber,checkADMSAccountMstr,checkCloseAccount))
            {
              validAccountNumberEnterred(enteredAccNumber);
                }
            else
            {
                    invalidAccountNumberEnterred();
              return;
                }

        }
		function showAccountDetails() {
            var enteredAccNumber = document.getElementById('accNumber').value;
            var popup = window.open("../AccountDetailsWindow.php?accNo=" + enteredAccNumber, "Details", "resizable=1,scrollbars=1,height=325,width=280,left = " + (document.documentElement.clientWidth - 300) + ",top = " + (225));
            $(popup).blur(function () { this.close(); });
        }
     function showAccountDetailsIFrame() {
         var enteredAccNumber = document.getElementById('accNumber').value;
         $("#accountPreview").prop("src", "../AccountDetailsWindow.php?accNo=" + enteredAccNumber);
         $("#accountPreview").css({ "visibility": "visible" });
     }
		function validAccountNumberEnterred() {
            document.getElementById('getAccountDetailsSpan').style.visibility = "visible";
			var enteredAccNumber = document.getElementById('accNumber').value;
            var loanActive = doPOST_Request('../db/accountInformations.php', enteredAccNumber, 'isLoanActive');
            var documentStatus = doPOST_Request('../db/accountInformations.php', enteredAccNumber, 'GetDocumentStatusOfAccount');
            if (loanActive == "true") {
                if (documentStatus != '"OUT"') {
                    document.getElementById('accNumber').style.backgroundColor = "#CCFFCC";
					$('#formButton').prop('disabled', false);
                     showAccountDetailsIFrame();
                }
                else {
                    document.getElementById('accNumber').style.backgroundColor = "#FFC1C1";
                    throwError("Loan document is not inside RACPC!");
                }
            }
            else if (loanActive == "false") {
					document.getElementById('accNumber').style.backgroundColor = "#FFC1C1";
                    throwError("Loan is not active.");
                }
        }
		function invalidAccountNumberEnterred() {
            document.getElementById('accNumber').style.backgroundColor = "#FFC1C1";
            document.getElementById('getAccountDetailsSpan').style.visibility = "hidden";
         $(':button').prop("disabled", true);
        }
		function nullAccountNumberEnterred() {
            document.getElementById('accNumber').style.backgroundColor = "";
            document.getElementById('getAccountDetailsSpan').style.visibility = "hidden";
         $(':button').prop("disabled", true);
        }
	 function throwError(errorMessage) {
		$(".error").css('visibility', 'visible');
		$("#Error").text(errorMessage);
	}
        function resetForm() {
		document.getElementById('getAccountDetailsSpan').style.visibility = "hidden";
         $("#accountPreview").css({ "visibility": "hidden" });
		$(".error").css('visibility', 'hidden');
	}
	</script>
</head>
<body>
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
 <br/><br/>
 <div>
    <table border="0" style="width:100%;">
        <tr>
                <td style="width:50%;vertical-align: top;">
<form id="formid" class="pure-form pure-form-aligned" action="../db/closeLoanAction.php" method="POST" onSubmit="return confirm('Do you really want to close the loan?');">
		<div class="pure-control-group">
            <label for="accNumber" >Account Number</label>
                            <input type="text" id="accNumber" name="accNumber" autocomplete="off" onKeyDown="if (event.keyCode == 13) accountNumButtonClick()" />
            <a id="getAccountDetailsSpan" href="#"  style="visibility: hidden" onClick="showAccountDetails()">View Details</a> 
			<br/>
			<div class="pure-control-group error">
				<label for="Error" class="error" style="color: #ff6a00">Error :</label>
				<span id="Error"  class="error" style="color: #ff6a00"></span>
			</div>
		</div>
		<div class="pure-controls">
            <button class="pure-button pure-button-primary" type="submit" id="formButton" disabled="disabled">Close Loan</button>
        </div>
</form>
            </td>
                
            <td style="width:60%">
                <iframe id="accountPreview"  frameBorder="0"  marginheight="0" marginwidth="0"  style="visibility: hidden;height:30em;width:80%;"> </iframe>
            </td>
        </tr>
    </table>


</div>

</body>
</html>
<?php
}
?>
