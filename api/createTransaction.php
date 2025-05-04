<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
require_once dirname(__FILE__) . '/function.php';

$robux = $_GET['robux'];
$setharga = $_GET['setharga'];
$user_id = $_GET['id'];
$game_id = $_GET['idgame'];
$universeId = $_GET['universeId'];
$productId = $_GET['productId'];
$email = $_GET['email'];
if (!$robux || !$setharga || !$user_id || !$game_id || !$universeId || !$productId || !$email){
    http_response_code(403);
    echo json_encode(array("status" => "failed", "reason" => "Invalid Request"));
}else{
$getAmount = $setharga * $rate;

require_once dirname(__FILE__) . '/MidtransConfig.php';
require_once dirname(__FILE__) . '/koneksi.php';

// Set your Merchant Server Key
\Midtrans\Config::$serverKey = '';
// Set to Development/Sandbox Environment (default). Set to true for Production Environment (accept real transaction).
\Midtrans\Config::$isProduction = true;
// Set sanitization on (default)
\Midtrans\Config::$isSanitized = true;
// Set 3DS transaction for credit card to true
\Midtrans\Config::$is3ds = true;
$order_id = rand();
$params = array(
    'transaction_details' => array(
        'order_id' => $order_id,
        'gross_amount' => $getAmount,
    ),
    'customer_details' => array(
        'email' => $email
    ),
);
$snapToken = \Midtrans\Snap::getSnapToken($params);
if($conn->query("INSERT INTO pembayaran (jumlah_robux, set_harga, user_id, game_id, universe_id, payment, order_id, status, token, delivered, product_id) VALUES ('$robux', '$setharga', '$user_id', '$game_id', '$universeId', 'QRIS', '$order_id', 'PENDING', '$snapToken', 'false', '$productId')")){
echo json_encode(array("order_id" => $order_id, "token" => $snapToken));
}else{
    echo 'Ukhnow error.';
}
}
}else{
    die(http_response_code(403));
}
?>