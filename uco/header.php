
<!doctype html>
<?php
        session_start();
?>
<html lang=''>
<head>
<script src="../jquery-latest.min.js" type="text/javascript"></script>
</head>
<body style="background-image:url('img\\greyzz.png')">
<script type="text/javascript">
    var pf;
    $(document).ready(function () {
        
        $.post('../getPfnoFromSession.php', {type: 'getPfno'}, function (msg) {
            if (msg != "false") {
                pf = msg.replace(/["']/g, "");
                $.post('../db/UserInformations.php', { pfno: pf, type: 'GetUserName' }, function (msg) {
                    if (msg != "false") {
                        document.getElementById('name').innerHTML = "Welcome ".concat(msg.replace(/["']/g, ""));
                    }
                }).fail(function (msg) {
                    alert("fail : " + msg);
                });

                $.post('../db/UserInformations.php', { pfno: pf, type: 'GetADMSUserRole' }, function (msg) {
                    if (msg != "false") {
                        document.getElementById('desig').innerHTML = "Signed in as ".concat(msg.replace(/["']/g, ""));
                    }
                }).fail(function (msg) {
                    alert("fail : " + msg);
                });
            }


        }).fail(function (msg) {
            alert("fail : " + msg);
        });

    });

</script>
<div>
<table border="0" >
<tr>
<td style="width: 20%;"><img height=80 width=300 src="img/header.png" /></td>
<td style="width: 40%;"> <center><img height=80 width=300 src="img/title.png" /></center></td>
<td style="width: 20%;text-align: right;border-width: 2px;border-color: #000;font-family: 'Trebuchet MS';padding-right: 3em;">

<p id="name"></p>
<p id="desig"></p>
</td>
</tr>
</table>
</div>

</body>
</html>
