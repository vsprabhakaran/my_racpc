<html>
    <head>
        <link rel="stylesheet" href="../css/pure-min.css">
         <style type="text/css">
          p {
           font-family: 'Trebuchet MS';
           font-size: large; 
          }
             body
             {
                 padding: 3em 3em 3em 3em;
             }
            .button-success,
            .button-error,
            .button-warning,
            .button-secondary {
                color: white;
                border-radius: 4px;
                text-shadow: 0 1px 1px rgba(0, 0, 0, 0.2);
            }

            .button-success {
                background: rgb(28, 184, 65); /* this is a green */
            }

            .button-error {
                background: rgb(202, 60, 60); /* this is a maroon */
            }

            .button-warning {
                background: rgb(223, 117, 20); /* this is an orange */
            }

            .button-secondary {
                background: rgb(66, 184, 221); /* this is a light blue */
            }
          </style>
        <script type="text/javascript">
            function goBack() {
                window.location = "docManagerPrintSticker.php";
            }
        </script>
    </head>
<body>
    <br/><br/>
<?php
    if(!isset($_SESSION)) session_start();
    require("../db/loanQueries.php");
    $role = $_SESSION['role'];
    $actionType = $_POST['actionType'];
    if(($actionType == "rackUpdate") && ($role == 'RACPC_DM'))
    {
        $accNumber = $_POST['accNumber'];
        $newRackNumber = $_POST['rack'];
        $rackUpdated = FALSE;
        if(isLoanAccountInADMS($accNumber) && isLoanActive($accNumber))
        {
            $rackUpdated = UpdateRackDetails($accNumber,$newRackNumber);
        }
        ($rackUpdated)?(printSuccessPage("Rack Update Successful")):(printErrorPage("Error updating rack!!!"));
    }
    
      
function printSuccessPage($message)
{
    printf('<div class="pure-controls"><p>%s</p><button class="button-success pure-button" id="backButton1" name="backButton1" onclick="goBack()">Back</button>', $message);
}
function printErrorPage($message)
{
    printf('<div class="pure-controls"><p>%s</p><button class="button-error pure-button" id="backButton2" name="backButton2" onclick="goBack()">Back</button>', $message);
}
?>
</body>
</html>