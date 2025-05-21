<?php
error_reporting(0);
include 'koneksi.php';

if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $keyword = $_GET['keyword'];
    $searchUsernameUrl = 'https://users.roblox.com/v1/usernames/users';
    $searchUsernameBody = [
        'usernames' => [$keyword],
        'excludeBannedUsers' => true
    ];
    $ch  = curl_init($searchUsernameUrl);
    curl_setopt($ch, CURLOPT_POST, 1);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($searchUsernameBody));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:77.0) Gecko/20100101 Firefox/77.0");
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json'
    ]);
    $result = curl_exec($ch);
    curl_close($ch);
    $responseData = json_decode($result, true);
    $user_id =  $responseData["data"][0]['id'];
    $name =  $responseData["data"][0]['name'];
}

$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM `pembayaran` WHERE user_id = '$user_id' ";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $arr[] = array(
            "UserId" => $row['user_id'],
            "name" => $name,
            "orderId" => $row['order_id'],
            "jumlahRobux" => $row['jumlah_robux'],            
            "status" => $row['status'],
            "createdAt" => $row['created_at'],
        );
    }
    echo json_encode(['Keyword' => $name, 'data' => $arr]);
}else{
    die(http_response_code(404));
}

