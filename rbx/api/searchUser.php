<?php
if ($_SERVER['REQUEST_METHOD'] === 'GET') {
    $keyword = $_GET['keyword'];
    // TODO: check keyword already exists in db, if already exsist return data directly
    // $user = user->FindByUsername($keyword)
    // echo json($user)
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
    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        $error = curl_error($ch);
        echo json_encode(['error' => 'terjadi kesalahan']);
        return;
    }

    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    if ($httpCode !== 200) {
        echo json_encode(['error' => 'terjadi kesalahan']);
        return;
    }
    $responseData = json_decode($response, true);
    if (count($responseData["data"]) == 0) {
        echo json_encode(['error' => 'user tidak ditemukan']);
        return;
    }
    curl_close($ch);
    $user = $responseData["data"][0];

    $thumbnails = json_decode(file_get_contents("https://thumbnails.roblox.com/v1/users/avatar-headshot?userIds=" . $user["id"] . "&size=75x75&format=Png&isCircular=false"), true);
    $imageUrl = "";
    if (count($thumbnails["data"])) {
        $imageUrl = $thumbnails['data'][0]['imageUrl'];
    }
    // TODO: store user value in db
    $arr[] = array(
        "UserId" => $user['id'],
        "name" => $user['name'],
        "DisplayName" => $user['displayName'],
        "Thumbnails" =>  $imageUrl,
    );

    echo json_encode(['Keyword' => $user['name'], 'data' => $arr]);
} else {
    die(http_response_code(403));
}
