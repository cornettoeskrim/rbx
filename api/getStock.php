<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
include "function.php";
echo json_encode(
    array(
        "stock" => requestStock($systemCookie), 
        "rate" => $rate,
        "totalSold" => $total_sold,
        "totalOrder" => $total_order
    ),
);
}else{
    die(http_response_code(403));
}
?>