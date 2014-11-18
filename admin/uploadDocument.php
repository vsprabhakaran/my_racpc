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
        var dbURL = '../db/accountInformations.php';
        var docStatus_var = "0";
        var noOfDocs_var = -1;
        var folioNumber_var = "0";
        var rackNumber_var = "0";
        var whichAction = -1;
        var ACTION_TYPE = {
            NewDocument: 0,
            OldDocNoChange: 1,
            OldDocRackChange: 2,
            OldDocFolioChange: 4,
            OldDocDocumentChange: 8
        };
        function accountNumButtonClick() {

            var enteredAccNumber = document.getElementById('accNumber').value;
            if (enteredAccNumber == "") {
                nullAccountNumberEnterred();
                return;
            }

            if (isValidAccount(enteredAccNumber)) { validAccountNumberEnterred(enteredAccNumber); }
            else { invalidAccountNumberEnterred(); return; }
            //Hiding the pdf file whenever the account number is changed. Resetting the document actions as well.
            document.getElementById("pdfFile").style.visibility = "hidden";

        }
        function validAccountNumberEnterred(enteredAccNumber) {
            //Resetting some elements before actions
			document.getElementById("barcodeIFrame").setAttribute('src',"../barcodegit/test.php?text="+ document.getElementById("accNumber").value);
			document.getElementById("barcodeIFrame").style.display="block";
            resetForm();
            document.getElementById('getAccountDetailsSpan').style.visibility = "visible";
            document.getElementById('accNumber').style.backgroundColor = "#CCFFCC";
            $('#formButton').prop('disabled', false);
			//$("#accBarcode").barcode(enteredAccNumber, "code128",{barWidth:2, barHeight:30});
            populateFields(enteredAccNumber);


        }
        function cleanFormValues() {
            $('#folio_no').val("");
            $('#rack_no').val("");
            $('#file').val("");
        }
        function resetForm() {
            cleanFormValues();
            $('#folio_no').prop('disabled', true);
            $('#rack_no').prop('disabled', true);
            $('#formButton').prop('disabled', true);
            $('#actionTypeField').val("null");
            document.getElementById('getAccountDetailsSpan').style.visibility = "hidden";
            document.getElementById("viewDocDiv").style.display = "none";
            document.getElementById("newDocDiv").style.display = "none";
            $('.editAnchor').css({ "visibility": "hidden" });
            whichAction = -1;
        }
        function invalidAccountNumberEnterred() {
            document.getElementById('accNumber').style.backgroundColor = "#FFC1C1";
            resetForm();
        }
        function nullAccountNumberEnterred() {
            document.getElementById('accNumber').style.backgroundColor = "";
            resetForm();
        }
        function showAccountDetails() {
            var enteredAccNumber = document.getElementById('accNumber').value;
            var popup = window.open("../AccountDetailsWindow.php?accNo=" + enteredAccNumber, "Details", "resizable=1,scrollbars=1,height=325,width=280,left = " + (document.documentElement.clientWidth - 300) + ",top = " + (225));
            $(popup).blur(function () { this.close(); });
        }


        function isValidAccount(accountNumber) {
            var result = doPOST_Request(dbURL, accountNumber, 'isValidAccount');
            if (result == "true") return true;
            else false;
        }
		/* function showRackBarcode(){
			var rack= $("#rack_no").val();
			alert(rack);
			$("#rackBarcode").barcode(rack, "code128",{barWidth:2, barHeight:30});
		} */
        function isLoanInADMS(accountNumber) {
            var result = doPOST_Request(dbURL, accountNumber, 'isLoanAccountInADMS');
            if (result == "true") return true;
            else false;
        }
        function populateFields(accountNumber) {

            docStatus = doPOST_Request(dbURL, accountNumber, 'GetDocumentStatusOfAccount');

            //Enable the fiels for editing coz the documnet and location details are not present
            if (docStatus == "A" || !(isLoanInADMS(accountNumber))) {
                whichAction = ACTION_TYPE.NewDocument; $('#actionTypeField').val("NewDocument");
                $('#folio_no').prop('disabled', false);
                $('#rack_no').prop('disabled', false);
                document.getElementById("newDocDiv").style.display = "block";
                return;
            }
            else if (docStatus == "C") {    //The loan is closed so no need to get or populate the details regarding this loan
                alert("This loan is already closed!!");
                nullAccountNumberEnterred();
                return;
            }
            else if (docStatus == "IN" || docStatus == "OUT") {
                noOfDocs_var = doPOST_Request(dbURL, accountNumber, 'GetNoOfFilesForAccount');
                if (noOfDocs_var == 1) {
                    whichAction = ACTION_TYPE.OldDocNoChange; $('#actionTypeField').val("OldDocNoChange");
                    $('.editAnchor').css({ "visibility": "visible" });
                    document.getElementById("viewDocDiv").style.display = "block";
                }
                else {
                    whichAction = ACTION_TYPE.OldDocDocumentChange; $('#actionTypeField').val("OldDocDocumentChange");
                    document.getElementById("newDocDiv").style.display = "block";
                }
            }
            folioNumber_var = doPOST_Request(dbURL, accountNumber, 'GetFolioNumberOfAccount');
            $("#folio_no").val(folioNumber_var);
            rackNumber_var = doPOST_Request(dbURL, accountNumber, 'GetRackNumberOfAccount');
            $("#rack_no").val(rackNumber_var);
        }
        function doPOST_Request(phpURL, accNumber, typeCall) {
            var returnMsg = '';
            $.ajax({
                type: 'POST',
                url: phpURL,
                data: { accNo: accNumber, type: typeCall },
                success: function (msg) {
                    if (msg != "") returnMsg = msg.replace(/["']/g, "");
                    else alert("not Found");
                    if (msg == "false") returnMsg = "NA";
                },
                error: function (msg) { alert("fail : " + msg); },
                async: false
            });
            return returnMsg;
        }
        function showDocument() {
            //document.getElementById("pdfFile").setAttribute('src', "..\\uploads\\" + document.getElementById("accNumber").value + ".pdf");
            var enteredAccNumber = document.getElementById('accNumber').value;
				$.post('../db/accountInformations.php', { accNo: enteredAccNumber, type: 'GetBranchCodeOfAccount' }, function (msg) {
                if (msg != "") {
                    var branchCode=msg.replace(/["']/g, "");
					document.getElementById("pdfFile").setAttribute('src',"../docBuffer.php?accNo="+enteredAccNumber);
                document.getElementById("pdfFile").style.visibility = "visible";
                }
                else if (msg == "false") {
                    alert("Branch code not found");
                }
            }).fail(function (msg) {
                alert("fail : " + msg);
            });
        }
        function FolioEditClick() {
            if (whichAction == -1) alert("cannot modify FolioNumber");
            if (whichAction == ACTION_TYPE.OldDocNoChange) {
                $("#folio_no").prop('disabled', false);
                whichAction = ACTION_TYPE.OldDocFolioChange;
                $('#actionTypeField').val("OldDocFolioChange");
            }
            else if ((whichAction & ACTION_TYPE.OldDocRackChange) ) {
                $("#folio_no").prop('disabled', false);
                whichAction |= ACTION_TYPE.OldDocFolioChange; $('#actionTypeField').val("OldDocRackChange,OldDocFolioChange");
            }
            else alert("invalid action!!!");
        }
        function RacKEditClick() {
            if (whichAction == -1) alert("cannot modify RackNumber");
            if (whichAction == ACTION_TYPE.OldDocNoChange) {
                $("#rack_no").prop('disabled', false);
                whichAction = ACTION_TYPE.OldDocRackChange;
                $('#actionTypeField').val("OldDocRackChange");
                //$('.editAnchor').css({ "visibility": "hidden" });
            }
            else if ((whichAction & ACTION_TYPE.OldDocFolioChange) ) {
                $("#rack_no").prop('disabled', false);
                whichAction |= ACTION_TYPE.OldDocRackChange; $('#actionTypeField').val("OldDocRackChange,OldDocFolioChange");
            }
            else alert("invalid action!!!");
        }
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
          $('.editAnchor').css({ "visibility":"hidden" });
      
          $('#formid').bind("keyup keypress", function(e) {
            var code = e.keyCode || e.which; 
            if (code  == 13) {               
              e.preventDefault();
              return false;
            }
          });
          $('#formid').bind('submit', function () {
              var isFormValid = true;
              var alertMsg = "Please fill all the details to proceed."
              if (whichAction == ACTION_TYPE.NewDocument) {
              if (!$('#file').val())  isFormValid = false; 
              else if (!$('#folio_no').val()) isFormValid = false;
              else if (!$('#rack_no').val()) isFormValid = false;
      
              }
              else if(whichAction == ACTION_TYPE.OldDocDocumentChange)
              {
                  if (!$('#file').val()) isFormValid = false;
                  alertMsg = "Please choose the document to upload."
              }
              else if((whichAction & ACTION_TYPE.OldDocFolioChange)&&(whichAction & ACTION_TYPE.OldDocRackChange))
              {
                  if ((!$('#folio_no').val()) || (!$('#rack_no').val())) isFormValid = false;
                  alertMsg = "Please enter new folio and rack number."
              }
              else if(whichAction == ACTION_TYPE.OldDocFolioChange)
              {
                  if (!$('#folio_no').val()) isFormValid = false;
                  alertMsg = "Please enter new folio number."
              }
              else if(whichAction == ACTION_TYPE.OldDocRackChange)
              {
                  if (!$('#rack_no').val()) isFormValid = false;
                  alertMsg = "Please enter new Rack number."
              }
              else if(whichAction == ACTION_TYPE.OldDocNoChange)
              {
                  isFormValid = false;
                  alertMsg = "Nothing to do."
              }
              if (!isFormValid) {
                  alert(alertMsg);
              return isFormValid;
              }
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
   <table style="width: 100%" border="0">
      <tr>
         <td style="height:15em;width:50%;">
            <div style="height:10em">
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
                     <br/>
                  </div>
                  <div class="pure-control-group" id="newDocDiv" style="display: none">
                     <label for="file">Choose file</label>
            <input id="file" type="file" name="file" disabled="disabled" onchange="validateFile()" />
                  </div>
                  <div class="pure-control-group" id="viewDocDiv" style="display:none">
                     <label for="fileURL">Document </label>
                     <a id="viewDocumentURL" href="#"   onclick="showDocument()">View Document</a> 
                  </div>
                  <div class="pure-control-group">
                     <label for="folio_no">Folio No</label>
                     <input id="folio_no" type="text" name="folio_no" disabled="disabled" autocomplete="off" style="color: #000"/>
                     <a class="editAnchor" href="#" onclick="FolioEditClick()"><img class="editImg" src="../img/write.png"  alt="edit"/></a>
                  </div>
                  <div class="pure-control-group">
                     <label for="rack_no">Rack Location</label>
                     <input id="rack_no" type="text" name="rack_no" disabled="disabled" autocomplete="off" onkeydown="if (event.keyCode == 13) showRackBarcode()" style="color: #000"/>
                     <a class="editAnchor" href="#" onclick="RacKEditClick()"><img class="editImg" src="../img/write.png" alt="edit"/></a>
         </div>
         <div>
            <iframe id="rackIFrame" frameBorder="0" scrolling="no" style="height:4em;width:15em; padding-left:10em;display:none" marginheight="0" marginwidth="0" frameborder="0" src=''></iframe>
            <br>
                  </div>
                  <div class="pure-controls">
                     <button class="pure-button pure-button-primary" id="formButton" disabled="disabled">Submit</button>
                  </div>
                  <input type="hidden" name="actionTypeField" id="actionTypeField" value="null"/>
               </form>
            </div>
         </td>
         <td style="height:100%;width:50%;" rowspan="2">
            <iframe  id="pdfFile"  style="visibility: hidden;height:30em;width:100%;"> </iframe>
         </td>
      </tr>
      <tr>
         <td style="height:10em;width:50%;">&nbsp;</td>
      </tr>
   </table>
</body>
</html>
