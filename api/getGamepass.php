<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
include "function.php";
$universeId = $_GET['universeId'];
$price = $_GET['price'];

$getGame = json_decode(requestCookie("https://games.roblox.com/v1/games/$universeId/game-passes?limit=100&sortOrder=Asc", $systemCookie));
$arr = [];
foreach ($getGame->data as $i => $data) {
    if($data->price == $price AND $data->isOwned == false) {
        $getThumbnails = json_decode(file_get_contents("https://thumbnails.roblox.com/v1/game-passes?gamePassIds=$data->id&size=150x150&format=Png&isCircular=false"), true);
        $getForSale = json_decode(file_get_contents("https://apis.roblox.com/game-passes/v1/game-passes/$data->id/product-info"), true);
        $arr[$i]  = array([
            "id" => $data->id, 
            "name" => $data->name, 
            "price" => $data->price, 
            "isOwned" => $data->isOwned, 
            "productId" => $data->productId,
            "IsForSale" => $getForSale['IsForSale'], 
            "thumbnails" => $getThumbnails['data'][0]['imageUrl']
        ]);
    }
}

echo json_encode(['data' => $arr]);

}else{
    die(http_response_code(403));
}