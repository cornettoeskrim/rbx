<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
include "function.php";
$id = $_GET['id'];
$getGame = json_decode(file_get_contents("https://games.roblox.com/v2/users/$id/games?sortOrder=Asc&limit=10"));
$arr = [];
foreach ($getGame->data as $i => $data) {
    $getThumbnails = json_decode(file_get_contents("https://thumbnails.roblox.com/v1/games/icons?universeIds=$data->id&size=256x256&format=Png&isCircular=false"), true);
    $arr[$i] = [
        "id" => $data->rootPlace->id, 
        "universeId" => $data->id, 
        "name" => $data->name, 
        "thumbnails" => $getThumbnails['data'][0]['imageUrl']];
}
echo json_encode(['data' => $arr]);
}else{
    die(http_response_code(403));
}