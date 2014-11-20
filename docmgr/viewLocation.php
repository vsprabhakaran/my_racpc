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
        <meta http-equiv="refresh" content="0;URL=../login.php">
    <?php
        }
    ?>

    <script type="text/javascript" src="../jquery-latest.min.js"></script>

</head>
<body>

<div>
   

<table border="0" style="width:100%;height:100%;border-width:2px;">


<tr>
<td>
<div>
<iframe scrolling="no" frameBorder="0" src="../header.php" style="width: 100%;height: 90px;" marginheight="0" marginwidth="0" frameborder="0">
</iframe>
</div>
</td>
</tr>

<tr>
<td colspan="3">
<div>
<iframe scrolling="no" frameBorder="0" src="../cssmenu/docManagerMenu.php" style="width: 100%;height: 85px;">
</iframe>
</div> 
</td>
</tr>


<tr>
  <table border="0" style="width: 40% ">
    <tr> 
        <td style="text-align: left"> <h4> Account Number </h4> </td> 
        <td>
        <center> 

            <input type="text" name="accountno" id="accountno" oninput="showdetails(this.value)"/> 
            <div id="txtHint"><b></b></div> 
        </center>  </td>
    </tr>
    </table>
</tr>
    
</table>

</div>

</body>
</html>
