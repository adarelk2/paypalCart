<?php
include_once 'DB.class.php';
$db = new DB;
$conditions = array(
   'where' => array('payment_gross' => 60),
   'return_type' => 'count'
);
$productData = $db->getRows('payments', $conditions);

$conn = new mysqli("localhost", "root", "", "webshop");
$query = $conn->query("SELECT *from products");
$jsonData = [];
$count =0;
while($row = $query->fetch_array())
{
   $jsonData[$count] = $row;
   $count++;
}
echo json_encode($jsonData);

?>
<head>
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>

  </head>