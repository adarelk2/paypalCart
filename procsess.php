<?php
$redirectStr = '';
if(!empty($_GET['paymentID']) && !empty($_GET['token']) && !empty($_GET['payerID']) ){
    // Include and initialize database class
    include_once 'DB.class.php';
    $db = new DB;

    // Include and initialize paypal class
    include_once 'PaypalExpress.class.php';
    $paypal = new PaypalExpress;
    
    // Get payment info from URL
    $paymentID = $_GET['paymentID'];
    $token = $_GET['token'];
    $payerID = $_GET['payerID'];
    $itemsPruduct = (explode(",",$_GET['items']));
    // Validate transaction via PayPal API
    $paymentCheck = $paypal->validate($paymentID, $token, $payerID, 0);
    
    // If the payment is valid and approved
    if($paymentCheck && $paymentCheck->state == 'approved'){
      
        // Get the transaction data
        $id = $paymentCheck->id;
        $state = $paymentCheck->state;
        $payerFirstName = $paymentCheck->payer->payer_info->first_name;
        $payerLastName = $paymentCheck->payer->payer_info->last_name;
        $payerName = $payerFirstName.' '.$payerLastName;
        $payerEmail = $paymentCheck->payer->payer_info->email;
        $payerID = $paymentCheck->payer->payer_info->payer_id;
        $payerCountryCode = $paymentCheck->payer->payer_info->country_code;
        $paidAmount = $paymentCheck->transactions[0]->amount->details->subtotal;
        $currency = $paymentCheck->transactions[0]->amount->currency;
        
        // If payment price is valid
        $cost = 0;
        $cost = $db->getCost($itemsPruduct);
        if($paidAmount >= $cost){
                foreach($itemsPruduct as $key)
                {
                    $data = array(
                        'product_id' => $key,
                        'txn_id' => $id,
                        'payment_gross' => $cost,
                        'currency_code' => $currency,
                        'payer_id' => $payerID,
                        'payer_name' => $payerName,
                        'payer_email' => $payerEmail,
                        'payer_country' => $payerCountryCode,
                        'payment_status' => $state,
                        'username' => "test"
                    );
                    $insert = $db->insert('payments', $data);
                }
            
            // Add insert id to the URL
            $redirectStr = '?id='.$insert;
        }
    }
    // Redirect to payment status page
    header("Location:payment-status.php".$redirectStr);
}else{
    // Redirect to the home page
    header("Location:index.php");
}
?>
