<?php
// Include and initialize database class
include_once 'DB.class.php';
$db = new DB;

// Get all products from database
$products = $db->getRows('products');
?>

<!DOCTYPE html>
<html lang="en-US">
<head>
<title>Products - PayPal Express Checkout by CodexWorld</title>
<meta charset="utf-8">

<!-- Stylesheet file -->
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h1>PayPal Express Checkout - Products</h1>
    
    <!-- List products -->
    <?php
    if(!empty($products)){
        foreach($products as $row){
    ?>
        <div class="item">
            <img src="images/<?php echo $row['image']; ?>"/>
            <p><?php echo $row['name']; ?></p>
            <p><b>Price:</b> <?php echo $row['price']; ?></p>
            <a href="checkout.php?id=<?php echo $row['id']; ?>">BUY</a>
        </div>
    <?php        
        }
    }else{
        echo '<p>Product(s) not found...</p>';
    }
    ?>
</div>
</body>
</html>