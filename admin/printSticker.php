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
		   $_SESSION["pfno"] = "";
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
			var enteredAccNumber = document.getElementById('accNumber').value;
			$.ajax({
                type: 'POST',
                url: '../db/accountInformations.php',
                data: { accNo: enteredAccNumber, type: 'GetRackNumberOfAccount' },
                success: function (msg) {
                    if (msg != "") {
			document.getElementById("barcodeIFrame").setAttribute('src',"../barcodegit/test.php?text="+ document.getElementById("accNumber").value);
					var rackNo = msg.replace(/["']/g, "");
					$("#rack").val(rackNo);
					$("#rackIFrame").prop("src","../barcodegit/test.php?text="+rackNo);
			document.getElementById("barcodeIFrame").style.display="block";
					document.getElementById("rackIFrame").style.display="block";
            document.getElementById('getAccountDetailsSpan').style.visibility = "visible";
            document.getElementById('accNumber').style.backgroundColor = "#CCFFCC";
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
		function showRackBarcode(){
			document.getElementById("rackIFrame").setAttribute('src',"../barcodegit/test.php?text="+ document.getElementById("rack").value);
			document.getElementById("rackIFrame").style.display="block";
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
		<div>
		<iframe id="barcodeIFrame" frameBorder="0" scrolling="no" style="height:4em;width:20em; padding-left:10em;display:none" marginheight="0" marginwidth="0" frameborder="0" src=''></iframe>
		<br>
		</div>
		<div class="pure-control-group">
			<label for="rack">Rack Location</label>
			<input type="text" id="rack" name="rack" autocomplete="off" disabled="true" onkeydown="if (event.keyCode == 13) showRackBarcode()" />
		</div>
		<div>
		<iframe id="rackIFrame" frameBorder="0" scrolling="no" style="height:4em;width:20em; padding-left:10em;display:none" marginheight="0" marginwidth="0" frameborder="0" src=''></iframe>
		<br>
	</div>
   </form>

</div>

</body>
</html>
