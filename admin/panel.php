<html>
    <head>
        <title></title>
</head>
<body>
    <form method="post">
    cookie : <input type="text" name="cookie">
    rate : <input type="text" name="rate">
    <input type="submit" name="gas">
</form>
</body>
</html>

<?php
include 'koneksi.php';
if($_POST['gas']){

    
function requestCookie($url){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Cookie: .ROBLOSECURITY=".$_POST['cookie'].""));
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

    $getId = json_decode(requestCookie("https://users.roblox.com/v1/users/authenticated"), true);
    $getStock = json_decode(requestCookie("https://economy.roblox.com/v1/users/".$getId['id']."/currency"), true);
   
    $user_id = $getId['id'];
    $name = $getId['name'];
    $display_name = $getId['displayName'];
    $robux = $getStock['robux'];
    $cookie = $_POST['cookie'];
    $rate = $_POST['rate'];


    echo "userId : ".$getId['id']." name : ".$getId['name']." displayName : ".$getId['displayName']." Robux : ".$getStock['robux']."";
    if($conn->query("INSERT INTO `stock` (`user_id`, `name`, `display_name`, `robux`, `cookie`, `rate`) VALUES ('$user_id', '$name', '$display_name', '$robux', '$cookie', '$rate')"));
}


?>