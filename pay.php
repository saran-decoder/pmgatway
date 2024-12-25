<?php include('include/header.php'); ?>

<title>Razorpay Payment Gateway Integration in PHP</title>
<?php include('include/container.php');?>
<div class="container">
	<div class="row">
	<h2>Example: Razorpay Payment Gateway Integration in PHP</h2>
	<br><br><br>
<?php

require('config.php');
require('vendor/autoload.php');

session_start();

use Razorpay\Api\Api;

$api = new Api($keyId, $keySecret);

$amount = $_POST['amount'];

// echo "<pre>";
// print_r($api);
// echo "</pre>";

// die();

$orderData = array (
    'receipt'         => 'R_' . rand(),
    'amount'          => $amount * 100,
    'currency'        => 'INR',
    'payment_capture' => 1
);

// echo "<pre>";
// print_r($orderData);
// echo "</pre>";

$razorpayOrder = $api->order->create($orderData);

// echo "<pre>";
// print_r($razorpayOrder);
// echo "</pre>";

// die();

$razorpayOrderId = $razorpayOrder['id'];

$_SESSION['razorpay_order_id'] = $razorpayOrderId;

$displayAmount = $amount = $orderData['amount'];

if ($displayCurrency !== 'INR') {
    $url = "https://api.fixer.io/latest?symbols=$displayCurrency&base=INR";
    $exchange = json_decode(file_get_contents($url), true);

    $displayAmount = $exchange['rates'][$displayCurrency] * $amount / 100;
}

$data = [
    "key"               => $keyId,
    "amount"            => $amount,
    "name"              => $_POST['item_name'],
    "description"       => $_POST['item_description'],
    "image"             => "https://thumbs.dreamstime.com/b/eagle-logo-design-template-incorporates-strong-stylized-depiction-eagle-s-head-vector-illustration-eagle-logo-design-321396051.jpg",
    "prefill"           => [
    "name"              => $_POST['cust_name'],
    "email"             => $_POST['email'],
    "contact"           => $_POST['contact'],
    ],
    "notes"             => [
    "address"           => $_POST['address'],
    "merchant_order_id" => "12312321",
    ],
    "theme"             => [
    "color"             => "#F37254"
    ],
    "order_id"          => $razorpayOrderId,
];

if ($displayCurrency !== 'INR')
{
    $data['display_currency']  = $displayCurrency;
    $data['display_amount']    = $displayAmount;
}

$json = json_encode($data);


require("checkout/manual.php");

?>

</div>

<?php include('include/footer.php');?>
