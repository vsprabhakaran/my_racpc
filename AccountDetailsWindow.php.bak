<!doctype html>
<html lang=''>
<head>
   <title>Account Details</title>
    <script src="jquery-latest.min.js" type="text/javascript"></script>
        <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <link rel="stylesheet" href="css/pure-min.css">
    <link rel="stylesheet" href="css/css-table.css">
    <?php
        

    ?>
    <script type="text/javascript">
        $(document).ready(function () {
            var enteredAccNumber = getParameterByName("accNo");
            var phpURL = 'db/accountInformations.php';

            if (doPOST_Request(phpURL, enteredAccNumber, "isValidAccount") == "false") {
                alert("Invalid Account Information Requested!!!");
                window.close();
            }

            document.getElementById('AccNameTag').innerHTML = doPOST_Request(phpURL, enteredAccNumber, "GetAccountNameOfAccount");
            document.getElementById('BranchCodeTag').innerHTML = doPOST_Request(phpURL, enteredAccNumber, "GetBranchCodeOfAccount");
            document.getElementById('BranchNameTag').innerHTML = doPOST_Request(phpURL, enteredAccNumber, "GetBranchNameOfAccount");
            document.getElementById('LoanProductTag').innerHTML = doPOST_Request(phpURL, enteredAccNumber, "GetLoanProductOfAccount");
            document.getElementById('LoanStatusTag').innerHTML = doPOST_Request(phpURL, enteredAccNumber, "GetLoanStatusOfAccount");
            document.getElementById('DocumentStatusTag').innerHTML = doPOST_Request(phpURL, enteredAccNumber, "GetDocumentStatusOfAccount");
            document.getElementById('FolioNumberTag').innerHTML = doPOST_Request(phpURL, enteredAccNumber, "GetFolioNumberOfAccount").replace(/["'\\]/g, "");
            document.getElementById('RackNumberTag').innerHTML = doPOST_Request(phpURL, enteredAccNumber, "GetRackNumberOfAccount");

            function doPOST_Request(phpURL, pfNumber, typeCall) {
                var returnMsg = '';
                $.ajax({
                    type: 'POST',
                    url: phpURL,
                    data: { accNo: pfNumber, type: typeCall },
                    success: function (msg) {
                        if (msg != "") returnMsg = msg.replace(/["']/g, "");
                        else alert("not Found");
                        if (msg == "false") returnMsg = "NA"; ;
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
        });
    </script>
  </head>
<body>
  <table border="0" class="pure-table" >
      <thead>
          <tr><th colspan="2"><center>Account Details</center></th></tr>
      </thead>
      <tr>
          <th>Name</th>
          <td id="AccNameTag"></td>
      </tr>     
      <tr class="pure-table-odd">
          <th>Branch Name</th>
          <td id="BranchNameTag"></td>
      </tr>
      <tr>
          <th>Branch Code</th>
          <td id="BranchCodeTag"></td>
      </tr>
      <tr class="pure-table-odd">
          <th>Loan Product</th>
          <td id="LoanProductTag"></td>
      </tr>
      <tr>
          <th>Loan Status</th>
          <td id="LoanStatusTag"></td>
      </tr>
      <tr class="pure-table-odd">
          <th>Document Status</th>
          <td id="DocumentStatusTag"></td>
      </tr>
      <tr>
          <th>Folio Number</th>
          <td id="FolioNumberTag"></td>
      </tr>
      <tr class="pure-table-odd">
          <th>Rack Number</th>
          <td id="RackNumberTag"></td>
      </tr>
  </table>
</body>
</html>
