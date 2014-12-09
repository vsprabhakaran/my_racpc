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
<!DOCTYPE html>
<html lang="">
    <head>
        <link rel="stylesheet" href="../css/inputFormStyles.css" type="text/css">
        <link rel="stylesheet" href="../css/pure-min.css">
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
                resetForm();
                var enteredAccNumber = document.getElementById('accNumber').value;
                if (enteredAccNumber == "") {
                    nullAccountNumberEnterred();
                    return;
                }

                if (isValidAccount(enteredAccNumber)) { validAccountNumberEnterred(enteredAccNumber); }
                else { invalidAccountNumberEnterred(); return; }
            }
            function validAccountNumberEnterred(enteredAccNumber) {
                //Resetting some elements before actions
                $.ajax({
                    type: 'POST',
                    url: '../db/accountInformations.php',
                    data: { accNo: enteredAccNumber, type: 'isLoanActive' },
                    success: function (msg) {
                        if (msg == "true" || (msg == "false" && !isLoanInADMS(enteredAccNumber))) {
                            
                            document.getElementById("barcodeIFrame").setAttribute('src', "../barcodegit/test.php?text=" + document.getElementById("accNumber").value);
                            $('.accountNumberBarcode').css("display", "block");
                            document.getElementById('accNumber').style.backgroundColor = "#CCFFCC";
                            $('#formButton').prop('disabled', false);
                            $('#generateFP').prop('disabled', false);
                            document.getElementById('getAccountDetailsSpan').style.visibility = "visible";
                            //$("#accBarcode").barcode(enteredAccNumber, "code128",{barWidth:2, barHeight:30});
                            populateFields(enteredAccNumber);
                            showAccountDetailsIFrame();
                        }
                        else {

                            document.getElementById('accNumber').style.backgroundColor = "#FFC1C1";
                    alert("You are trying to access a closed loan!");
                        }
                    },
                    error: function (msg) { alert("fail : " + msg); },
                    async: false
                });



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
                $('.accountNumberBarcode').css("display", "none");
                $('.rackNumberBarcode').css("display", "none");
			$("#pdfFile").prop("src","");
			$("#pdfFile").css({ "visibility": "hidden" });
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
            function showAccountDetailsIFrame() {
                var enteredAccNumber = document.getElementById('accNumber').value;
                $("#pdfFile").prop("src", "../AccountDetailsWindow.php?accNo=" + enteredAccNumber);
                $("#pdfFile").css({ "visibility": "visible" });
            }

            function isValidAccount(accountNumber) {
                var result = doPOST_Request(dbURL, accountNumber, 'isValidAccount');
                if (result == "true") return true;
                else false;
            }
            /* function showRackBarcode(){
            var rack = $("#rack_no").val();
            alert(rack);
            $("#rackBarcode").barcode(rack, "code128", { barWidth: 2, barHeight: 30 });
            } */
            function isLoanInADMS(accountNumber) {
                var result = doPOST_Request(dbURL, accountNumber, 'isLoanAccountInADMS');
                if (result == "true") return true;
                else false;
            }
            function populateFields(accountNumber) {

                docStatus = doPOST_Request(dbURL, accountNumber, 'GetDocumentStatusOfAccount');
                var enteredAccNumber = document.getElementById('accNumber').value;
                var loanStatus;
                $.ajax({
                    type: 'POST',
                    url: '../db/accountInformations.php',
                    data: { accNo: enteredAccNumber, type: 'isLoanActive' },
                    success: function (msg) {
                        if (msg == "true") {
                            loanStatus = 'A';
                        }
                        else if (msg == "false") {
                            loanStatus = 'C';
                        }
                    },
                    error: function (msg) { alert("fail : " + msg); },
                    async: false
                });

                //Enable the fiels for editing coz the documnet and location details are not present
                if (docStatus == "A" || !(isLoanInADMS(accountNumber))) {
                    whichAction = ACTION_TYPE.NewDocument; $('#actionTypeField').val("NewDocument");
                    $('#folio_no').prop('disabled', false);
                    $('#rack_no').prop('disabled', false);
                    document.getElementById("newDocDiv").style.display = "block";
                    return;
                }
                else if (loanStatus == "C") {    //The loan is closed so no need to get or populate the details regarding this loan

                    invalidAccountNumberEnterred();
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
                        if (msg != "") returnMsg = msg.replace(/["'\\]/g, "");
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
                        var branchCode = msg.replace(/["']/g, "");
                        document.getElementById("pdfFile").setAttribute('src', "../docBuffer.php?accNo=" + enteredAccNumber);
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
                else if ((whichAction & ACTION_TYPE.OldDocRackChange)) {
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
                else if ((whichAction & ACTION_TYPE.OldDocFolioChange)) {
                    $("#rack_no").prop('disabled', false);
                    whichAction |= ACTION_TYPE.OldDocRackChange; $('#actionTypeField').val("OldDocRackChange,OldDocFolioChange");
                }
                else alert("invalid action!!!");
            }
            function showRackBarcode() {
                document.getElementById("rackIFrame").setAttribute('src', "../barcodegit/test.php?text=" + document.getElementById("rack_no").value);
                $('.rackNumberBarcode').css("display", "block");
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
			 function checkFolioNumber() {
                var folioNumber = $('#folio_no').val();
                var pattern = /^[a-z0-9\/]+$/i;
                return pattern.test(folioNumber);
            }
        </script>
    </head>
    <body style="margin: 0">
        <script type="text/javascript">
            $(document).ready(function () {
                resetForm();
                $('#formid').bind("keyup keypress", function (e) {
                    var code = e.keyCode || e.which;
                    if (code == 13) {
                        e.preventDefault();
                        return false;
                    }
                });
                $('#formid').bind('submit', function () {
                    var isFormValid = true;
                    var alertMsg = "Please fill all the details to proceed."
                    if (whichAction == ACTION_TYPE.NewDocument) {
                        if (!$('#file').val()) isFormValid = false;
                        else if (!$('#folio_no').val()) isFormValid = false;
                        else if (!$('#rack_no').val()) isFormValid = false;
            
                    }
                    else if (whichAction == ACTION_TYPE.OldDocDocumentChange) {
                        if (!$('#file').val()) isFormValid = false;
                        alertMsg = "Please choose the document to upload."
                    }
                    else if ((whichAction & ACTION_TYPE.OldDocFolioChange) && (whichAction & ACTION_TYPE.OldDocRackChange)) {
                        if ((!$('#folio_no').val()) || (!$('#rack_no').val())) isFormValid = false;
                        alertMsg = "Please enter new folio and rack number.";
						if (isFormValid) {
                            isFormValid = checkFolioNumber();
                            if(!isFormValid) alertMsg = "Folio Number can be alpanumeric and only symbol allowed is / (forward slash)";
                        }
                    }
                    else if (whichAction == ACTION_TYPE.OldDocFolioChange) {
                        if (!$('#folio_no').val()) isFormValid = false;
                        alertMsg = "Please enter new folio number.";
						if (isFormValid) {
                            isFormValid = checkFolioNumber();
                            if(!isFormValid) alertMsg = "Folio Number can be alpanumeric and only symbol allowed is / (forward slash)";
                        }
                    }
                    else if (whichAction == ACTION_TYPE.OldDocRackChange) {
                        if (!$('#rack_no').val()) isFormValid = false;
                        alertMsg = "Please enter new Rack number."
                    }
                    else if (whichAction == ACTION_TYPE.OldDocNoChange) {
                        isFormValid = false;
                        alertMsg = "Nothing to do."
                    }
                    if (!isFormValid) {
                        alert(alertMsg);
                        return isFormValid;
                    }
                });
            });
            function validateFile() {
                var enteredAccNumber = document.getElementById('accNumber').value;
                var filePath = document.getElementById('file').value;
                var fileName = filePath.replace(/^.*[\\\/]/, '');
                var enteredAccNumber = enteredAccNumber + ".pdf";
                if (enteredAccNumber != fileName) {
                    alert("File name and Loan Number mismatch. Upload correct document.");
                    //Resetting the file element using wrap/unwrapping - refer stackoverflow.
                    $('#file').wrap('<form>').closest('form').get(0).reset();
                    $('#file').unwrap();
                }

            }
            function generateFPFunc() {
                var number = document.getElementById('accNumber').value;
                //$("#genLink").prop("href", "firstPage/generate.php?accNo=" + number);
                $("#pdfFile").prop("src", "firstPage/generate.php?accNo=" + number);
            }
        </script>
        <br />
        <br />
        <table style="width: 100%" border="0" cellspacing="0" cellpadding="0">
            <tr>
                <td style="height:15em;width:50%;">
            <div>
                        <form id="formid" class="pure-form pure-form-aligned" action="uploadDocumentAction.php" method="post" enctype="multipart/form-data">
                            <div class="pure-control-group">
                                <label for="accNumber">Account Number</label>
            <input type="text" id="accNumber" name="accNumber" autocomplete="off" onKeyDown="if (event.keyCode == 13) accountNumButtonClick()" onBlur="accountNumButtonClick()" />
                                <!--<span><button name="accButton" onclick="accountNumButtonClick()">Go</button></span> -->
                                <a id="getAccountDetailsSpan" href="#" style="visibility: hidden" onClick="showAccountDetails()">View Details</a>
                            </div>
                                                            <!--div  barcode gen JS method>
                                                     <a id="printBarcode" href="#" onclick="print()" ><div id="accBarcode"></div></a>
                                                     </div-->
                            <div>
                                <table border="0">
                                    <tr>
                                        <td>
                                            <iframe id="barcodeIFrame" class="accountNumberBarcode" frameborder="0" scrolling="no" style="height:4em;width:15em; padding-left:10em;display:none" marginheight="0" marginwidth="0" frameborder="0" src=""></iframe>
                                        </td>
                                        <td>
                                            <img src="../img/print_icon.jpg" class="accountNumberBarcode" style="height: 2em;width: 2em;padding:1ex 1ex 0ex 1ex;" alt="print" onClick="window.frames['barcodeIFrame'].focus();window.frames.print();" />
                                        </td>
                                    </tr>
                                </table>
                                <br />
                            </div>
                            <div class="pure-control-group" id="newDocDiv" style="display: none">
                                <label for="file">Choose file</label>
                                <input id="file" type="file" name="file" onChange="validateFile()" />
                            </div>
                            <div class="pure-control-group" id="viewDocDiv" style="display:none">
                                <label for="fileURL">Document </label>
                                <a id="viewDocumentURL" href="#" onClick="showDocument()">View Document</a>
                            </div>
                            <div class="pure-control-group">
                                <label for="folio_no">Folio No</label>
                                <input id="folio_no" type="text" name="folio_no" disabled="disabled" autocomplete="off" style="color: #000" />
                                <a class="editAnchor" href="#" onClick="FolioEditClick()"><img class="editImg" src="../img/write.png" alt="edit" /></a>
                            </div>
                            <div class="pure-control-group">
                                <label for="rack_no">Rack Location</label>
                                <input id="rack_no" type="text" name="rack_no" disabled="disabled" autocomplete="off" onKeyDown="if (event.keyCode == 13) showRackBarcode()" style="color: #000" />
                                <a class="editAnchor" href="#" onClick="RacKEditClick()"><img class="editImg" src="../img/write.png" alt="edit" /></a>
                            </div>
                            <div>
                                <table border="0">
                                    <tr>
                                        <td>
                                            <iframe id="rackIFrame" class="rackNumberBarcode" frameborder="0" scrolling="no" style="height:4em;width:15em; padding-left:10em;display:none" marginheight="0" marginwidth="0" frameborder="0" src=""></iframe>
                                        </td>
                                        <td>
                                            <img src="../img/print_icon.jpg" class="rackNumberBarcode" style="height: 2em;width: 2em;padding:1ex 1ex 0ex 1ex;" alt="print" onClick="window.frames['rackIFrame'].focus();window.frames.print();" />
                                        </td>
                                    </tr>
                                </table>
                                <br>
                            </div>
                            <div class="pure-controls">
            <button class="pure-button pure-button-primary" id="formButton" type="submit" disabled="disabled" >Submit</button>
                                 <button class="pure-button pure-button-primary" id="generateFP" type="button" disabled="disabled" onClick="generateFPFunc()" >Generate</button>
                            </div>
                            <input type="hidden" name="actionTypeField" id="actionTypeField" value="null" />
                        </form>
                    </div>
                </td>
                <td style="height:100%;width:50%;" rowspan="2">
                    <iframe id="pdfFile"  frameBorder="0"  marginheight="0" marginwidth="0"  style="visibility: hidden;height:30em;width:100%;"> </iframe>
                </td>
            </tr>
            <tr>
                <td style="height:10em;width:50%;">&nbsp;</td>
            </tr>
        </table>


    </body>
</html>
<?php
    }
?>
