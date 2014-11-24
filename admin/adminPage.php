<!doctype html>
<html lang=''>
<head>
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
   <title>RACPC Admin</title>
    <link rel="stylesheet" href="css/styles.css">
    <link rel="stylesheet" href="../menustyles.css">
   <script src="../jquery-latest.min.js" type="text/javascript"></script>
   <script src="../script.js"></script>
    <script type="text/javascript">
        var currentTab = 'uploadLoanTab';
        function displayPanel(tabName) {
            document.getElementById(currentTab).className = '';
            document.getElementById(tabName).className = 'active';
            
            currentTab = tabName;
            var contentIFrame = document.getElementById("contentFrame");
            switch(tabName)
            {
                case 'uploadLoanTab':
                {
                    contentIFrame.src = "uploadDocument.php";
                    break;
                }
                case 'closeLoanTab':
                 {
                    contentIFrame.src = "closeLoan.php";
                    break;
                }
                case 'manageUserTab':
                 {
                    contentIFrame.src = "manageUsers.php";
                    break;
                }
                case 'printStickerTab':
                 {
                    contentIFrame.src = "printSticker.php";
                    break;
                }
                default:
                {
                    contentIFrame.src = "uploadDocument.php";
                    break;
                }
            }
        }
    </script>
</head>
<body style="background-image:url('../img/greyzz.png')">

<div>
    
<table border="0" style="width:100%;height:100%;border-width:2px;">
<tr>
<td colspan="3"> <div>
<iframe frameBorder="0" scrolling="no" src="../header.php" style="width: 100%;height: 90px;" marginheight="0" marginwidth="0" frameborder="0"></iframe></div>
</td>
</tr>
<tr><td colspan="3"><div id='cssmenu'>
<ul>
   <li id="uploadLoanTab" class='active' style="width: 15%"><a href='#' onclick="displayPanel('uploadLoanTab')"><span>Documents</span></a></li>
   <li id="closeLoanTab"><a href='#' onclick="displayPanel('closeLoanTab')"><span>Loan Closure</span></a></li>
   <li id="manageUserTab" ><a href='#' onclick="displayPanel('manageUserTab')"><span>Manage User</span></a></li>
   <li id="printStickerTab" ><a href='#' onclick="displayPanel('printStickerTab')"><span>Print Sticker</span></a></li>
   <li style="width: 33%; text-align:right; visibility: hidden;">&nbsp;</li>
   <li style="text-align:center;"><a href='../logout.php' ><span>Logout</span></a></li>
</ul>
</div>
</td></tr>
<tr >
<td style="width: 10%"><br/></td>
<td> <iframe id="contentFrame" frameBorder="0" scrolling="no" src="uploadDocument.php" style="width: 100%;height: 550px;" marginheight="0" marginwidth="0" frameborder="0"></iframe> </td>
<td ><br/></td>
</tr>
<tr><td colspan="3">
    <iframe frameBorder="0" scrolling="no" src='../footer.php' style="width: 100%;height: 30%; background-color: #0f71ba;" marginheight="0" marginwidth="0" frameborder="0"/>
</td></tr>
</table>
</div>

</body>
</html>
<?php
}
?>