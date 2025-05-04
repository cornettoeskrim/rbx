<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
include "function.php";
echo json_encode(array(
    "stock" => requestStock($systemCookie), 
    "rate" => $rate)
);
}else{
    die(http_response_code(403));
}
?>