<!doctype html>
<html lang=''>
<head>
   <title>Document Manager View Report</title>

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
    <script type="text/javascript" src="../jquery-latest.min.js"></script>
    <link rel="stylesheet" href="../css/my_styles.css">
    <link rel="stylesheet" href="../css/pure-min.css">

    <script>
        function ViewReport() {
            var phpURL = '../getPfnoFromSession.php';
            var login_pf = doPOST_Request_SessionUser(phpURL, 'getPfno');
            //alert(login_pf);
            phpURL = '../db/accountInformations.php';
            var msg = doPOST_Request_ViewReport(phpURL, login_pf, 'ViewReport');
            alert(msg);
            if (msg == "true") alert("View Report Successfull");
            else alert("View Report Failure");
        }

        function doPOST_Request_SessionUser(phpURL, typeCall) {
            var returnMsg = '';
            $.ajax({
                type: 'POST',
                url: phpURL,
                data: { type: typeCall },
                success: function (msg) {
                    if (msg != "") { returnMsg = msg.replace(/["']/g, ""); }
                    else alert("session user not Found");
                },
                error: function (msg) { alert("fail : " + msg); },
                async: false
            });
            return returnMsg;
        }
        function doPOST_Request_ViewReport(phpURL, login_pf, typeCall) {
            var returnMsg = '';
            $.ajax({
                type: 'POST',
                url: phpURL,
                data: { login_pf_index: login_pf, type: typeCall },
                success: function (msg) {
                    if (msg == "true") { returnMsg = msg.replace(/["']/g, ""); alert("success"); }
                    else alert("View Report Failed");
                },
                error: function (msg) { alert("fail : " + msg); },
                async: false
            });
            return returnMsg;

        }
    </script>
</head>
<body>
    <h1>Click the button to view list of Out documents</h1>
    <div class="pure-controls">
    
    <input class="pure-button pure-button-primary" type="button" value="Submit" id="submit_report" onClick="ViewReport()"/>  
    </div>

</body>
</html>
<?php
}
?>