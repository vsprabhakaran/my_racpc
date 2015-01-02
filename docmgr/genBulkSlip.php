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
        require_once("../db/userQueries.php");
        require_once("../db/loanQueries.php");
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
            table td div
            {
                text-align: center;
            }
            .header
            {
                text-align: right;
                padding: 1em;
            }
            .values
            {
                text-align: center;
                padding: 1em;
            }
            .headerRows td
            {
                border: 0px;
            }
        </style>
        <script type="text/javascript" src="../jquery-latest.min.js"></script>
        <script type="text/javascript" src="../jquery.tablesorter.min.js"></script>
        <script type="text/javascript">
            function goBack() {
                window.location = "";
            } function printSlip() {
                $('#printButton').hide();
                window.print();
                $('#printButton').show();
            }
        </script>
    </head>
    <body>

        <script type="text/javascript">
            $(document).ready(function () {
            
                $('#formid').bind("keyup keypress", function (e) {
                    var code = e.keyCode || e.which;
                    if (code == 13) {
                        e.preventDefault();
                        return false;
                    }
                });
                $('#formid').bind('submit', function () {
                    return false;
                });
            });
            
        </script>



        <?php 
         if(!CheckForParameters())
         {
             echo "<p>Insufficient/Invalid parameters!!!</p>";
         }
         else
         {
             $BulkSlipAccNumbers = $_POST['BulkSlipAccNumbers'];
             $SlipType = $_POST['SlipType'];
             $OutsiderPFNumber = $_POST['OutsiderPFNumber'];
             $reason = $_POST['reason'];
             $DocMgrPFNumber = $_SESSION['pfno'];
             $BulkSlipAccNumArray = explode(',',$BulkSlipAccNumbers);
             $returnStatus = FALSE;
             foreach ($BulkSlipAccNumArray as $AccNumberIter) {
                 $returnStatus = FALSE;
                 $phno = GetUserPhone($AccNumberIter);

                 if($SlipType === "Inslip")
                 {
                     $returnStatus = InUpdateDocStatus($AccNumberIter,$OutsiderPFNumber,$DocMgrPFNumber,$reason,$phno);
                 }
                 else if($SlipType === "Outslip")
                 {
                     $returnStatus = OutUpdateDocStatus($AccNumberIter,$OutsiderPFNumber,$DocMgrPFNumber,$reason,$phno);
                 }
                 if(!$returnStatus) break;
                 
             }
             if(!$returnStatus)
             {
                 echo "<p>Database error occured during the slip generation!!!</p>";
             }
             else
             {
                echo DisplayGeneratedSlip();    
             }
         }
         ?>

        
    </body>
</html>
<?php
    }
    function DisplayGeneratedSlip()
    {
        $table = "";
        
        $BulkSlipAccNumbers = $_POST['BulkSlipAccNumbers']; 
        $OutsiderPFNumber = $_POST['OutsiderPFNumber'];
        $SlipType = $_POST['SlipType'];
        $reason = $_POST['reason'];
        $DocMgrPFNumber = $_SESSION['pfno'];
        $RacpcName = GetUserRacpcName($DocMgrPFNumber);
        $RacpcCode = GetUserRacpcCode($DocMgrPFNumber);
        

        PrintTableHeader($table,$SlipType,$RacpcCode,$RacpcName);
        PrintAccountNumbers($table,$BulkSlipAccNumbers);
        PrintOutsiderInfo($table,$OutsiderPFNumber,$SlipType);
        PrintDocMgrInfo($table,$DocMgrPFNumber);
        PrintReason($table,$reason);
        PrintSignature($table,$SlipType);
        PrintTalbeFooter($table);
        return $table;
    }
    function CheckForParameters()
    {
        if(isset($_POST['BulkSlipAccNumbers']) && isset($_POST['OutsiderPFNumber']) && isset($_POST['SlipType']) && isset($_POST['reason']))
        {
            return TRUE;
        }
        else
        {
            return FALSE;
        }
    }
    function PrintTableHeader(&$table,$SlipType,$RacpcCode,$RacpcName)
    {
        date_default_timezone_set('Asia/Calcutta');
        $dateTime = date('d/m/Y h:i:s a');

        $table.= '<div style="width:100%;height:100%;">
                    <table border="1" style="table-layout: fixed;width:90%;height:100%;border-width:2px;margin: 0 auto;">
                        <colgroup>
                           <col span="1" style="width: 20%;">
                           <col span="1" style="width: 60%;">
                           <col span="1" style="width: 20%;">
                        </colgroup>
                        <tr class="headerRows">
                            <td colspan="3">
                                <div>
                                    <img height="80" width="250" src="../img/header.png" alt="sbi logo" />
                                </div>
                            </td>
                        </tr>
                        <tr class="headerRows">
                            <td colspan="3">
                                <div>
                                    <h3>BULK '. strtoupper($SlipType).' FORM </h3>
                                </div>
                                <div>
                                    <h4>'. $RacpcName.' ('.$RacpcCode.')</h4>
                                </div>
                                <div>
                                    <h4>'.$dateTime.'</h4>
                                </div>
                            </td>
                        </tr>';
    }
    function PrintTalbeFooter(&$table)
    {
        $table.= '</table>
        <div class="pure-controls" style="text-align:center;padding:1em;"><button class="pure-button pure-button-primary" id="printButton" name="printButton" style="" type="button"  onclick="printSlip()">Print Slip</button>
        </div>';
    }
    function PrintAccountNumbers(&$table,$BulkSlipAccNumbers)
    {
        
        $table.= '<tr>
                    <td >
                        <p class="header">
                            Account Number
                        </p>
                    </td>
                    <td colspan="2" style="max-width:70%">
                        <p style="width:100%;word-wrap: break-word;white-space: normal;">
                            '.str_replace(",",", ",$BulkSlipAccNumbers).'
                        </p>
                    </td>
                </tr>';
    }
    function PrintOutsiderInfo(&$table,$OutsiderPFNumber,$SlipType)
    {
        $personType = ($SlipType == 'Outslip')?'Receiver':'Returnee';
        $OutsiderName = GetUserName($OutsiderPFNumber);
        $table.= '<tr>
                    <td >
                        <p class="header">
                            '.$personType.' Details
                        </p>
                    </td>
                    <td style="max-width:70%;">
                        <p class="values">
                            '.$OutsiderPFNumber.'<br/>'.$OutsiderName.'
                        </p>
                    </td>
                    <td >&nbsp;</td>
                </tr>';
    }
    function PrintDocMgrInfo(&$table,$DocMgrPFNumber)
    {
        $DocMgrName = GetUserName($DocMgrPFNumber);
        $table.= '<tr>
                    <td >
                        <p class="header">
                            Document Manager Details
                        </p>
                    </td>
                    <td style="max-width:70%;">
                        <p class="values">
                            '.$DocMgrPFNumber.'<br/>'.$DocMgrName.'
                        </p>
                    </td>
                    <td >&nbsp;</td>
                </tr>';
    }
    function PrintReason(&$table,$reason)
    {
        $table.= '<tr>
                    <td >
                        <p class="header">
                            Reason
                        </p>
                    </td>
                    <td style="max-width:70%;text-align:center;">
                        <p class="values" style="word-wrap: break-word;white-space: normal;">
                            '.$reason.'
                        </p>
                    </td>
                    <td >&nbsp;</td>
                </tr>';
    }
    function PrintSignature(&$table,$SlipType)
    {
        $personType = ($SlipType == 'Outslip')?'Receiver':'Returnee';
        $table.= '<tr>
                    <td colspan="3" >
                        <table style="width:100%;height:7em;margin: 0 auto;text-align:center;vertical-align: text-top;">
                            <tr style="vertical-align:top">
                                <td style="width:50%">
                                <p>Document Manager Signature</p>
                                </td>
                                <td style="width:50%">
                                <p>'.$personType.'\'s Signature</p>
                                </td>
                            <tr>
                        </table>                        
                    </td>
                </tr>';
    }
?>