<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
$id = $_GET['id'];
echo file_get_contents("https://thumbnails.roblox.com/v1/users/avatar?userIds=$id&size=352x352&format=Png&isCircular=false");
}else{
    die(http_response_code(403));
}