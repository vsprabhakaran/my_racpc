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
    <script type="text/javascript" src="../jquery-latest.min.js"></script>
    <link rel="stylesheet" href="../css/my_styles.css">
    <link rel="stylesheet" href="../css/pure-min.css">
<script  type="text/javascript">
    function doPOST_Request_SessionUser(phpURL) {
        var returnMsg = '';
        $.ajax({
            type: 'POST',
            url: phpURL,
            success: function (msg) {
                if (msg != "") 
                { 
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
</script>
</head>
<body>

<div>
<table border="0" style="width: 100%;" >
<tr>
<td colspan="3">
    <div id="myResults"> </div>
    <script>
        var phpURL = '../getPfnoFromSession.php';
        var login_pf = doPOST_Request_SessionUser(phpURL);
        alert(login_pf);
        $('#myResults').text(login_pf);
    </script>
  
       
    <center>
        <img height=80 width=300 src="../img/header.png"/>
    </center>
</td>
</tr>
</table>
</div>

</body>
</html>

<?php
}
?>