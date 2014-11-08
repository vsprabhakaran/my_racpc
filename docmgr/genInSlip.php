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
    function printFunction()
    { 
    window.print();
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
<iframe scrolling="no" frameBorder="0" src="headerOutSlip.php" style="width: 100%;height: 90px;" marginheight="0" marginwidth="0" frameborder="0">
</iframe>
</div>
</td>
</tr>



<tr>
<td colspan="2"> 
<center>
<div>

  <table border="1" style="width:100%;height:100%;border-width:0px;border-collapse: collapse;">
  <tr>
    <td colspan="3"><center><h1>Inslip Form</h1> </center></td>
    </tr>
   <tr>
    <td><h3><center>Account Number</center></h3></td>
    <td><h4><center><?php echo $_POST["accountno"]; ?><br></center></h4></td> 
    <td style="width: 100px"><br> </td>
   </tr>
   <tr>
    <td><h3><center>Account Holder Name</center></h3></td>
    <td><h4><center><?php echo $_POST["accountname"]; ?><br></center></h4></td> 
    <td></td>
   </tr>
   <tr>
    <td><h3><center>Branch Code</center></h3></td>
    <td><h4><center><?php echo $_POST["brcode"]; ?><br></center></h4></td> 
    <td></td>
   </tr>
   <tr>
    <td><h3><center>Branch Name</center></h3></td>
    <td><h4><center><?php echo $_POST["brname"]; ?><br></center></h4></td> 
    <td></td>
   </tr>
    <tr>
    <td><h3><center>Folio Number</center></h3></td>
    <td><h4><center><?php echo $_POST["foliono"]; ?><br></center></h4></td> 
    <td></td>
   </tr>
   <tr>
    <td><h3><center>Giver Details</center></h3></td>
    <td><h4>
        <center>
        <?php echo $_POST["pfnogiver"]; ?><br><br> 
        <?php echo $_POST["nameofGiver"]; ?> <br>
        </center></h4></td> 
    <td></td>
   </tr>
   <tr>
    <td><h3><center>Comments</center></h3></td>
    <td><h4><center><?php echo $_POST["reason"]; ?><br></center></h4></td> 
    <td></td>
   </tr>
   


  
 
</table>
<br/>
<br/>
<div class="pure-controls">
<input class="pure-button pure-button-primary" type="button" 
    value="PRINT In SLIP" id="printInslip" onclick="this.style.visibility='hidden';printFunction()"  />  
</center>
</div> 

</div> 
</center>
</td>
</tr>



<tr>
<td>
  
</td>
    
</tr>
</table>
    </center>
</div>

</body>
</html>