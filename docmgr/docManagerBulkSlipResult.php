<?php
session_start();
if( $_SESSION["role"] != "RACPC_ADMIN" && $_SESSION["role"] != "RACPC_DM")
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
        <link rel="stylesheet" href="css/styles.css">
        <link rel="stylesheet" href="../css/inputFormStyles.css" type="text/css">
        <link rel="stylesheet" href="../css/pure-min.css">
        <link rel="stylesheet" href="../css/sortTableStyle/style.css" type="text/css">
        <style type="text/css">
        p {
            font-family: 'Trebuchet MS';
            font-size: large;
        }
        .button-error
        {
            color: white;
            border-radius: 4px;
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
            background: rgb(202, 60, 60); /* this is a maroon */
        }
        .button-success {
            color: white;
            border-radius: 4px;
            text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
            background: rgb(28, 184, 65); /* this is a green */
        }
        </style>
        <script type="text/javascript" src="../jquery-latest.min.js"></script>
        <script type="text/javascript" src="../jquery.tablesorter.min.js"></script>
        <script type="text/javascript">
        function goBack() {
            window.location = "docManagerBulkSlipInput.php";
        }

        function printTable()
    {
        var divToPrint=document.getElementById('myTable');
        newWin= window.open("");
        newWin.document.write(divToPrint.outerHTML);
        newWin.document.close();
        newWin.focus();
        newWin.print();
        newWin.close();
    }

    </script>
</head>
<body>

<script type="text/javascript">
$(document).ready(function () {
    $("#myTable").tablesorter();
    //debuggin
    //if ($('#BulkSlipPFNumbers').val().length != 0) alert($('#BulkSlipPFNumbers').val());
    //end of debuggin
    $('#formid').bind("keyup keypress", function (e) {
        var code = e.keyCode || e.which;
        if (code == 13) {
            e.preventDefault();
            return false;
        }
    });
    $('#formid').bind('submit', function () {
        return confirm("Confirm Slip Gerneration");
    });
});

</script>
<form id="formid" class="pure-form pure-form-aligned" action="genBulkSlip.php" method="post" enctype="multipart/form-data">
    <br/><br/>
    <?php
    require_once("../db/userQueries.php");
    require_once("../db/loanQueries.php");


    $isSlipGenerationPossbile = TRUE;
    if (!empty($_FILES) && !empty($_POST['SlipType']) && !empty($_POST['pfno']))
    {
        $fileContents = file_get_contents($_FILES['file']['tmp_name']);
        $slipType = $_POST['SlipType'];
        $OusiderPFNumber = $_POST['pfno'];
        $DocMgrPFNumber = $_SESSION['pfno'];
        $errorMessage = "null";
        $lines = explode(PHP_EOL, $fileContents);

        //Check for only numbers in the file.
        $lineCounter = 0;
        foreach ($lines as $line) {
            $lineCounter++;
            if(!preg_match('/^[0-9]+$/',$line)) {
                echo "<p>Error: File contains invalid characters in line ".$lineCounter.".</p>The file should contain one account number per line and no other special characters are allowed. <br/>Tip: Remove empty line at the end of file as it might be seen as empty account number.";
                $isSlipGenerationPossbile = FALSE;
                break;
            }
        }


        //Check for repeating account numbers in the file
        if(($isSlipGenerationPossbile) && (count(array_unique($lines))<count($lines)))
        {
            echo "<p>Error: The file contains repeating account numbers.</p><br/>Tip: Please remove them and upload the file again.";
            $isSlipGenerationPossbile = FALSE;
        }
        $table = '<div style="height:100%;width:100%;text-align:center;">';
        $hasErrorAccountNumbers = FALSE;
        $PassedAccountNumberCSV = "";
        if($isSlipGenerationPossbile)
        {
            printTableHeader($table,$slipType);
            foreach ($lines as $currentAccNo) {
                if(!SlipGenerationTest($slipType,$OusiderPFNumber,$DocMgrPFNumber,$currentAccNo,$errorMessage))
                {
                    printErrorTableRow($table,$currentAccNo,$errorMessage);
                    $hasErrorAccountNumbers = TRUE;
                }
                else
                {
                    printPassedTableRow($table,$currentAccNo,$errorMessage);
                    $PassedAccountNumberCSV .= $currentAccNo.",";
                }
            }
            //Removing the trailing comma
            $PassedAccountNumberCSV = trim($PassedAccountNumberCSV,",");
            $table.= '</tbody><table><div>';
            echo $table;
        }
    }
    else
    {
        echo "<p>Error: Required Informations not available</p>";
        $isSlipGenerationPossbile = FALSE;
    }
    ?>
    <table><tr><td>
        <div class="pure-controls" style="float: left;">
            <button class="button-error pure-button"  name="backButton2" style="float: left;" type="button"  onclick="goBack()">Back</button>&nbsp;&nbsp;
            <button class="pure-button pure-button-primary" id="printButton" name="printButton" style="" type="button"  onclick="printTable()">Print Table</button>&nbsp;&nbsp;
            <?php
            if($isSlipGenerationPossbile && $PassedAccountNumberCSV != "") // Generates this part only when all the uploaded numbers has valid slip generation possbility.
            {
                ?>
                <button class="pure-button <?php echo ($hasErrorAccountNumbers)?"button-error":"button-success"; ?>" id="formButton" style="float: right;" type="submit" >
                    <?php echo ($hasErrorAccountNumbers)?"Generate Slip Anyway":"Generate Slip"; ?>
                </button>
                <input type="hidden" id="BulkSlipAccNumbers" value="<?php echo $PassedAccountNumberCSV; ?>" name="BulkSlipAccNumbers"/>
                <input type="hidden" id="OutsiderPFNumber" value="<?php echo $OusiderPFNumber; ?>" name="OutsiderPFNumber" />
                <input type="hidden" id="SlipType" value="<?php echo $slipType; ?>" name="SlipType"/>
                <input type="hidden" id="reason" value="<?php echo $_POST['reason'] ?>" name="reason" />
                <?php } ?>
            </div>
        </td></tr></table>
    </form>
</body>
</html>
<?php
}
/*
Out-Slip Generation Conditions:
1. Valid account i.e in master.
2. account must be in adms.
3. Account should not be closed.
4. Account should not be accessed by document manager of different RACPC.
5. The outsider must exist in user master.
6. Account should not be accessed by the outsider who does not belong to the loan branch.
7. loan document should not be already in OUT status.
*/
function SlipGenerationTest($slipType, $OusiderPFNumber, $DocMgrPFNumber, $AccountNumber, &$errorMessage)
{
    $isSlipGenTestPassed = FALSE;
    if(!isValidAccount($AccountNumber))
    {
        $errorMessage = "Account invalid/unavailable in master";
    }
    else if(!isLoanAccountInADMS($AccountNumber))
    {
        $errorMessage = "Account is not available in ADMS.";
    }
    else if(!isLoanActive($AccountNumber))
    {
        $errorMessage = "Account is not active/closed.";
    }
    else if(getRacpcCodeofAccount($AccountNumber) != GetUserRacpcCode($DocMgrPFNumber))
    {
        $errorMessage = "Account belongs to different RACPC";
    }
    else if(!isValidUser($OusiderPFNumber))
    {
			$errorMessage = "Invalid Receiver/Returnee";
    }
    //When a user is adms user, then he should have access to all the documents of that racpc except other than branch view user.
    else if(!isUserRacpcUser($OusiderPFNumber) && (getBranchCode($AccountNumber) != GetUserBranchCode($OusiderPFNumber)))
    {
        $errorMessage = "Receiver/Returnee and this Account does not belong to same Branch.";
    }
    //When a user does belong to RACPC, he should have access to all his documents
    else if(isUserRacpcUser($OusiderPFNumber) && (getRacpcCodeofAccount($AccountNumber) != GetUserBranchCode($OusiderPFNumber)))
    {
        $errorMessage = "Receiver/Returnee and this Account does not belong to same RACPC.";
    }
    else
    {
        $isSlipGenTestPassed = TRUE;
    }

    //Document status checking
    if($isSlipGenTestPassed)
    {
        if($slipType == "Inslip")
        {
            $docStatus = GetDocumentStatusOfAccount($AccountNumber);
            if($docStatus)
            {
                if($docStatus == "IN")
                {
                    $errorMessage = "The document is already within RACPC.";
                    $isSlipGenTestPassed  = FALSE;
                }
                else if($docStatus == "C")
                {
                    $errorMessage = "The document is Closed.";
                    $isSlipGenTestPassed  = FALSE;
                }
                else if($docStatus != "OUT")
                {
                    $errorMessage = "The document is not valid for In-Slip Generation.";
                    $isSlipGenTestPassed  = FALSE;
                }
            }
            else
            {
                $errorMessage = "Document status not available";
                $isSlipGenTestPassed  = FALSE;
            }
        }
        else if($slipType == "Outslip")
        {
            $docStatus = GetDocumentStatusOfAccount($AccountNumber);
            if($docStatus)
            {
                if($docStatus == "OUT")
                {
                    $errorMessage = "The document is already OUT.";
                    $isSlipGenTestPassed  = FALSE;
                }
                else if($docStatus == "C")
                {
                    $errorMessage = "The document is Closed.";
                    $isSlipGenTestPassed  = FALSE;
                }
                else if($docStatus != "IN")
                {
                    $errorMessage = "The document is not valid for Out-Slip";
                    $isSlipGenTestPassed  = FALSE;
                }
            }
            else
            {
                $errorMessage = "Document status not available";
                $isSlipGenTestPassed  = FALSE;
            }
        }
        else
        {
            $errorMessage = "Invalid slip type given.";
            $isSlipGenTestPassed = FALSE;
        }
    }

    return $isSlipGenTestPassed;
}
function printErrorTableRow(&$table,$AccountNumber,$errorMessage)
{
    $rackNumber = GetRackNumberOfAccount($AccountNumber);
    $name = GetAccountNameOfAccount($AccountNumber);
    if($rackNumber == '') $rackNumber = "NA";
    if($name == '') $name ='NA';
    $table .= '<tr class="failed"><td>'.$AccountNumber.'</td><td>'.$name.'</td><td>'.$rackNumber.'</td><td>'.$errorMessage.'</td></tr>';
}
function printPassedTableRow(&$table,$AccountNumber,$errorMessage)
{
    $rackNumber = GetRackNumberOfAccount($AccountNumber);
    $name = GetAccountNameOfAccount($AccountNumber);
    if($rackNumber == '') $rackNumber = "NA";
    if($name == '') $name ='NA';
    $table .=  '<tr class="passed"><td>'.$AccountNumber.'</td><td>'.$name.'</td><td>'.$rackNumber.'</td><td>&#x2714;</td></tr>';
}
function printTableHeader(&$table,$slipType)
{
    $table .= '<table id="myTable" class="tablesorter" style="width:80%;margin: 0 auto;">
    <caption>Bulk '.$slipType.' Generation Preview</caption>
    <thead>
    <tr>
    <th>Account Number</th>
    <th>Name</th>
    <th>Rack Number</th>
    <th>Status</th>
    </tr>
    </thead>
    <tbody> ';
}

?>
