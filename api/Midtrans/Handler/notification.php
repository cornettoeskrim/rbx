<?php
// This is just for very basic implementation reference, in production, you should validate the incoming requests and implement your backend more securely.
// Please refer to this docs for sample HTTP notifications:
// https://docs.midtrans.com/en/after-payment/http-notification?id=sample-of-different-payment-channels

namespace Midtrans;

require_once dirname(__FILE__) . '/../../function.php';
require_once dirname(__FILE__) . '/../../koneksi.php';
require_once dirname(__FILE__) . '/../../MidtransConfig.php';
Config::$isProduction = true;
Config::$serverKey = 'Mid-server-iuiikDBP7sgcBZC4yaB6-ba1';

// non-relevant function only used for demo/example purpose
printExampleWarningMessage();

try {
    $notif = new \Midtrans\Notification();
}
catch (\Exception $e) {
    exit($e->getMessage());
}

$notif = $notif->getResponse();
$transaction = $notif->transaction_status;
$type = $notif->payment_type;
$order_id = $notif->order_id;
$fraud = $notif->fraud_status;

if ($transaction == 'capture') {
    // For credit card transaction, we need to check whether transaction is challenge by FDS or not
    if ($type == 'credit_card') {
        if ($fraud == 'challenge') {
            // TODO set payment status in merchant's database to 'Challenge by FDS'
            // TODO merchant should decide whether this transaction is authorized or not in MAP
            echo "Transaction order_id: " . $order_id ." is challenged by FDS";
        } else {
            // TODO set payment status in merchant's database to 'Success'
            echo "Transaction order_id: " . $order_id ." successfully captured using " . $type;
        }
    }
} else if ($transaction == 'settlement') {
    // TODO set payment status in merchant's database to 'Settlement'
    mysqli_query($conn, "UPDATE pembayaran SET status = 'PAID' WHERE order_id = '$order_id'") or die(mysqli_error());
    $getData = $conn->query("SELECT * FROM pembayaran WHERE order_id = '$order_id'");
    $data = $getData->fetch_assoc();
    if(requestPurchases($systemCookie, $data['product_id'], $data['set_harga'], $data['user_id']) == true){
        mysqli_query($conn, "UPDATE pembayaran SET delivered = 'true' WHERE order_id = '$order_id'") or die(mysqli_error());
    }else{
        mysqli_query($conn, "UPDATE pembayaran SET delivered = 'false' WHERE order_id = '$order_id'") or die(mysqli_error());
    }
} else if ($transaction == 'pending') {
    // TODO set payment status in merchant's database to 'Pending'
    mysqli_query($conn, "UPDATE pembayaran SET status = 'PENDING' WHERE order_id = '$order_id'") or die(mysqli_error());
} else if ($transaction == 'deny') {
    // TODO set payment status in merchant's database to 'Denied'
    mysqli_query($conn, "UPDATE pembayaran SET status = 'DENIED' WHERE order_id = '$order_id'") or die(mysqli_error());
} else if ($transaction == 'expire') {
    // TODO set payment status in merchant's database to 'expire'
    mysqli_query($conn, "UPDATE pembayaran SET status = 'EXPIRED' WHERE order_id = '$order_id'") or die(mysqli_error());
} else if ($transaction == 'cancel') {
    // TODO set payment status in merchant's database to 'Denied'
    mysqli_query($conn, "UPDATE pembayaran SET status = 'CANCEL' WHERE order_id = '$order_id'") or die(mysqli_error());
}

function printExampleWarningMessage() {
    if ($_SERVER['REQUEST_METHOD'] != 'POST') {
        echo 'Notification-handler are not meant to be opened via browser / GET HTTP method. It is used to handle Midtrans HTTP POST notification / webhook.';
    }
    if (strpos(Config::$serverKey, 'your ') != false ) {
        echo "<code>";
        echo "<h4>Please set your server key from sandbox</h4>";
        echo "In file: " . __FILE__;
        echo "<br>";
        echo "<br>";
        echo htmlspecialchars('Config::$serverKey = \'<your server key>\';');
        die();
    }   
}