<!doctype html>
<html lang=''>
<head>
   <title>Document Manager</title>
     <?php
        session_start();
        if( $_SESSION["role"] != "RACPC_DM")
        {
           $_SESSION["role"] = "";
        ?>
        <meta http-equiv="refresh" content="0;URL=../login.html">
    <?php
        }
    ?>

    <script type="text/javascript" src="../jquery-latest.min.js"></script>
    <link rel="stylesheet" href="../css/my_styles.css">
    <link rel="stylesheet" href="../css/pure-min.css">


<script>

    function outUpdateDocStatus() {
        //alert("inside updstauts");

        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else { // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                //document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
            }
        }


        var enteredAccNumber = document.getElementById('accountno').value;
        xmlhttp.open("GET", "getDocMgrdetails2.php?q=" + enteredAccNumber, true);


        // update out slip flag
        //alert("inside kar");
        $.post('getDocMgrdetails2.php', { accNo: enteredAccNumber, type: 'OutUpdateDocStatus' }, function (msg) {
            //  alert(msg);
            if (msg == "true") {
                // alert("Out status Updated Successfully");
            }
            else if (msg == "false") {
                //    document.getElementById('accNumber').style.backgroundColor = "#FFC1C1";
                //    document.getElementById('getAccountDetailsSpan').style.visibility = "hidden";
            }
        }).fail(function (msg) {
            alert("fail : " + msg);
        });
    }

    function outActivityUpdate() {
        var pf;
        $.post('../getPfnoFromSession.php', {}, function (msg) {
            if (msg != "false") {
                pf = msg.replace(/["']/g, "");
            }
        }).fail(function (msg) {
            alert("fail : " + msg);
        });
        //alert("inside updstauts");

        if (window.XMLHttpRequest) {
            // code for IE7+, Firefox, Chrome, Opera, Safari
            xmlhttp = new XMLHttpRequest();
        } else { // code for IE6, IE5
            xmlhttp = new ActiveXObject("Microsoft.XMLHTTP");
        }
        xmlhttp.onreadystatechange = function () {
            if (xmlhttp.readyState == 4 && xmlhttp.status == 200) {
                //document.getElementById("txtHint").innerHTML = xmlhttp.responseText;
            }
        }


        var enteredAccNumber = document.getElementById('accountno').value;
        var borrower_pf_index = document.getElementById('pfnorcv').value;
        var login_pf_index = pf;
        var slip_type = 'OUT';
        var reason = document.getElementById('reason').value;
        var phno = '';
        xmlhttp.open("GET", "getDocMgrdetails2.php?q=" + enteredAccNumber, true);


        // update out slip flag
        //alert("inside kar");
        $.post('getDocMgrdetails2.php', { accNo: enteredAccNumber, type: 'OutUpdateDocStatus' }, function (msg) {
            //  alert(msg);
            if (msg == "true") {
                // alert("Out status Updated Successfully");
            }
            else if (msg == "false") {
                //    document.getElementById('accNumber').style.backgroundColor = "#FFC1C1";
                //    document.getElementById('getAccountDetailsSpan').style.visibility = "hidden";
            }
        }).fail(function (msg) {
            alert("fail : " + msg);
        });
    }
    function showdetails(str) {
        var enteredAccNumber = $('#accountno').val();
        if (enteredAccNumber == "") {
            nullAccountNumberEnterred();
            return;
        }
        var phpURL = '../db/accountInformations.php';
        $.post(phpURL, { accNo: enteredAccNumber, type: 'isValidAccount' }, function (msg) {
            if (msg == "true") {
                validAccountNumberEnterred();
            }
            else if (msg == "false") {
                invalidAccountNumberEnterred();
                return;
            }
        }).fail(function (msg) {
            alert("fail : " + msg);
        });

        document.getElementById('accountname').value = doPOST_Request(phpURL, enteredAccNumber, "GetAccountNameOfAccount"); ;
        document.getElementById('brcode').value = doPOST_Request(phpURL, enteredAccNumber, "GetBranchCodeOfAccount");
        document.getElementById('foliono').value = doPOST_Request(phpURL, enteredAccNumber, "GetFolioNumberOfAccount").replace(/["'\\]/g, "");
        document.getElementById('brname').value = doPOST_Request(phpURL, enteredAccNumber, "GetBranchNameOfAccount");
    }
    function showUdetails() {
        var enteredPFNumber = $('#pfnorcv').val();
        //alert(enteredPFNumber);
        if (enteredPFNumber == "") {
            nullPfNumberEnterred();
            return;
        }
        var phpURL = '../db/UserInformations.php';
        $.post(phpURL, { pfno: enteredPFNumber, type: 'isValidUser' }, function (msg) {
            if (msg == "true") {
                validPFNumberEnterred();
            }
            else if (msg == "false") {
                invalidPFNumberEnterred();
                return;
            }
        }).fail(function (msg) {
            alert("fail : " + msg);
        });

        document.getElementById('nameofReciver').value = doPOST_RequestUser(phpURL, enteredPFNumber, "GetUserName");
        //alert(document.getElementById('nameofGiver').value);

    }
    function showUserDetails() {
        var enteredPFNumber = document.getElementById('pfnorcv').value;
        //alert(enteredAccNumber);
        var popup = window.open("../UserDetailsWindow.php?pfNo=" + enteredPFNumber, "Details", "resizable=1,scrollbars=1,height=325,width=280,left = " + (document.documentElement.clientWidth - 300) + ",top = " + (225));
        $(popup).blur(function () { this.close(); });
    }
    function showAccountDetails() {
        var enteredAccNumber = document.getElementById('accountno').value;
        //alert(enteredAccNumber);
        var popup = window.open("../AccountDetailsWindow.php?accNo=" + enteredAccNumber, "Details", "resizable=1,scrollbars=1,height=325,width=280,left = " + (document.documentElement.clientWidth - 300) + ",top = " + (225));
        $(popup).blur(function () { this.close(); });
    }
    function resetForm() {
        $('#genInslip').prop('disabled', true);
        $('#reason').prop('disabled', true);
        $('#accountname').val("");
        $('#brcode').val("");
        $('#brname').val("");
        $('#foliono').val("");
        $('#reason').val("");
        $('#pfnorcv').val("");
        $('#pfnorcv').prop('disabled', true);
        $('#accountno').val("");
        $('#nameofReciver').val("");

        document.getElementById('slip_upload_frame').src = "";
    }
    function invalidAccountNumberEnterred() {
        document.getElementById('accountno').style.backgroundColor = "#FFC1C1";
        document.getElementById('getAccountDetailsSpan').style.visibility = "hidden";
        resetForm();
    }
    function nullAccountNumberEnterred() {
        document.getElementById('accountno').style.backgroundColor = "";
        document.getElementById('getAccountDetailsSpan').style.visibility = "hidden";
        resetForm();
    }
    function validAccountNumberEnterred() {
        document.getElementById('getAccountDetailsSpan').style.visibility = "visible";
        document.getElementById('accountno').style.backgroundColor = "#CCFFCC";
        $('#pfnorcv').prop('disabled', false);
        $('#genInslip').prop('disabled', false);
        $('#reason').prop('disabled', false);
    }
    function invalidPFNumberEnterred() {
        document.getElementById('pfnorcv').style.backgroundColor = "#FFC1C1";
        document.getElementById('getUserDetailsSpan').style.visibility = "hidden";

    }
    function nullPFNumberEnterred() {
        document.getElementById('pfnogiver').style.backgroundColor = "";
        document.getElementById('getUserDetailsSpan').style.visibility = "hidden";

    }
    function validPFNumberEnterred() {
        document.getElementById('getUserDetailsSpan').style.visibility = "visible";
        document.getElementById('pfnogiver').style.backgroundColor = "#CCFFCC";
    }
    function doPOST_Request(phpURL, pfNumber, typeCall) {
        var returnMsg = '';
        $.ajax({
            type: 'POST',
            url: phpURL,
            data: { accNo: pfNumber, type: typeCall },
            success: function (msg) {
                if (msg != "") returnMsg = msg.replace(/["']/g, "");
                else alert("not Found");
            },
            error: function (msg) { alert("fail : " + msg); },
            async: false
        });
        return returnMsg;
    }
    function doPOST_RequestUser(phpURL, enteredPFNumber, typeCall) {
        var returnMsg = '';
        $.ajax({
            type: 'POST',
            url: phpURL,
            data: { pfno: enteredPFNumber, type: typeCall },
            success: function (msg) {
                if (msg != "") { returnMsg = msg.replace(/["']/g, ""); }
                else alert("not Found");
            },
            error: function (msg) { alert("fail : " + msg); },
            async: false
        });
        return returnMsg;

    }
</script>

</head>
<body>
     <script type="text/javascript">
        $(document).ready(function () {
            $('#genOutSlip').bind("keyup keypress", function (e) {
                var code = e.keyCode || e.which;
                if (code == 13) {
                    e.preventDefault();
                    return false;
                }
            });
            $('#genOutSlip').bind('submit', function () {
                /*var isFormValid = true;
                if (!$('#file').val())  isFormValid = false; 
                else if (!$('#folio_no').val()) isFormValid = false;
                else if (!$('#rack_no').val()) isFormValid = false;
                if (!isFormValid) alert('Please Fill all the details to proceed');
                return isFormValid;*/
            });
        });
    </script>

<div>
   



 <br/>
   <br/>
    <table border="0" style="width: 100%;height: 100%"><tr><td style="width: 50%">
<form name="genOutSlip" id="genOutSlip" action="genOutSlip.php" class="pure-form pure-form-aligned" method="POST" target="slip_upload_frame">
    <div class="pure-control-group"> 
        <label for="accountno" >Account Number :</label>
        <input type="text" name="accountno" id="accountno" onkeydown="if (event.keyCode == 13) showdetails()" /> 
        <a id="getAccountDetailsSpan" href="#"  style="visibility: hidden" onclick="showAccountDetails()">View Details</a>
</div>

    <div class="pure-control-group">
        <label for="accountname" >Account Holder Name:</label>
        <input type="text" id="accountname" name="accountname" readonly="true"/> 
    </div>

     <div class="pure-control-group">
        <label for="brcode" >Branch Code :</label>
        <input type="text" id="brcode" name="brcode" readonly="true" /> 
     </div>
     <div class="pure-control-group">
        <label for="brcode" >Branch Name :</label> 
        <input type="text" id="brname" name="brname" readonly="true" /> 
     </div>
    <div class="pure-control-group">
        <label for="foliono" > Folio number :</label>
        <input type="text" id="foliono" name="foliono" readonly="true" />
</div> 
    <div class="pure-control-group">
        <label for="pfnorcv" >  Receivers's PF Number :</label>

        <input type="text" name="pfnorcv" id="pfnorcv" onKeyDown="if (event.keyCode == 13) showUdetails()" />
        <a id="getUserDetailsSpan" href="#"  style="visibility: hidden" onclick="showUserDetails()">View Details</a>

    </div>
    <div class="pure-control-group">
        <label for="nameofGiver" > Name of the Reciver :</label>
        <input type="text" id="nameofReciver" name="nameofReciver" readonly="true" />
    </div>
    <div class="pure-control-group">
        <label for="reason" > Reason :</label>
        <textarea rows="4" cols="23" name="reason" id="reason" disabled="disabled"> </textarea>  
     </div>      
    
<div class="pure-controls">
<input class="pure-button pure-button-primary" type="submit" 
    value="Generate Out Slip" id="genInslip" onclick="outUpdateDocStatus()" disabled="disabled" />  
<input class="pure-button pure-button-primary" type="button" value="Reset" id="reset" onclick="resetForm()"/>  
            </div>

                
            </center>
</form></td>
<td style="width: 50%;height: 100%;" >
<iframe id="slip_upload_frame" name="slip_upload_frame" style="width: 100%;height:400px;" marginheight="0" marginwidth="0" frameborder="0"></iframe>
        </td>
    </tr>
</table>
</div>

</body>
</html>
