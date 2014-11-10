<html>
    <head>
	<link rel="stylesheet" href="css/pure-min.css">
	<?php
        session_start();
        if( !($_SESSION["role"] == "BRANCH_VIEW" || $_SESSION["role"] == "RACPC_VIEW" || $_SESSION["role"] == "RACPC_ADMIN" ))
        {
           $_SESSION["role"] = "";
           $_SESSION["pfno"] = "";
        ?><meta http-equiv="refresh" content="0;URL=../login.html"><?php
        }
    ?>
	<script type="text/javascript" src="/my_racpc/jquery-latest.min.js"></script>
    <script type="text/javascript">
            function getPDF() {
			
                document.getElementById("pdfFile").setAttribute('src',"uploads\\"+ document.getElementById("accNumber").value + ".pdf");
                document.getElementById("pdfFile").style.visibility = "visible";
				
            }
			
			function accountNumButtonClick() {
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
        function validAccountNumberEnterred() {
            document.getElementById('getAccountDetailsSpan').style.visibility = "visible";
            document.getElementById('accNumber').style.backgroundColor = "#CCFFCC";
            $('#viewButton').prop('disabled', false);
        }
        function invalidAccountNumberEnterred() {
            document.getElementById('accNumber').style.backgroundColor = "#FFC1C1";
            document.getElementById('getAccountDetailsSpan').style.visibility = "hidden";
            $('#viewButton').prop('disabled', true);
            
        }
        function nullAccountNumberEnterred() {
            document.getElementById('accNumber').style.backgroundColor = "";
            document.getElementById('getAccountDetailsSpan').style.visibility = "hidden";
            $('#viewButton').prop('disabled', true);
            
        }
        function showAccountDetails() {
            var enteredAccNumber = document.getElementById('accNumber').value;
            var popup = window.open("AccountDetailsWindow.php?accNo=" + enteredAccNumber, "Details", "resizable=1,scrollbars=1,height=325,width=280,left = " + (document.documentElement.clientWidth - 300) + ",top = " + (225));
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
<!--object data="myfile.pdf" type="application/pdf" width="100%" height="100%"-->
  <!-- Account Number :  <input type="text" id="accno"/>
    <button id="submit" onclick="getPDF()">View</button> -->
	<br><br>
	<form id="formid" class="pure-form pure-form-aligned">
	<div class="pure-control-group">
            <label for="accNumber" >Account Number</label>
            <input type="text" id="accNumber" name="accNumber"  autocomplete="off" onkeydown="if (event.keyCode == 13) accountNumButtonClick()" onblur="accountNumButtonClick()" />
			<a id="getAccountDetailsSpan" href="#"  style="visibility: hidden" onclick="showAccountDetails()">View Details</a> 
    </div>
	</form>
	<button id="viewButton" class="pure-button pure-button-primary" onclick="getPDF()" style="margin-left:180px" disabled="disabled">View</button>
			<br><br>
	
<iframe  id="pdfFile" height="75%" width="100%" style="visibility: hidden"> </iframe>
</body>
</html>