<!doctype html>
<html lang=''>
<head>
   <link rel="stylesheet" href="css/styles.css">
     <?php
        session_start();
        if( $_SESSION["role"] != "RACPC_ADMIN")
        {
           $_SESSION["role"] = "";
        ?><meta http-equiv="refresh" content="0;URL=../login.html"><?php
        }
    ?>
</head>
<body>

<div>
   <p>closing the loan Panel</p>

</div>

</body>
</html>
