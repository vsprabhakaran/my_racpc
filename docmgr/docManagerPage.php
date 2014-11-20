<!doctype html>
<html lang=''>
<head>
   <title>Document Manager</title>
     <link rel="stylesheet" href="../css/styles.css">
    <link rel="stylesheet" href="../menustyles.css">
   <script src="../jquery-latest.min.js" type="text/javascript"></script>
   <script src="../script.js"></script>
    <script type="text/javascript">
        var currentTab = 'outSlipTab';
        function displayPanel(tabName) {
            document.getElementById(currentTab).className = '';
            document.getElementById(tabName).className = 'active';
            
            currentTab = tabName;
            var contentIFrame = document.getElementById("contentFrame");
            switch(tabName)
            {
                /*
                case 'viewLocationTab':
                {
                    contentIFrame.src = "";
                    break;
                }
                */
                case 'inSlipTab':
                 {
                    contentIFrame.src = "docManagerPageInSlip.php";
                    break;
                }
                case 'outSlipTab':
                 {
                    contentIFrame.src = "docManagerPageOutSlip.php";
                    break;
                }
                case 'printStickerTab':
                 {
                    contentIFrame.src = "../admin/printSticker.php";
                    break;
                }
        /*        default:
                {
                    contentIFrame.src = "docManagerPageOutSlip.php";
                    break;
                }
        */
            }
        }
    </script>
</head>
<body>


<div>
    <?php
        session_start();
        if( $_SESSION["role"] != "RACPC_DM")
        {
           $_SESSION["role"] = "";
        ?><meta http-equiv="refresh" content="0;URL=login.html"><?php
        }
    ?>
<table border="0" style="width:100%;height:100%;border-width:2px;">
<tr>
<td colspan="3"> <div>
<iframe frameBorder="0" scrolling="no" src="../header.php" style="width: 100%;height: 90px;" marginheight="0" marginwidth="0" frameborder="0"></iframe>
</div>
</td>
</tr>
<tr><td colspan="3">
    <div id='cssmenu'>
<ul>
    <li id="outSlipTab" class='active'><a href='#' onclick="displayPanel('outSlipTab')"><span>Out-Slip</span></a></li>
    <li id="inSlipTab" ><a href='#' onclick="displayPanel('inSlipTab')"><span>In-Slip</span></a></li>
    <!--
   <li id="viewLocationTab"  ><a href='#' onclick="displayPanel('viewLocationTab')"><span>View Location</span></a></li>
    -->
   <li id="printStickerTab"><a href='#' onclick="displayPanel('printStickerTab')"><span>Print Sticker</span></a></li>
   <li style="width: 33%; text-align:right; visibility: hidden;" >&nbsp;</li>
   <li style="text-align:center;"><a href='../logout.php'><span>Logout</span></a></li>
</ul>
</div>
</td></tr>
<tr>
<td style="width: 1%"><br/></td>
<td style="width: 100%" colspan="2">
<iframe id="contentFrame" frameBorder="0" scrolling="no" src="docManagerPageOutSlip.php"  style="width: 100%;height: 800px;" marginheight="0" marginwidth="0" frameborder="0">
</iframe></td>

</tr>
<tr><td colspan="3" ><br/></td></tr>
</table>
</div>

</body>
</html>
