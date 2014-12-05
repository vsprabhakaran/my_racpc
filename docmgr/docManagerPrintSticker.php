<?php
    session_start();
    if( $_SESSION["role"] != "RACPC_ADMIN" && $_SESSION["role"] != "RACPC_DM")
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
        <link rel="stylesheet" href="css/styles.css">
        <link rel="stylesheet" href="../css/inputFormStyles.css" type="text/css">
        <link rel="stylesheet" href="../css/pure-min.css">
        <script type="text/javascript" src="../jquery-latest.min.js"></script>
        <script type="text/javascript">
            var isRackUpdateRequested = -1;
            function reset() {
                $("#rack").val("");
                document.getElementById('getAccountDetailsSpan').style.visibility = "hidden";
                document.getElementById("barcodeIFrame").style.display = "none";
                document.getElementById("rackIFrame").style.display = "none";
                document.getElementById('accNumber').style.backgroundColor = "";
                
            }
            function accountNumButtonClick() {
                resetForm();
                var enteredAccNumber = document.getElementById('accNumber').value;
                if (enteredAccNumber == "") {
                    nullAccountNumberEnterred();
                    return;
                }
                var msg = doPOST_Request('../db/accountInformations.php', enteredAccNumber, 'isValidAccount');
                if (msg == "true") {
                    validAccountNumberEnterred();
                }
                else if (msg == "false") {
                    invalidAccountNumberEnterred();
                }
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
                var loanStatus = doPOST_Request('../db/accountInformations.php', enteredAccNumber, 'isLoanActive');
                if (loanStatus == 'true') {
                    var rackNo = doPOST_Request('../db/accountInformations.php', enteredAccNumber, 'GetRackNumberOfAccount');
                    if (rackNo != "") {
                        document.getElementById("barcodeIFrame").setAttribute('src', "../barcodegit/test.php?text=" + document.getElementById("accNumber").value);
                        $("#rack").val(rackNo);
                        $("#rackIFrame").prop("src", "../barcodegit/test.php?text=" + rackNo);
                        $('.accountNumberBarcode').css("display", "block");
                        $('.rackNumberBarcode').css("display", "block");
                        document.getElementById("rackIFrame").style.display = "block";
                        document.getElementById('getAccountDetailsSpan').style.visibility = "visible";
                        document.getElementById('accNumber').style.backgroundColor = "#CCFFCC";
                        $('.editAnchor').css('visibility', 'visible');
                    }
                    else if (rackNo == "false") {
                        alert("Rack Number not found");
                        reset();
                    }
                }
                else if (loanStatus == 'false') {
                    reset();
                    document.getElementById('accNumber').style.backgroundColor = "#FFC1C1";
                    alert("Loan is not active.");
                }
                document.getElementById('getAccountDetailsSpan').style.visibility = "visible";
            }
            function showRackBarcode() {
                document.getElementById("rackIFrame").setAttribute('src', "../barcodegit/test.php?text=" + document.getElementById("rack").value);
                document.getElementById("rackIFrame").style.display = "block";
            }
            function invalidAccountNumberEnterred() {
                document.getElementById('accNumber').style.backgroundColor = "#FFC1C1";

            }
            function nullAccountNumberEnterred() {
                document.getElementById('accNumber').style.backgroundColor = "";

            }
            function showAccountDetails() {
                var enteredAccNumber = document.getElementById('accNumber').value;
                var popup = window.open("../AccountDetailsWindow.php?accNo=" + enteredAccNumber, "Details", "resizable=1,scrollbars=1,height=325,width=280,left = " + (document.documentElement.clientWidth - 300) + ",top = " + (225));
                $(popup).blur(function () { this.close(); });
            }
            function resetForm() {
                document.getElementById('getAccountDetailsSpan').style.visibility = "hidden";
                $(".accountNumberBarcode").css("display", "none");
                $(".rackNumberBarcode").css("display", "none");
                $("#rack").val("");
                $(':button').css('visibility', 'hidden');
                $('.editAnchor').css('visibility', 'hidden');
                $('#actionType').val("none");
            }
            function RacKEditClick() {
                if (isRackUpdateRequested != -1) isRackUpdateRequested = 1;
                $("#rack").prop('disabled', false);
                $(':button').css('visibility', 'visible');
            }
        </script>
    </head>
    <body>

        <script type="text/javascript">
            $(document).ready(function () {
                $('.accountNumberBarcode').css("display", "none");
                $('.rackNumberBarcode').css("display", "none");
                $(':button').css('visibility', 'hidden');
                $('.editAnchor').css('visibility', 'hidden');
                $('#formid').bind("keyup keypress", function (e) {
                    var code = e.keyCode || e.which;
                    if (code == 13) {
                        e.preventDefault();
                        return false;
                    }
                });
                $('#formid').bind('submit', function () {
                    var isFormValid = false;
                    if (isRackUpdateRequested) {
                        if ($('#rack').val()) {
                            isFormValid = true;
                            $('#actionType').val("rackUpdate");
                        }
                        if (!isFormValid) alert("Please enter new Rack number.");
                    }
                    return isFormValid;
                });
            });
        </script>
 <br><br>
        <div>
            <form id="formid" class="pure-form pure-form-aligned" action="updateRack.php" method="post">
                <div class="pure-control-group">
                    <label for="accNumber">Account Number</label>
                    <input type="text" id="accNumber" name="accNumber" autocomplete="off" onKeyDown="if (event.keyCode == 13) accountNumButtonClick()" onblur="accountNumButtonClick()" />
                    <a id="getAccountDetailsSpan" href="#" style="visibility: hidden" onClick="showAccountDetails()">View Details</a>
                </div>
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
                    <br>
                </div>
                <div class="pure-control-group">
                    <label for="rack">Rack Location</label>
                    <input type="text" id="rack" name="rack" autocomplete="off" disabled="disabled" onBlur="showRackBarcode()" />
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
                    <input type="hidden" name="actionType" value="none" id="actionType"/>
                    <button class="pure-button pure-button-primary" type="submit" id="submitNewRackLocation" name="submitNewRackLocation">Update Rack</button>
                </div>
            </form>

        </div>

    </body>
</html>
<?php
    }
?>