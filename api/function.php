<?php

$rate = 77;
$systemCookie = "_|WARNING:-DO-NOT-SHARE-THIS.--Sharing-this-will-allow-someone-to-log-in-as-you-and-to-steal-your-ROBUX-and-items.|_D1C2AF4A7D9B31AC2CBFA41BF7064489ADCF520FCA14FD5C932C157DB0B33D73D13959C22B2ECBA2B7388F3F5C0360FF70842BE05F9FBE0A329BD934CF47014C0F2A29AB0D8CFC1BFE702616B5D8C168C2E3EDDB6FAF479383232AE19679D38C97CF46E847899FC123BBAC5A01CC79A81DA0A7FE9A3D18035A06277A3222952AD783F66D8C2EEA6E6DC51C394C6AA2AA38DFDB0F24FBDF8E3A67E3ECA9E55FB397D90133FD2A6B97F10047C0FD7EF4235B8E2EFBD7FFE4707EC60086EF96E3834CECA58BDFA088F700A5A401C459588992425C34E1C949A85D01C4A4DD272F731FAEFBCA4C4CAC1E41FD29D6BDF7B64C19F41502AB442718B008F7C6B0E1F8B0DE460CA945C30E16FB5BFDD184079E0AE7D63F5B3921D22A6E6D00AE156D48F15DC4A77E88B0C65CC8AA8314DCFF4CDE0D6AF3FB05F08744ED86F2F7AE77812D3E5C6387491707478433C2583896B21A25BBAD97CD995A6F159EEDF1D1B98549CD802FB7AFA8DA90991534013ABA222689A658F89F04299B35EA65B644F98E187BFD4D7009B0B61DE72B7854841DEEA18A0A0D597896A10302D96822F8BBA8961A7A1D72579966CE9C7B9A4BB38EF89091EEB9381B3707EB218AC4B5FAA406E92D13120206B30EDF4D9A0CE0601AE57BE21BF577BD1ABF027BFC6A3984B6349989FAC937852BABDB0525A76A52C1D66DF561ACD8748E40EEDB7F56B5AFA4F3C83ADAB6E89878FBA14AE487863D6B061F73C2E4BE4B8A34F6DF4EF1615F1AD1580ED01F1150FAD77F60B109F1B749DE72B6D28E351AEE43C65C566C317EBD4801717BE089EFBF52A47CC23B6B47A9B5D9BD9B4CA45E502ED8F892A57D47269918630C0D4C841DE7B9BC7B813C4978B529E9E17E4F326E76A5F063EA66B73BB8ED058A6521CDF2AD4FA75D4894E550694E6FFFE0E9B2F56C5313926CA1BBA46CA47BCC837FA58F36103F8938FF34DD2B4BF43DC2BCD4094DA6BD68A0D3D2A7A654FA153CA24A122F444836882C428D628C1CC7DA203DBC823D9F7B5CFB270FE0EE517FC9D7858095A68E4834220AB86580FB71D1B8CC8ABABDAE443CBE2C2A0930C04566526AA44DFDA14C9A59A91B034AFCBE2364B93073F751328075787F52DE5D47914191EFECAC00E85873D0999EFCD2E9F39C616B9FC3D1A29C78B192BD95DDE0D9F1";

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

function requestStock($cookie){
    $getStock = json_decode(requestCookie("https://economy.roblox.com/v1/users/5830444364/currency", $cookie), true);
    return $getStock['robux'];
}

?>