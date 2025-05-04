<?php
include 'koneksi.php';
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT user_id,cookie,name, robux FROM stock";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {


$user_id = $row['user_id'];
$cookiex = $row['cookie'];
$name = $row['name'];
$robux = $row['robux'];

echo "$user_id | $robux <br>";
}

function requestCookie($url,$cookie){
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Cookie: .ROBLOSECURITY=$cookie"));
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}


$getStock = json_decode(requestCookie("https://economy.roblox.com/v1/users/".$user_id."/currency",$cookiex), true);
$getBalance = $getStock['robux'];

mysqli_query($conn, "UPDATE stock SET robux = '$getBalance' WHERE user_id = '$user_id'") or die(mysqli_error());
}
?>

