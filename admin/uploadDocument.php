<!doctype html>
<html lang=''>
<head>
   <link rel="stylesheet" href="../css/inputFormStyles.css" type="text/css">
   <link rel="stylesheet" href="../css/pure-min.css">
     <?php
        session_start();
        if( $_SESSION["role"] != "RACPC_ADMIN")
        {
           $_SESSION["role"] = "";
        ?><!--meta http-equiv="refresh" content="0;URL=../login.html"--><?php
        }
    ?>
    <script type="text/javascript" src="../jquery-latest.min.js"></script>
	<script type="text/javascript" src="../jquery-barcode.js"></script>
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
			document.getElementById("barcodeIFrame").setAttribute('src',"../barcodegit/test.php?text="+ document.getElementById("accNumber").value);
			document.getElementById("barcodeIFrame").style.display="block";
			var acc= $("#accNumber").val();
            document.getElementById('getAccountDetailsSpan').style.visibility = "visible";
            document.getElementById('accNumber').style.backgroundColor = "#CCFFCC";
            $('#file').prop('disabled', false);
            $('#folio_no').prop('disabled', false);
            $('#rack_no').prop('disabled', false);
            $('#formButton').prop('disabled', false);
			$("#accBarcode").barcode(acc, "code128",{barWidth:2, barHeight:30});
        }
        function invalidAccountNumberEnterred() {
            document.getElementById('accNumber').style.backgroundColor = "#FFC1C1";
            document.getElementById('getAccountDetailsSpan').style.visibility = "hidden";
            $('#file').prop('disabled', true);
            $('#folio_no').prop('disabled', true);
            $('#rack_no').prop('disabled', true);
            $('#formButton').prop('disabled', true);
        }
        function nullAccountNumberEnterred() {
            document.getElementById('accNumber').style.backgroundColor = "";
            document.getElementById('getAccountDetailsSpan').style.visibility = "hidden";
            $('#file').prop('disabled', true);
            $('#folio_no').prop('disabled', true);
            $('#rack_no').prop('disabled', true);
            $('#formButton').prop('disabled', true);
        }
        function showAccountDetails() {
            var enteredAccNumber = document.getElementById('accNumber').value;
            var popup = window.open("../AccountDetailsWindow.php?accNo=" + enteredAccNumber, "Details", "resizable=1,scrollbars=1,height=325,width=280,left = " + (document.documentElement.clientWidth - 300) + ",top = " + (225));
            $(popup).blur(function () { this.close(); });
        }
		/* function showRackBarcode(){
			var rack= $("#rack_no").val();
			alert(rack);
			$("#rackBarcode").barcode(rack, "code128",{barWidth:2, barHeight:30});
		} */
		
		function showRackBarcode(){
			document.getElementById("rackIFrame").setAttribute('src',"../barcodegit/test.php?text="+ document.getElementById("rack_no").value);
			document.getElementById("rackIFrame").style.display="block";
		}
		/* function print(){
			var acc= $("#accNumber").val();
			alert(acc);
			$.post('../barcodegit/barcode.php', { accNo: acc }, function (msg) {
                if (msg == "false") {
                    alert("Barcode Generation Failed");
                }
            }).fail(function (msg) {
                alert("fail : " + msg);
            });

		} */
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
            $('#formid').bind('submit', function () {
                var isFormValid = true;
                if (!$('#file').val())  isFormValid = false; 
                else if (!$('#folio_no').val()) isFormValid = false;
                else if (!$('#rack_no').val()) isFormValid = false;
                if (!isFormValid) alert('Please Fill all the details to proceed');
                return isFormValid;
            });
        });
		function validateFile(){
			var enteredAccNumber = document.getElementById('accNumber').value;
			var filePath = document.getElementById('file').value;
			var fileName = filePath.replace(/^.*[\\\/]/, '');
			var enteredAccNumber=enteredAccNumber+".pdf";
			if(enteredAccNumber!=fileName)
			{
				alert("File name and Loan Number mismatch. Upload correct document.");
				document.getElementById('file').value='';
			}
		}
		
    </script>
	<br/><br/>
<div>

   <form id="formid" class="pure-form pure-form-aligned" action="uploadDocumentAction.php" method="post" enctype="multipart/form-data">
   <div class="pure-control-group">
            <label for="accNumber" >Account Number</label>
            <input type="text" id="accNumber" name="accNumber" autocomplete="off" onkeydown="if (event.keyCode == 13) accountNumButtonClick()" />
            <!--<span><button name="accButton" onclick="accountNumButtonClick()">Go</button></span> -->
            <a id="getAccountDetailsSpan" href="#"  style="visibility: hidden" onclick="showAccountDetails()">View Details</a> 
    </div>
	<!--div  barcode gen JS method>
	<a id="printBarcode" href="#" onclick="print()" ><div id="accBarcode"></div></a>
	</div-->
	<div>
		<iframe id="barcodeIFrame" frameBorder="0" scrolling="no" style="height:4em;width:15em; padding-left:10em;display:none" marginheight="0" marginwidth="0" frameborder="0" src=''></iframe>
	<br>
	</div>
	
    <div class="pure-control-group">
		<label for="file">Choose file</label>
		<input id="file" type="file" name="file" disabled="disabled" onchange="validateFile()" />
	</div>
	<div class="pure-control-group">
		<label for="folio_no">Folio No.</label>
		<input id="folio_no" type="text" name="folio_no" disabled="disabled" autocomplete="off"/>
	</div>
	<div class="pure-control-group">
		<label for="rack_no">Rack Location</label>
		<input id="rack_no" type="text" name="rack_no" disabled="disabled" autocomplete="off" onkeydown="if (event.keyCode == 13) showRackBarcode()" />
	</div>
	<div>
		<iframe id="rackIFrame" frameBorder="0" scrolling="no" style="height:4em;width:15em; padding-left:10em;display:none" marginheight="0" marginwidth="0" frameborder="0" src=''></iframe>
	<br>
	</div>
	<div class="pure-controls">
		<button class="pure-button pure-button-primary" id="formButton" disabled="disabled">Submit</button>
	</div>
	</form>
    

</div>

</body>
</html>
