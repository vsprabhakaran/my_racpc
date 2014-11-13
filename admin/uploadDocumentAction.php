<html>
    <head>
        <link rel="stylesheet" href="../css/pure-min.css">
         <style type="text/css">
          p {
           font-family: 'Trebuchet MS';
           font-size: large; 
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
                window.location = "uploadDocument.php";
            }
        </script>
    </head>
<body>
<?php
    if(!isset($_SESSION)) session_start();
    require("../db/loanQueries.php");
    //include("../db/loanQueries.php");
    $accountNo = $_POST['accNumber'];
    $folioNum = $_POST['folio_no'];
    $rackNum = $_POST['rack_no'];
	$branchCode=getBranchCode($accountNo);
    $isDocUpdateSuccess = TRUE;
    if($accountNo == "" || $folioNum == "" || $rackNum =="" || $_FILES['file']['name'] =="")
    {
		$isDocUpdateSuccess = FALSE;
	}
    $target_dir = "D:/uploads/";
	if(! is_dir("D:/uploads/$branchCode"))
	{
		mkdir("D:/uploads/$branchCode",0777);
	}
    $target_dir = $target_dir.$branchCode.'/'.basename( $_FILES['file']['name']);
    $uploadOk=1;
    
    //Uploading the file
    if ($isDocUpdateSuccess && !(move_uploaded_file($_FILES['file']['tmp_name'], $target_dir))) {
        $isDocUpdateSuccess = FALSE;
    }
    //Updating the DB
    if($isDocUpdateSuccess)
            $isDocUpdateSuccess = InsertLoanDocuments($accountNo,TRUE,$folioNum,$rackNum);
    //DB update failed so deleting the document
    if(!$isDocUpdateSuccess)
        unlink($target_dir);
        
?>
    <br/><br/>
    <?php
        if($isDocUpdateSuccess)
        {
    ?>
    <div class="pure-controls">
        <p>Update Document successful.</p>
		<button class="button-success pure-button" id="backButton1" name="backButton1" onclick="goBack()">Back</button>
	</div>
    
    <?php
        }
        else
        {
    ?>
    <div class="pure-controls">
        <p>Document Upload failed!!!.</p>
		<button class="button-error pure-button" id="backButton2" name="backButton2" onclick="goBack()">Back</button>
	</div>
    
    <?php
        }
    ?>
</body>
</html>