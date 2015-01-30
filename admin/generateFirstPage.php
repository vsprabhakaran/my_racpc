<?php
        session_start();
        if(!isset($_SESSION["role"]))
        {
           $_SESSION["role"] = "";
           $_SESSION["pfno"] = "";
        ?>
<meta http-equiv="refresh" content="0;URL=login.php"><?php
        }
        require "../db/userQueries.php";
    ?>
<!doctype html>
<html lang=''>
<head>
   <title>Cover Page</title>
    <script src="../jquery-latest.min.js" type="text/javascript"></script>
        <meta http-equiv="content-type" content="text/html;charset=utf-8"/>
    <link rel="stylesheet" href="../css/pure-min.css">
    <script type="text/javascript">
        $(document).ready(function () {
                var AccNumber=document.getElementById('AccNumberTag').innerText;
                $("#barcode").prop('src', "firstPage/test.php?text=" + AccNumber + "&check=2");

        });
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
                <td colspan="3" height="150"><center><img src='firstPage/logo.png' /></center>
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
                    <center><h3><b> <?php echo GetUserRacpcName($_SESSION["pfno"]); echo "(".GetUserBranchCode($_SESSION["pfno"]).")"; ?></b></h3></center>
                </td>
            </tr>
            <tr>
                <td height="50" width="300">
                    <center>ACCOUNT NUMBER</center>
                </td>
                <td width="50">:</td>
                <td id="AccNumberTag"> <?php echo $_POST["accNumber"]; ?><br/>
                </td>
            </tr>
            <tr>
                <td height="50" width="300">
                    <center>NAME</center>
                </td>
                <td width="50">:</td>
                <td id="AccNameTag"><?php echo $_POST["accName"]; ?><br/>
                </td>
            </tr>
            <tr>
                <td height="50" width="300">
                    <center>PRODUCT NAME</center>
                </td>
                <td width="50">:</td>
                <td id="LoanProductTag"><?php echo $_POST["prodName"]; ?><br/>
                </td>
            </tr>
            <tr>
                <td height="50" width="300">
                    <center>BRANCH CODE</center>
                </td>
                <td width="50">:</td>
                <td id="BranchCodeTag"><?php echo $_POST["branchCode"]; ?><br/>
                </td>
            </tr>
            <tr>
                <td height="50" width="300">
                    <center>BRANCH NAME</center>
                </td>
                <td width="50">:</td>
                <td id="BranchNameTag"><?php echo $_POST["branchName"]; ?><br/>
                </td>
            </tr>
            <tr style="height: 52px">
                <td>
                    <center>STORAGE<center>
                </td>
                <td width="50">:</td>
                <td id="storageContent"><img id="storageBox" src='firstPage/box.png'/></td>
            </tr>
            <tr>
                <td>
                    <center>DOCUMENTS<center>
                </td>
                <td width="50">:</td>
                <td><img src='firstPage/box.png'/></td>
            </tr>
            <tr>
                <td><center><center></td>
                <td width="50"></td>
                <td><img src='firstPage/box.png'/></td>
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
