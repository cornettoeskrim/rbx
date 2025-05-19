<?php
error_reporting(0);
include 'koneksi.php';

$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$sql = "SELECT * FROM `pembayaran` ORDER BY `pembayaran`.`jumlah_robux` DESC ";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $lastOrder = $row['set_harga'];
        $totalSold += $row['jumlah_robux'];
        $totalOrder = mysqli_num_rows($result);   
     }
}
$sql = "SELECT * FROM stock ";
$result = $conn->query($sql);

if ($result->num_rows > 0) {
    // output data of each row
    while($row = $result->fetch_assoc()) {
        $rate = $row['rate'];
        $systemCookie = $row['cookie'];
    }


function headersToArray( $str )
{
    $headers = array();
    $headersTmpArray = explode( "\r\n" , $str );
    for ( $i = 0 ; $i < count( $headersTmpArray ) ; ++$i )
    {
        // we dont care about the two \r\n lines at the end of the headers
        if ( strlen( $headersTmpArray[$i] ) > 0 )
        {
            // the headers start with HTTP status codes, which do not contain a colon so we can filter them out too
            if ( strpos( $headersTmpArray[$i] , ":" ) )
            {
                $headerName = substr( $headersTmpArray[$i] , 0 , strpos( $headersTmpArray[$i] , ":" ) );
                $headerValue = substr( $headersTmpArray[$i] , strpos( $headersTmpArray[$i] , ":" )+1 );
                $headers[$headerName] = $headerValue;
            }
        }
    }
    return $headers;
}

//CURL HANDLER
function requestCookie($url, $cookies)
{
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array("Cookie: .ROBLOSECURITY=$cookies"));
    $output = curl_exec($ch);
    curl_close($ch);
    return $output;
}

//GET XSTF TOKEN
function getxcsrfCookie($url, $cookie){
    $curl = curl_init();
    curl_setopt( $curl , CURLOPT_URL , $url );
    curl_setopt( $curl , CURLOPT_POST , true );
    curl_setopt( $curl , CURLOPT_FOLLOWLOCATION , 1 );
    curl_setopt( $curl , CURLOPT_RETURNTRANSFER , true );
    curl_setopt( $curl , CURLOPT_HEADER , true );
    curl_setopt( $curl , CURLOPT_HTTPHEADER, array(
    'content-type: application/json',
    'cookie: .ROBLOSECURITY=' . trim($cookie)
    ));
    $result = curl_exec( $curl );
    $headerSize = curl_getinfo( $curl , CURLINFO_HEADER_SIZE );
    $headerStr = substr( $result , 0 , $headerSize );
    $bodyStr = substr( $result , $headerSize );
    $headers = headersToArray( $headerStr );
    curl_close( $curl );
    return trim($headers['x-csrf-token']);
}

//PROSES TRANSAKSI
function requestPurchases($cookie, $productId, $price, $sellerId){
    $ch = curl_init();
    $post = [
                "expectedCurrency" => 1,
                "expectedPrice" => $price,
                "expectedSellerId" => $sellerId
            ];
    curl_setopt($ch, CURLOPT_URL, "https://economy.roblox.com/v1/purchases/products/$productId");
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($post));
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
        "accept-language: en-US,en;q=0.9",
        "content-type: application/json; charset=UTF-8",
        "origin: https://web.roblox.com",
        "referer: https://web.roblox.com/",
        "x-csrf-token: " . trim(getxcsrfCookie("https://economy.roblox.com/v1/purchases/products/$productId", $cookie)),
        "Cookie: .ROBLOSECURITY=$cookie"));
    $output = curl_exec($ch);
    $json = json_decode($output, true);
    curl_close($ch);
    if($json['purchased'] == true){
        $outputResult = true;
    }else{
        $outputResult = false;
    }
    return $outputResult;
}

//GET STOCK
function requestStock($cookie){
    $getId = json_decode(requestCookie("https://users.roblox.com/v1/users/authenticated", $cookie), true);
    $getStock = json_decode(requestCookie("https://economy.roblox.com/v1/users/".$getId['id']."/currency", $cookie), true);
    return $getStock['robux'];
}
}
?>