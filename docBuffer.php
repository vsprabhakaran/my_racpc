<html>
<body>
<?php
	$file = "10107610014.pdf";
        $fileDir = 'D://uploads/4032/';
        $filePath = $fileDir . $file;

        if (file_exists($filePath))
        {
			 $contents = file_get_contents($filePath);
            header('Content-Type: application/pdf');
            header('Content-Length: ' . filesize($filePath));
            echo $contents;
        }
		else
		echo "file not found";
?>

</body>
</html>