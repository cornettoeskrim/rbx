<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    include "function.php";
    echo json_encode(
        array(
            "lastOrder" => $lastOrder,
            "stock" => 67000,
            //"stock" => requestStock($systemCookie),
            "rate" => $rate,
            "totalSold" => $totalSold,
            "totalOrder" => $totalOrder
        ),
    );
} else {
    die(http_response_code(403));
}
