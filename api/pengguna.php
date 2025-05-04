<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
$username = $_GET['nama'];
echo file_get_contents("https://users.roblox.com/v1/users/search?keyword=$username");
}else{
    die(http_response_code(403));
}
?>