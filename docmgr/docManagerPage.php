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
            switch (tabName) {
                case 'inSlipTab':
                 {
                   //$("#contentFrame").prop("scrolling","no");
                    contentIFrame.src = "docManagerPageInSlip.php";
                    break;
                }
                case 'outSlipTab':
                 {
                   //$("#contentFrame").prop("scrolling","no");
                    contentIFrame.src = "docManagerPageOutSlip.php";
                    break;
                }
                case 'printStickerTab':
                 {
                   //$("#contentFrame").prop("scrolling","no");
                    contentIFrame.src = "docManagerPrintSticker.php";
                    break;
                }
                case 'ViewOutReport':
                {
                //    $("#contentFrame").prop("scrolling","yes");
                    contentIFrame.src = './ViewReport/ViewOutReport.php';
                    break;
                }
                case 'BulkSlip':
                {

                    contentIFrame.src = 'docManagerBulkSlipInput.php';
                    //$("#contentFrame").prop("scrolling","yes");
                    break;
                }
                case 'ViewDoc':
                {
                  $("#contentFrame").prop("scrolling","no");
                    contentIFrame.src = '../viewDoc.php';
                    break;
                }
                case 'ViewInReport':
                {
			$("#contentFrame").prop("scrolling","no");
                    contentIFrame.src = './ViewReport/ViewInReport.php';
                    break;
                }
        
            }
        }
    </script>
</head>
<body style="background-image:url('../img/greyzz.png'); margin:0px;">


<div>
<table border="0" style="width:100%;height:100%;border-width:2px; border-spacing:0px">
<tr>
<td colspan="3">
<iframe frameBorder="0" scrolling="no" src="../header.php" style="width: 100%;height: 90px;" marginheight="0" marginwidth="0" frameborder="0">
</iframe>
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
   <!--
    <li id="ViewReport"><a href='#' onclick="displayPanel('ViewReport')"><span>View Report</span></a></li>
   
   <li id="ViewOutReport" ><a href='#' onclick="displayPanel('ViewOutReport')"><span>Out Slip Report</span></a></li>
    -->
   <li id="ViewDoc" ><a href='#' onclick="displayPanel('ViewDoc')"><span>View Document</span></a></li>
    <li id="BulkSlip"><a href='#' onclick="displayPanel('BulkSlip')"><span>Bulk Slip</span></a></li>
   <li id="ViewInReport"><a href='#' onclick="displayPanel('ViewInReport')"><span>In-Report</span></a></li>
   <li id="ViewOutReport"><a href='#' onclick="displayPanel('ViewOutReport')"><span>Out-Report</span></a></li>
   <li style=" text-align:right; visibility: hidden;" >&nbsp;</li>
   <li style="text-align:center;float:right;"><a href='../logout.php'><span>Logout</span></a></li>
</ul>
</div>
</td></tr>
<tr>
<td ></td>
<td style="width: 100%">
<iframe id="contentFrame" frameBorder="0" src="docManagerPageOutSlip.php"  style="width: 100%;height: 800px;padding-bottom:2em;" marginheight="0" marginwidth="0" frameborder="0">
</iframe></td>
<td ></td>
</tr>
</table>
</div>
<div>
  <iframe frameBorder="0" scrolling="no" src='../footer.php' style="width: 100%;height: 2em; position:fixed; bottom:0; background-color: #0f71ba;vertical-align:bottom;" marginheight="0" marginwidth="0" frameborder="0"/>
</div>

</body>
</html>
<?php
}
?>
