<!doctype html>
<html lang=''>
<head>
   <link rel="stylesheet" href="css/styles.css">
   <link rel="stylesheet" href="../css/pure-min.css">
     <?php
        session_start();
        if( $_SESSION["role"] != "RACPC_ADMIN" && $_SESSION["role"] != "RACPC_DM")
        {
           $_SESSION["role"] = "";
        ?><meta http-equiv="refresh" content="0;URL=../login.html"><?php
        }
    ?>
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
        function validAccountNumberEnterred() {
            document.getElementById('getAccountDetailsSpan').style.visibility = "visible";
            document.getElementById('accNumber').style.backgroundColor = "#CCFFCC";
        }
        function invalidAccountNumberEnterred() {
            document.getElementById('accNumber').style.backgroundColor = "#FFC1C1";
            document.getElementById('getAccountDetailsSpan').style.visibility = "hidden";
            
        }
        function nullAccountNumberEnterred() {
            document.getElementById('accNumber').style.backgroundColor = "";
            document.getElementById('getAccountDetailsSpan').style.visibility = "hidden";
            
        }
        function showAccountDetails() {
            var enteredAccNumber = document.getElementById('accNumber').value;
            var popup = window.open("../AccountDetailsWindow.php?accNo=" + enteredAccNumber, "Details", "resizable=1,scrollbars=1,height=325,width=280,left = " + (document.documentElement.clientWidth - 300) + ",top = " + (225));
            $(popup).blur(function () { this.close(); });
        }
    </script>
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
 <br><br>
<div>
   <form id="formid" class="pure-form pure-form-aligned">
		<div class="pure-control-group">
            <label for="accNumber" >Account Number</label>
            <input type="text" id="accNumber" name="accNumber" autocomplete="off" onkeydown="if (event.keyCode == 13) accountNumButtonClick()" />
            <a id="getAccountDetailsSpan" href="#"  style="visibility: hidden" onclick="showAccountDetails()">View Details</a> 
		</div>
		<div class="pure-control-group">
			<label for="rack">Rack Location</label>
			<input type="text" id="rack" name="rack" autocomplete="off" onkeydown="if (event.keyCode == 13) rackButtonClick()" />
			<a id="getAccountDetailsFromRackSpan" href="#"  style="visibility: hidden" onclick="showRackDetails()">View Details</a> 
		</div>
		<div class="pure-controls">
		<button class="pure-button pure-button-primary" id="formButton" disabled="disabled">Print</button>
	</div>
   </form>

</div>

</body>
</html>
