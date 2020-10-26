<?php
// Redirect to the home page if id parameter not found in URL
if(empty($_GET['id'])){
    header("Location: index.php");
}

// Include and initialize database class
include_once 'DB.class.php';
$db = new DB;

// Include and initialize paypal class
include_once 'PaypalExpress.class.php';
$paypal = new PaypalExpress;

// Get product ID from URL
$productID = $_GET['id'];

// Get product details
$conditions = array(
    'where' => array('id' => $productID),
    'return_type' => 'single'
);
$productData = $db->getRows('products', $conditions);

// Redirect to the home page if product not found
if(empty($productData)){
    header("Location: index.php");
}
?>

<!DOCTYPE html>
<html lang="en-US">
<head>
<title>Checkout - PayPal Express Checkout by CodexWorld</title>
<meta charset="utf-8">

<!-- Stylesheet file -->
<link rel="stylesheet" href="css/style.css">

<!-- PayPal checkout JS library -->
<script src="https://www.paypalobjects.com/api/checkout.js"></script>
</head>
<body>
<div class="container">
    <h1>PayPal Express Checkout - Checkout</h1>
    <div class="item">
        <!-- Product details -->
        <img src="images/<?php echo $productData['image']; ?>"/>
        <p>Name: <?php echo $productData['name']; ?></p>
        <p>Price: <?php echo $productData['price']; ?></p>
        
        <!-- Checkout button -->
        <div id="paypal-button"></div>
    </div>
</div>

<!--
JavaScript code to render PayPal checkout button and execute payment
-->
<script>
paypal.Button.render({
    // Configure environment
    env: '<?php echo $paypal->paypalEnv; ?>',
    client: {
        sandbox: '<?php echo $paypal->paypalClientID; ?>',
        production: '<?php echo $paypal->paypalClientID; ?>'
    },
    // Customize button (optional)
    locale: 'en_US',
    style: {
        size: 'small',
        color: 'gold',
        shape: 'pill',
    },
    // Set up a payment
    payment: function (data, actions) {
        return actions.payment.create({
            transactions: [{
                amount: {
                    total: 70,
                    currency: '<?php echo $productData['currency']; ?>'
                }
            }]
      });
    },
    // Execute the payment
    onAuthorize: function (data, actions) {
        return actions.payment.execute()
        .then(function () {
            // Show a confirmation message to the buyer
            //window.alert('Thank you for your purchase!');
            
            // Redirect to the payment process page
            window.location = "process.php?paymentID="+data.paymentID+"&token="+data.paymentToken+"&payerID="+data.payerID+"&items=0,1,2";
        });
    }
}, '#paypal-button');
</script>
</body>
</html>