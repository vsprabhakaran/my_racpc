<!doctype html>
<html lang=''>
<head>
   <link rel="stylesheet" href="css/styles.css">
   <link rel="stylesheet" href="../css/pure-min.css">
   <script type="text/javascript" src="../jquery-latest.min.js"></script>
	<script type="text/javascript">
		function accountNumButtonClick() {
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
                }
                else if (msg == "false") {
					document.getElementById('accNumber').style.backgroundColor = "#FFC1C1";
                    alert("Loan is already closed.");
                }
                },
                error: function (msg) { alert("fail : " + msg); },
                async: false
            });
			
			var popup = window.open("../AccountDetailsWindow.php?accNo=" + enteredAccNumber, "Details", "resizable=1,scrollbars=1,height=325,width=280,left = " + (document.documentElement.clientWidth - 300) + ",top = " + (225));
            $(popup).blur(function () { this.close(); });
        }
		function invalidAccountNumberEnterred() {
            document.getElementById('accNumber').style.backgroundColor = "#FFC1C1";
            document.getElementById('getAccountDetailsSpan').style.visibility = "hidden";
            
        }
		function nullAccountNumberEnterred() {
            document.getElementById('accNumber').style.backgroundColor = "";
            document.getElementById('getAccountDetailsSpan').style.visibility = "hidden";
            
        }
	</script>
     <?php
        session_start();
        if( $_SESSION["role"] != "RACPC_ADMIN")
        {
           $_SESSION["role"] = "";
		   $_SESSION["pfno"] = "";
        ?><meta http-equiv="refresh" content="0;URL=../login.html"><?php
        }
    ?>
</head>
<body>
<script type="text/javascript">
        $(document).ready(function () {
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
<form id="formid" class="pure-form pure-form-aligned" action="../db/closeLoanAction.php" method="POST" onsubmit="return confirm('Do you really want to close the loan?');">
		<div class="pure-control-group">
            <label for="accNumber" >Account Number</label>
            <input type="text" id="accNumber" name="accNumber" autocomplete="off" onkeydown="if (event.keyCode == 13) accountNumButtonClick()" />
            <a id="getAccountDetailsSpan" href="#"  style="visibility: hidden" onclick="showAccountDetails()">View Details</a> 
		</div>
		<div class="pure-controls">
            <button class="pure-button pure-button-primary" id="formButton" disabled="disabled">Close Loan</button>
        </div>
</form>

</div>

</body>
</html>
