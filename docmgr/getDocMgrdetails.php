<?php
//$q1 = intval($_GET['q']);
$con=mysqli_connect('localhost','root','','racpc_automation');
if (!$con) {
  die('Could not connect: ' . mysqli_error($con));
}

$q2 = mysqli_real_escape_string($con, $_GET['q']);

$result = 
mysqli_query($con,"select acc_holder_name as a, l.branch_code as b, folio_no as c,
 b.branch_name as d
from 
loan_account_mstr l,branch_mstr b
where l.branch_code = b.branch_code
and loan_acc_no = '".$q2."' ");

echo "<table border='1'>
<tr>
<th>acc_holder_name</th>
<th>branch_code</th>
<th>folio_no</th>
<th>branch name</th>
</tr>";

while($row = mysqli_fetch_array($result)) {
//  echo "<tr>";
$a1 = $row['a'];
$a2 = $row['b'];
$a3 = $row['c'];
$a4 = $row['d'];

$arr = array($a1,$a2,$a3,$a4);

echo json_encode($arr);


//$answer = array('AccHolderName'=>$a1,
//                'BranchCode' => $a2,
//                'FolioNo'=>$a3,
//                'BranchName' => $a4                 
//            );
//echo $answer;
//return json_encode($answer);


//  echo "<td>" . $row['a'] . "</td>";
//  echo "<td>" . $row['b'] . "</td>";
//  echo "<td>" . $row['c'] . "</td>";
//  echo "<td>" . $row['d'] . "</td>";
//  echo "</tr>";
}
echo "</table>";

mysqli_close($con);
?>