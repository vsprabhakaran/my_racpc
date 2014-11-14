<!doctype html>
<html lang=''>
<head>
   <title>generate outslip</title>
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
<script>
    function printFunction() {
    window.print();
    }
     function doPOST_Request_SessionUser(phpURL) {
        var returnMsg = '';
        $.ajax({
            type: 'POST',
            url: phpURL,
            success: function (msg) {
                if (msg != "") {
                    returnMsg = msg.replace(/["']/g, "");
                    //document.getElementById('myResults').innerHTML = returnMsg;
                }
                else alert("session user not Found");
            },
            error: function (msg) { alert("fail : " + msg); },
            async: false
        });
        return returnMsg;
    }
    function doPOST_Request(phpURL, pfNumber, typeCall) {
        var returnMsg = '';
        $.ajax({
            type: 'POST',
            url: phpURL,
            data: { pfno: pfNumber, type: typeCall },
            success: function (msg) {
                //alert(msg);
                if (msg != "") { returnMsg = msg.replace(/["']/g, ""); }
                else alert("pf number not Found");
            },
            error: function (msg) { alert("fail : " + msg); },
            async: false
        });
        return returnMsg;

    }
</script>
</head>
<body>

<div>
   <center>
<table border="0" style="width:90%;height:100%;border-width:2px;">

<tr>
<td>
<div>
<!--
    style="display:inline-block;"
<iframe scrolling="no" frameBorder="0" src="headerOutSlip.php" style="width: 100%;height: 90px;" marginheight="0" marginwidth="0" frameborder="0">
</iframe>
-->
<center>
    <img height=80 width=250 src="../img/header.png"/>
</center>
</div>
</td>
</tr>



<tr>
<td colspan="2"> 
<center>
<div>

  <table border="1" style="width:100%;height:100%;border-width:0px;border-collapse: collapse;">
  <tr>
    <td colspan="3" style="font-family: Arial, Helvetica, Sans-Serif; font-size: large; font-weight: 600">
    <center>
        <h3>OUTSLIP FORM</h3> <div id="myResults" style="font-family: Arial, Helvetica, Sans-Serif; font-size: large; font-weight: 300"></div>
    </center>
	</td>
    </tr>
  <script>
        var phpURL = '../getPfnoFromSession.php';
        var pfNumber = doPOST_Request_SessionUser(phpURL);
        phpURL = '../db/UserInformations.php';
        var racpc_name = doPOST_Request(phpURL, pfNumber, "GetUserRacpcName");
        $('#myResults').text(racpc_name);
    </script>
   <tr>
    <td><center><h4>ACCOUNT NUMBER</h4></center></td>
    <td><center><?php echo $_POST["accountno"]; ?><br></center></td> 
    <td style="width: 100px"><br> </td>
   </tr>
   <tr>
    <td><center><h4>ACCOUNT HOLDER NAME</h4></center></td>
    <td><center><?php echo $_POST["accountname"]; ?><br></center></td> 
    <td></td>
    </tr>
    <tr>
    <td><center><h4>PRODUCT DESCRIPTION</h4></center></td>
    <td><center><?php echo $_POST["productcode"]; ?><br></center></td> 
    <td></td>
   </tr>
   <tr>
    <td><center><h4>BRANCH CODE</h4></center></td>
    <td><center><?php echo $_POST["brcode"]; ?><br></center></td> 
    <td></td>
   </tr>
   <tr>
    <td><center><h4>BRANCH NAME</h4></center></td>
    <td><center><?php echo $_POST["brname"]; ?><br></center></td> 
    <td></td>
   </tr>
    <tr>
    <td><center><h4>FOLIO NUMBER</h4></center></td>
    <td><center><?php echo $_POST["foliono"]; ?><br></center></td> 
    <td></td>
   </tr>
   <tr>
    <td><center><h4>RECEIVER DETIALS</h4></center></td>
    <td>
        <center>
        <?php echo $_POST["pfnorcv"]; ?><br><br>
        <?php echo $_POST["nameofReciver"]; ?><br>
        </center>
		</td> 
    <td></td>
   </tr>
   <tr>
    <td><center><h4>REASON</h4></center></td>
    <td><center><?php echo $_POST["reason"]; ?><br></center></td> 
    <td></td>
   </tr>
</table>

</div> 
</center>
</td>
</tr>

</table>
</center>
</div>

 
<table style="width:100%">
 
<tr>
<td colspan="2">
<div class="pure-controls">
<center>
<input class="pure-button pure-button-primary" id="printbutton" type="button" 
    value="PRINT OUT SLIP" id="printOutslip" onclick="this.style.visibility='hidden';printFunction()"  />  
</center>
</div> 
</td>
</tr>

<tr>
<br>
</tr>
    
<tr>
<th>DOCUMENT MANAGER SIGNATURE</th>
<th>RECEIVER SIGNATURE</th>
</tr>
<tr>
<!--
    <th><br><br><hr style="border-top: dotted 1px;" /><br></th>
-->
<th><br><br><hr style="border-top: medium double #333; color: #333; text-align: center; padding: 0" /><br></th>
<th><br><br><hr style="border-top: medium double #333; color: #333; text-align: center; padding: 0"/><br></th>
</tr>



</table>

</body>
</html>