<?php

require 'config.php';
require 'vendor/autoload.php';

session_start();

// Create the Razorpay Order
use Razorpay\Api\Api;

$api = new Api($keyId, $keySecret);

$amount = $_POST['price'];
$name = $_POST['name'];
$email = $_POST['email'];
$phone = $_POST['phone'];

$_SESSION['amount'] = $amount;
$_SESSION['name'] = $name;
$_SESSION['email'] = $email;

$order = array (
    'receipt' => '99999',
    'amount' => $amount * 100,
    'currency' => 'INR',
    'payment_capture' => 1
);

$new_order = $api->order->create($order);

// echo "<pre>";
// print_r($new_order);
// echo "</pre>";
// die();

$order_id = $new_order['id'];
$order_amount = $new_order['amount'];
$order_currency = $new_order['currency'];
$order_receipt = $new_order['receipt'];
$order_time = $new_order['created_at'];
$order_status = $new_order['status'];

$_SESSION['razorpay_order_id'] = $order_id;

$displayAmount = $amount = $order['amount'];

if ($displayCurrency !== 'INR')
{
    $url = "https://api.fixer.io/latest?symbols=$displayCurrency&base=INR";
    $exchange = json_decode(file_get_contents($url), true);

    $displayAmount = $exchange['rates'][$displayCurrency] * $amount / 100;
}

$data = [
    "key"               => $keyId, // Enter the Key ID generated from the Dashboard
    "amount"            => $amount, // Amount is in currency subunits. Default currency is INR. Hence, 50000 refers to 50000 paise
    "currency"          => $displayCurrency,
    "name"              => "Tester",
    "description"       => "Test transaction",
    "image"             => "https://cdn.razorpay.com/logos/GhRQcyean79PqE_medium.png",
    "prefill"           => [
        "name"              => $name,
        "email"             => $email,
        "contact"           => $phone,
    ],
    "notes"             => [
        "address"           => "Razorpay Corporate Office",
        "merchant_order_id" => "12312321",
    ],
    "theme"             => [
        "color"             => "#3399cc"
    ],
    "order_id"          => $order_id, // This is a sample Order ID. Pass the `id` obtained in the response of Step 1
];

if ($displayCurrency !== 'INR')
{
    $data['display_currency']  = $displayCurrency;
    $data['display_amount']    = $displayAmount;
}

$json = json_encode($data);

?>


<form action="verify.php" method="POST">
  <script
    src="https://checkout.razorpay.com/v1/checkout.js"
    data-key="<?php echo $data['key']?>"
    data-amount="<?php echo $data['amount']?>"
    data-currency="INR"
    data-name="<?php echo $data['name']?>"
    data-image="<?php echo $data['image']?>"
    data-description="<?php echo $data['description']?>"
    data-prefill.name="<?php echo $data['prefill']['name']?>"
    data-prefill.email="<?php echo $data['prefill']['email']?>"
    data-prefill.contact="<?php echo $data['prefill']['contact']?>"
    data-notes.shopping_order_id="3456"
    data-order_id="<?php echo $data['order_id']?>"
    <?php if ($displayCurrency !== 'INR') { ?> data-display_amount="<?php echo $data['display_amount']?>" <?php } ?>
    <?php if ($displayCurrency !== 'INR') { ?> data-display_currency="<?php echo $data['display_currency']?>" <?php } ?>
  >
  </script>
  <!-- Any extra fields to be submitted with the form but not sent to Razorpay -->
  <input type="hidden" name="shopping_order_id" value="3456">
</form>