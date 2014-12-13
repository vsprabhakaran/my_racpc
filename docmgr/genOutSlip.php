<?php
        session_start();
        if( $_SESSION["role"] != "RACPC_DM")
        {
           $_SESSION["role"] = "";
        ?>
        <meta http-equiv="refresh" content="0;URL=../login.php">
    <?php
        }
		else
		{
    ?>
<!doctype html>
<html lang=''>
<head>
   <title>Generate Outslip</title>
    <script type="text/javascript" src="../jquery-latest.min.js"></script>
     <link rel="stylesheet" href="../css/my_styles.css">
    <link rel="stylesheet" href="../css/pure-min.css">

<script>
    function printFunction() {
    window.print();
    }
    function doPOST_Request_SessionUser(phpURL,typeCall) {
        var returnMsg = '';
        $.ajax({
            type: 'POST',
            url: phpURL,
            data: {type: typeCall},
            success: function (msg) {
                if (msg != "") {
                    returnMsg = msg.replace(/["']/g, "");
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
                if (msg != "") { returnMsg = msg.replace(/["']/g, ""); }
                else alert("pf number not Found");
            },
            error: function (msg) { alert("fail : " + msg); },
            async: false
        });
        return returnMsg;

    }
</script>
<style type="text/css">
td
{
padding:1em 0ex 0ex 0em;
}
</style>
</head>
<body>

<div>
   <center>
<table border="0" style="width:100%;height:100%;border-width:2px;">

<tr>
<td>
<div>



<center>
    <img height=80 width=250 src="../img/header.png"/>
</center>
</div>
</td>
</tr>



<tr>
<td colspan="2" > 
<center>
<div>

  <table border="1" style="width:100%;height:100%;border-width:0px;border-collapse: collapse; font-size:small;table-layout:fixed;" >
  <tr >
    <td colspan="3" style="font-family: Arial, Helvetica, Sans-Serif; font-size:small;font-weight: 600">
    <center>
        <h3>OUTSLIP FORM</h3> 
        <div id="myResults" style="font-family: Arial, Helvetica, Sans-Serif;font-size:small;font-weight: 300"></div>
        <p id="date">
		<?php
		date_default_timezone_set('Asia/Calcutta');
		echo date('d/m/Y  h:i:s a');
		?>
		</p>
    </center>
	</td>
    </tr>
  <script>
        var phpURL = '../getPfnoFromSession.php';
        var pfNumber = doPOST_Request_SessionUser(phpURL,'getPfno');
        phpURL = '../db/UserInformations.php';
        var racpc_name = doPOST_Request(phpURL, pfNumber, "GetUserRacpcName");
      var racpc_code = doPOST_Request(phpURL, pfNumber, "GetUserBranchCode");
      $('#myResults').text(racpc_name + " (" + racpc_code + ")");
      //var docmgrname = doPOST_Request(phpURL, pfNumber, "GetUserName");
      //$('#docmgrname').text(docmgrname +" ("+ pfNumber + ")");
        /* var d = new Date();
      var msg = d.toLocaleString();
      document.getElementById("date").innerHTML = msg; */
    </script>
        
   <tr>
    <td style="width:10%"><center><h5>ACCOUNT NUMBER</h5></center></td>
    <td><center>
    <?php echo $_POST["accountno"]; 
    ?> <br>
    </center></td> 
<td>
<div>
<!-- added variable check for hidding print button and reducing the size of barcode for slip generation -->
<iframe id="barcodeIFrame" frameBorder="0" scrolling="no" style="height:4em;width:15em; " 
    marginheight="0" marginwidth="0" frameborder="0" src="../barcodegit/test.php?text= <?php echo $_POST['accountno'] ?> & check=1" />
</iframe>
<br><br>
</div>    
</td>
   </tr>
   <tr>
    <td><center><h5>ACCOUNT HOLDER NAME</h5></center></td>
    <td><center><?php echo $_POST["accountname"]; ?><br></center></td> 
    <td></td>
    </tr>
    <tr>
    <td><center><h5>PRODUCT DESCRIPTION</h5></center></td>
    <td><center><?php echo $_POST["productcode"]; ?><br></center></td> 
    <td></td>
   </tr>
<!--
   <tr>
    <td><center><h5>BRANCH CODE</h5></center></td>
    <td><center><?php echo $_POST["brcode"]; ?><br></center></td> 
    <td></td>
   </tr>
-->   
   <tr>
    <td><center><h5>BRANCH NAME</h5></center></td>
    <td><center><?php echo $_POST["brname"]; ?><br></center></td> 
    <td></td>
   </tr>
    <tr>
    <td><center><h5>FOLIO NUMBER</h5></center></td>
    <td><center><?php echo $_POST["foliono"]; ?><br></center></td> 
    <td></td>
   </tr>
   <tr>
    <td><center><h5>RECEIVER DETIALS</h5></center></td>
    <td>
        <center>
        <?php echo $_POST["pfnorcv"]; ?><br><br>
        <?php echo $_POST["nameofReciver"]; ?><br>
        </center>
		</td> 
    <td></td>
   </tr>
     
    
    <tr>
    <td><center><h5>DOCUMENT MANAGER DETAILS</h5></center></td>
    <td>
        <center>
               <?php echo $_POST["did"]; ?><br><br>
               <?php echo $_POST["dname"]; ?><br><br> 
        </center>
		</td>  
    <td></td>
    </tr>


   <tr>
    <td><center><h5>REASON</h5></center></td>
    <td style="word-wrap: break-word; "><p style="text-align: justify"><center><?php echo $_POST["reason"]; ?></center></p></td> 
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

<br/> 
<table style="width:100%">
 
<tr>
<th>DOCUMENT MANAGER SIGNATURE</th>
<th>RECEIVER'S SIGNATURE</th>
</tr>
<!--
<tr>
    <th><br><br><hr style="border-top: dotted 1px;" /><br></th>
<th><br><br><hr style="border-top: medium double #333; color: #333; text-align: center; padding: 0" /><br></th>
<th><br><br><hr style="border-top: medium double #333; color: #333; text-align: center; padding: 0"/><br></th>
</tr>
-->

<tr>
<td colspan="2">
<center>
<div class="pure-controls">
<input class="pure-button pure-button-primary" id="printbutton" type="button" 
    value="PRINT OUT SLIP" id="printOutslip" onclick="this.style.visibility='hidden';printFunction()"  />  
</div> 
</center>
</td>
</tr>

<tr>
<br>
</tr>
    




</table>

</body>
</html>
<?php
}
?>
