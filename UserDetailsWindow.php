<!doctype html>
<html lang=''>
<head>
   <title>User Details</title>
    <script src="jquery-latest.min.js" type="text/javascript"></script>
    <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <link rel="stylesheet" href="css/pure-min.css">
    <link rel="stylesheet" href="css/css-table.css">
    <?php
        

    ?>
    <script type="text/javascript">
        $(document).ready(function () {
            
            $('tbody tr').hover(function() {
	        $(this).addClass('odd');
	            }, function() {
	        $(this).removeClass('odd');
	            });

            var enteredPFNumber = getParameterByName("pfNo");
            if (doPOST_Request('db/UserInformations.php', enteredPFNumber, "isValidUser") == "false") {
                alert("Invalid User Information Requested!!!");
                window.close();
            }


            document.getElementById('UserNameTag').innerHTML = doPOST_Request('db/UserInformations.php', enteredPFNumber, "GetUserName");
            document.getElementById('BranchNameTag').innerHTML = doPOST_Request('db/UserInformations.php', enteredPFNumber, "GetUserBranchName");
            document.getElementById('BranchCodeTag').innerHTML = doPOST_Request('db/UserInformations.php', enteredPFNumber, "GetUserBranchCode");
            document.getElementById('DesignationTag').innerHTML = doPOST_Request('db/UserInformations.php', enteredPFNumber, "GetUserRole");
            document.getElementById('EmailTag').innerHTML = doPOST_Request('db/UserInformations.php', enteredPFNumber, "GetUserEmail");
            document.getElementById('PhoneTag').innerHTML = doPOST_Request('db/UserInformations.php', enteredPFNumber, "GetUserPhone");

        });
        function getParameterByName(name) {
            name = name.replace(/[\[]/, "\\[").replace(/[\]]/, "\\]");
            var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
                results = regex.exec(location.search);
            return results === null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
        }
        function doPOST_Request(phpURL, pfNumber, typeCall) {
            var returnMsg = '';
            $.ajax({
                type: 'POST',
                url: phpURL,
                data: { pfno: pfNumber, type: typeCall },
                success: function (msg) {
                    if (msg != "") returnMsg = msg.replace(/["']/g, "");
                    else  alert("not Found");
                },
                error: function(msg){ alert("fail : "+msg); },
                async: false
            });
            return returnMsg;
        }
    </script>
  </head>
<body>
  <table border="0" class="pure-table" >
      <thead>
          <tr><th colspan="2"><center>User Details</center></th></tr>
      </thead>
      <tr>
          <th>Name</th>
          <td id="UserNameTag"></td>
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
          <th>Designation</th>
          <td id="DesignationTag"></td>
      </tr>
      <tr>
          <th>Email</th>
          <td id="EmailTag"></td>
      </tr>
      <tr class="pure-table-odd">
          <th>Phone</th>
          <td id="PhoneTag"></td>
      </tr>
      
  </table>

</body>
</html>
