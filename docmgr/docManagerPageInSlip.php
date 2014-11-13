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
 <script type="text/javascript" id="resetUserValidationScript">
        function resetPanelShowUserDetails() {
            var enteredUserPf = $('#pfnogiver').val();
            var popup = window.open("../UserDetailsWindow.php?pfNo=" + enteredUserPf, "Details", "resizable=1,scrollbars=1,height=325,width=280,left = " + (document.documentElement.clientWidth - 300) + ",top = " + (225));
            $(popup).blur(function () { this.close(); });
        }

        function pfNumResetFormEnter() {
            var pfNumber = $('#pfnogiver').val();
            //alert(pfNumber);
            if (pfNumber == "") {
                nullpfNumberEnterredResetForm();
                return;
            }
            $.post('../db/UserInformations.php', { pfno: pfNumber, type: 'isValidUser' }, function (msg) {
                if (msg == "true") {
                    validpfNumberEnterredResetForm();
                }
                else if (msg == "false") {
                    invalidpfNumberEnterredResetForm();
                }
            }).fail(function (msg) {
                alert("fail : " + msg);
            });

        }

        function nullpfNumberEnterredResetForm(form) {
            document.getElementById('rViewDetailsURL').style.visibility = "hidden";
            document.getElementById('pfnogiver').style.backgroundColor = "";
            $('#rSubmitButton').prop('disabled', true);
        }
        function validpfNumberEnterredResetForm() {
            document.getElementById('rViewDetailsURL').style.visibility = "visible";
            document.getElementById('pfnogiver').style.backgroundColor = "#CCFFCC";
            $('#rSubmitButton').prop('disabled', false);
        }
        function invalidpfNumberEnterredResetForm() {
            document.getElementById('rViewDetailsURL').style.visibility = "hidden";
            document.getElementById('pfnogiver').style.backgroundColor = "#FFC1C1";
            $('#rSubmitButton').prop('disabled', true);
        }

    </script>
<script>
    function InUpdateDocStatus() {
        //`("inside updstauts");

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
        $.post('getDocMgrdetails2.php', { accNo: enteredAccNumber, type: 'InUpdateDocStatus' }, function (msg) {
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
    function showdetails() {
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
        var enteredPFNumber = $('#pfnogiver').val();
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

		document.getElementById('nameofGiver').value = doPOST_RequestUser(phpURL, enteredPFNumber, "GetUserName"); 
		//alert(document.getElementById('nameofGiver').value);
		
        }
    function showAccountDetails() {
        var enteredAccNumber = document.getElementById('accountno').value;
        //alert(enteredAccNumber);
        var popup = window.open("../AccountDetailsWindow.php?accNo=" + enteredAccNumber, "Details", "resizable=1,scrollbars=1,height=325,width=280,left = " + (document.documentElement.clientWidth - 300) + ",top = " + (225));
        $(popup).blur(function () { this.close(); });
    }
	  function showUserDetails() {
        var enteredPFNumber = document.getElementById('pfnogiver').value;
        //alert(enteredAccNumber);
        var popup = window.open("../UserDetailsWindow.php?pfNo=" + enteredPFNumber, "Details", "resizable=1,scrollbars=1,height=325,width=280,left = " + (document.documentElement.clientWidth - 300) + ",top = " + (225));
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
		$('#pfnogiver').val("");
		$('#pfnogiver').prop('disabled', true);
		$('#accountno').val("");
		$('#nameofGiver').val("");
		$('#nameofGiver').val("");
        
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
        $('#pfnogiver').prop('disabled', false);
        $('#genInslip').prop('disabled', false);
        $('#reason').prop('disabled', false);
    }
    function invalidPFNumberEnterred() {
        document.getElementById('pfnogiver').style.backgroundColor = "#FFC1C1";
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
                if (msg != "") {returnMsg = msg.replace(/["']/g, ""); }
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
                if (msg != "") {returnMsg = msg.replace(/["']/g, ""); }
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
            $('#genInSlip').bind("keyup keypress", function (e) {
                var code = e.keyCode || e.which;
                if (code == 13) {
                    e.preventDefault();
                    return false;
                }
            });
            $('#genInSlip').bind('submit', function () {
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
<table border="0" style="width: 100%">
	<tr>
	<td style="width: 50%">
<form name="genInSlip" id="genInSlip" action="genInSlip.php" class="pure-form pure-form-aligned" method="POST" target="slip_upload_frame">
    <div class="pure-control-group"> 
        <label for="accountno" >Account Number :</label>
        <input type="text" name="accountno" id="accountno" onKeyDown="if (event.keyCode == 13) showdetails()" /> 
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
		<label for="pfnogiver" >  Giver's PF Number :</label>
		<input type="text" name="pfnogiver" id="pfnogiver" onKeyDown="if (event.keyCode == 13) showUdetails()" />
        <a id="getUserDetailsSpan" href="#"  style="visibility: hidden" onclick="showUserDetails()">View Details</a>
   
    </div>
    <div class="pure-control-group">
        <label for="nameofGiver" > Name of the Giver :</label>
        <input type="text" id="nameofGiver" name="nameofGiver" readonly="true" />
    </div>
	
    <div class="pure-control-group">
        <label for="reason" > Comments :</label>
        <textarea rows="4" cols="23" name="reason" id="reason" disabled="disabled"> </textarea>  
     </div>      
    
    <div class="pure-controls">
         <input class="pure-button pure-button-primary" type="submit" value="Generate In Slip" id="genInslip" onclick="InUpdateDocStatus()" disabled="disabled" />  
		 <input class="pure-button pure-button-primary" type="button" value="Reset" id="reset" onClick="resetForm()" />  
    </div> 

</center>
</form>
</td>
        <td style="width: 50%;height: 100%;">
            <iframe id="slip_upload_frame" name="slip_upload_frame" style="width: 100%;height:400px;" frameBorder="0"  marginheight="0" marginwidth="0" frameborder="0"></iframe>
        </td>
        </tr>
        </table>  
</div>

</body>
</html>
