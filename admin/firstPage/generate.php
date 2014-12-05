<?php
        session_start();
        if(!isset($_SESSION["role"]))
        {
           $_SESSION["role"] = "";
           $_SESSION["pfno"] = "";
        ?>
<meta http-equiv="refresh" content="0;URL=login.php"><?php
        }
    ?>
<!doctype html>
<html lang=''>
<head>
   <title>Account Details</title>
    <script src="../../jquery-latest.min.js" type="text/javascript"></script>
        <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <link rel="stylesheet" href="../../css/pure-min.css">
    <script type="text/javascript">
        $(document).ready(function () {

            var enteredAccNumber = getParameterByName("accNo");
            var phpURL = '../../db/accountInformations.php';

            if (doPOST_Request(phpURL, enteredAccNumber, "isValidAccount") == "false") {
                alert("Invalid Account Information Requested!!!");
                window.close();
            }
            $("#barcode").prop('src', "test.php?text=" + enteredAccNumber + "&check=2");
            document.getElementById('AccNumberTag').innerHTML = enteredAccNumber;
            document.getElementById('AccNameTag').innerHTML = doPOST_Request(phpURL, enteredAccNumber, "GetAccountNameOfAccount");
            document.getElementById('BranchCodeTag').innerHTML = doPOST_Request(phpURL, enteredAccNumber, "GetBranchCodeOfAccount");
            document.getElementById('BranchNameTag').innerHTML = doPOST_Request(phpURL, enteredAccNumber, "GetBranchNameOfAccount");
            document.getElementById('LoanProductTag').innerHTML = doPOST_Request(phpURL, enteredAccNumber, "GetLoanProductOfAccount");

            
        });
        function doPOST_Request(phpURL, number, typeCall) {
                var returnMsg = '';
                $.ajax({
                    type: 'POST',
                    url: phpURL,
                    data: { accNo: number, type: typeCall },
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

            function getParameterByName(name) {
                name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
                var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
                results = regex.exec(location.search);
                return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
            }   
        function printPage() {
            $("#generate").css("visibility", "hidden");
            window.print();
            $("#generate").css("visibility", "visible");
        }
    </script>
  </head>
<body>
  <div >
      <center>
          <table border='0'>
            <tr>
                <td colspan="3" height="150" ><center><img src='logo.png' /></center>
                </td>
            </tr>
            <tr>
                <td>
                    <iframe id="barcode" src='' frameBorder="0" scrolling="no" style=" height: 65px;width: 185px;" ></iframe>
                </td>
                <td></td>
                <td ></td>
            </tr>
            <tr>
                <td colspan="3" height="80"> 
                    <center><h3><b>RACPC Ayyappanthangal (17938)</b></h3></center>
                </td>
            </tr>
            <tr> 
                <td height="50" width="350">
                    <center>ACCOUNT NUMBER</center>
                </td> 
                <td width="50">:</td> 
                <td id="AccNumberTag"><br/>
                </td> 
            </tr>
            <tr> 
                <td height="50" width="350">
                    <center>NAME</center>
                </td> 
                <td width="50">:</td> 
                <td id="AccNameTag"><br/>
                </td> 
            </tr>
            <tr> 
                <td height="50" width="350">
                    <center>PRODUCT NAME</center>
                </td> 
                <td width="50">:</td> 
                <td id="LoanProductTag"><br/>
                </td> 
            </tr>
            <tr> 
                <td height="50" width="350">
                    <center>BRANCH CODE</center>
                </td> 
                <td width="50">:</td> 
                <td id="BranchCodeTag"><br/>
                </td> 
            </tr>
            <tr> 
                <td height="50" width="350">
                    <center>BRANCH NAME</center>
                </td> 
                <td width="50">:</td> 
                <td id="BranchNameTag"><br/>
                </td> 
            </tr>
            <tr>
                <td>
                    <center>STORAGE<center>
                </td>
                <td width="50">:</td>
                <td><img src='box.png'/></td>
            </tr>
            <tr>
                <td>
                    <center>DOCUMENTS<center>
                </td>
                <td width="50">:</td>
                <td><img src='box.png'/></td>
            </tr>
            <tr>
                <td><center><center></td>
                <td width="50"></td>
                <td><img src='box.png'/></td>
            </tr>
              <tr>
                  <td colspan="3">
                   <center><button type="button" id="generate" onClick="printPage()" class="pure-button pure-button-primary">Print</button></center>   
                  </td>
              </tr>
          </table>
      </center>
      
  </div>
  
</body>
</html>