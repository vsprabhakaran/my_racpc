
<!doctype html>
<?php
        session_start();
?>
<html lang=''>
<head>
<script src="jquery-latest.min.js" type="text/javascript"></script>
    <style type="text/css">
        .container
        {
            height: 100%; 
            min-height: 100%;
        }

        #name {
            float: left;
            width: 70%;
            height: 70%;
            padding-right: 1ex;
            font-size: medium;
        }

        #desig {
            float: left;
            width: 70%;
            height: 20%;
            padding-right: 1ex;
            padding-top: 1ex;
            font-size: smaller;
        }


        #changePass{
            float: right;
            width: 20%;
            height: 100%;
        }


    </style>
</head>
<body style="background-image:url('img\\greyzz.png')">
<script type="text/javascript">
    var pf;
    $(document).ready(function () {
        $("#changePass").hide();
        $.post('getPfnoFromSession.php', {type: 'getPfno'}, function (msg) {
            if (msg != "false") {
                pf = msg.replace(/["']/g, "");
                $.post('db/UserInformations.php', { pfno: pf, type: 'GetUserName' }, function (msg) {
                    if (msg != "false") {
                        document.getElementById('name').innerHTML = msg.replace(/["']/g, "");
                    }
                }).fail(function (msg) {
                    alert("fail : " + msg);
                });

                $.post('db/UserInformations.php', { pfno: pf, type: 'GetADMSUserRole' }, function (msg) {
                    if (msg != "false") {
                        $("#changePass").show();
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
<div class="contaier">
    <div id="name"></div>
    <div id="changePass"><img src="img/changepassword.png" alt="change the password" style="min-height: 100%;max-width: 100%" onClick="window.open('changePwd.php','Details','resizable=1,scrollbars=yes,height=300,width=500')"/></div>
    <div id="desig"></div>

</div>
</td>
</tr>
</table>
</div>

</body>
</html>
