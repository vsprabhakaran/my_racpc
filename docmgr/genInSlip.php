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
   <title>Generate Inslip</title>
    <script type="text/javascript" src="../jquery-latest.min.js"></script>
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
<td colspan="2"> 
<center>
<div>

  <table border="1" style="width:100%;height:100%;border-width:0px;border-collapse: collapse; table-layout: fixed">
  <tr>
    <td colspan="3" style="font-family: Arial, Helvetica, Sans-Serif; font-size: large; font-weight: 600">
    <center>
        <h5>INSLIP FORM </h5>
        <div id="myResults" style="font-family: Arial, Helvetica, Sans-Serif; font-size: large; font-weight: 300"></div>
        <p id="date"></p>
    </center>
    </td>
    </tr>
      <script>
        var phpURL = '../getPfnoFromSession.php';
        var pfNumber = doPOST_Request_SessionUser(phpURL,'getPfno');
        phpURL = '../db/UserInformations.php';
        var racpc_name = doPOST_Request(phpURL, pfNumber, "GetUserRacpcName");
        $('#myResults').text(racpc_name);
        var d = new Date();
        document.getElementById("date").innerHTML = d.toDateString();
    </script>
   <tr>
    <td><center><h5>ACCOUNT NUMBER</h5></center></td>
    <td><center><?php echo $_POST["accountno"]; ?><br></center></td> 
    
    <td>
    <div>
	<!-- added variable check for hidding print button and reducing the size of barcode for slip generation -->
    <iframe id="barcodeIFrame" frameBorder="0" scrolling="no" style="height:4em;width:15em;" 
	marginheight="0" marginwidth="0" frameborder="0" src="../barcodegit/test.php?text= <?php echo $_POST['accountno'] ?> & check=1" />
    </iframe>
    <br> <br>
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
    <td><center><h5>GIVER DETAILS</h5></center></td>
    <td>
        <center>
        <?php echo $_POST["pfnogiver"]; ?><br><br> 
        <?php echo $_POST["nameofGiver"]; ?> <br>
        </center></td> 
    <td></td>
   </tr>
   <tr>
    <td><center><h5>COMMENTS</h5></center></td>
    <td style="word-wrap: break-word; text-justify: distribute">
       <p style="text-align: justify"><center><?php echo $_POST["reason"]; ?> </center></p>
    </td> 
    <td></td>
   </tr>
   

</table>
<br/>

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
    value="PRINT IN SLIP" id="printInslip" onclick="this.style.visibility='hidden';printFunction()"  />  
</center>
</div> 
</td>
</tr>
  
<tr>
<br>
</tr>
    
<tr>
<th>DOCUMENT MANAGER SIGNATURE</th>
<th>GIVER SIGNATURE</th>
</tr>
<tr>
<th><br><br><hr style="border-top: medium double #333; color: #333; text-align: center; padding: 0" /><br></th>
<th><br><br><hr style="border-top: medium double #333; color: #333; text-align: center; padding: 0" /><br></th>
</tr>
</table>

</body>
</html>
<?php
}
?>
