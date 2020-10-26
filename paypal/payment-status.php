<?php
if(!empty($_GET['id'])){
    // Include and initialize database class
    include_once 'DB.class.php';
    $db = new DB;
    
    // Get payment details
    $conditions = array(
        'where' => array('id' => $_GET['id']),
        'return_type' => 'single'
    );
    $paymentData = $db->getRows('payments', $conditions);
    
    // Get product details
    $conditions = array(
        'where' => array('id' => $paymentData['product_id']),
        'return_type' => 'single'
    );
    $productData = $db->getRows('products', $conditions);
}
?>

<!DOCTYPE html>
<html lang="en-US">
<head>
<title>Payment Status - PayPal Express Checkout by CodexWorld</title>
<meta charset="utf-8">

<!-- Stylesheet file -->
<link rel="stylesheet" href="css/style.css">
</head>
<body>
<div class="container">
    <h1>PayPal Express Checkout - Payment Status</h1>
    <div class="status">
        <?php if(!empty($paymentData)){ ?>
            <h1 class="success">Your Payment has been Successful!</h1>
            <h4>Payment Information</h4>
            <p><b>TXN ID:</b> <?php echo $paymentData['txn_id']; ?></p>
            <p><b>Paid Amount:</b> <?php echo $paymentData['payment_gross'].' '.$paymentData['currency_code']; ?></p>
            <p><b>Payment Status:</b> <?php echo $paymentData['payment_status']; ?></p>
            <p><b>Payment Date:</b> <?php echo $paymentData['created']; ?></p>
            <p><b>Payer Name:</b> <?php echo $paymentData['payer_name']; ?></p>
            <p><b>Payer Email:</b> <?php echo $paymentData['payer_email']; ?></p>
            
            <h4>Product Information</h4>
			<p><b>Name:</b> <?php echo $productData['name']; ?></p>
			<p><b>Price:</b> <?php echo $productData['price'].' '.$productData['currency']; ?></p>
        <?php }else{ ?>
            <h1 class="error">Your Payment has Failed</h1>
        <?php } ?>
    </div>
    <a href="index.php" class="btn-link">Back to Home</a>
</div>
</body>
</html>