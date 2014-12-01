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
	<script type="text/javascript">
		function accountNumButtonClick() {
		 resetForm();
            var enteredAccNumber = document.getElementById('accNumber').value;
            if (enteredAccNumber == "") {
                nullAccountNumberEnterred();
                return;
            }
            $.post('../db/accountInformations.php', { accNo: enteredAccNumber, type: 'isValidAccount' }, function (msg) {
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
			$.ajax({
                type: 'POST',
                url: '../db/accountInformations.php',
                data: { accNo: enteredAccNumber, type: 'isLoanActive' },
                success: function (msg) {
                    if (msg == "true") {
                    document.getElementById('accNumber').style.backgroundColor = "#CCFFCC";
					$('#formButton').prop('disabled', false);
                     showAccountDetailsIFrame();
                }
                else if (msg == "false") {
					document.getElementById('accNumber').style.backgroundColor = "#FFC1C1";
                    throwError("Loan is not active.");
                }
                },
                error: function (msg) { alert("fail : " + msg); },
                async: false
            });
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
	function resetForm()
	{
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
            <td style="width:40%;vertical-align: top;">
<form id="formid" class="pure-form pure-form-aligned" action="../db/closeLoanAction.php" method="POST" onSubmit="return confirm('Do you really want to close the loan?');">
		<div class="pure-control-group">
            <label for="accNumber" >Account Number</label>
            <input type="text" id="accNumber" name="accNumber" autocomplete="off" onKeyDown="if (event.keyCode == 13) accountNumButtonClick()" onBlur="accountNumButtonClick()" />
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