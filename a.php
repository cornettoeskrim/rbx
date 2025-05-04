<?php

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

function requestStock($cookie){
    $getStock = json_decode(requestCookie("https://economy.roblox.com/v1/users/5830444364/currency", $cookie), true);
    return $getStock['robux'];
}

echo requestStock("");