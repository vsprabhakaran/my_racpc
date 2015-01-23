<?php
session_start();
if( $_SESSION["role"] != "RACPC_ADMIN")
{
  $_SESSION["role"] = "";
  $_SESSION["pfno"] = "";
  ?><meta http-equiv="refresh" content="0;URL=../login.php"><?php
}
else
{
  ?>
<!DOCTYPE html>
<html lang="">
<head>
<link rel="stylesheet" href="../css/pure-min.css"/>
<script src="../jquery-latest.min.js" type="text/javascript"></script>

<script>
$(document).ready(function () {
});
function showIframe()
{
  $("#FP").css("visibility","visible");
}
</script>

</head>
<body style="background-image:url('../img/greyzz.png');">
  <br>
  <br>
  <div>
    <table style="width:100%">
      <tr>
        <td style="width:40%; vertical-align:top;">
          <form class="pure-form pure-form-aligned" id="detailsForm" action="generateFirstPage.php" method="POST" target="FP">
            <div class="pure-control-group">
              <label for="accNumber">Account Number</label>
              <input type="text" id="accNumber"  name="accNumber"/>
            </div>
            <div class="pure-control-group">
              <label for="accName">Account Holder Name</label>
              <input type="text" id="accName"  name="accName"/>
            </div>
            <div class="pure-control-group">
              <label for="prodName">Product Code</label>
              <input type="text" id="prodName"  name="prodName"/>
            </div>
            <div class="pure-control-group">
              <label for="branchCode">Branch Code</label>
              <input type="text" id="branchCode"  name="branchCode"/>
            </div>
            <div class="pure-control-group">
              <label for="branchName">Branch Name</label>
              <input type="text" id="branchName"  name="branchName"/>
            </div>
            <div class="pure-controls">
              <button class="pure-button pure-button-primary" type="submit" onclick="showIframe()">Generate</button>
            </div>
          </form>
        </td>
        <td>
          <iframe id="FP" name="FP"  style="height:500px; width:95%;visibility:hidden;">
        </td>
      </tr>
    </table>
</div>
</body>
</html>
<?php
}
?>
