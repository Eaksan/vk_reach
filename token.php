<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Token</title>
</head>
<body>
<?php
if(isset($_GET["code"]) == FALSE) {
?>

<a href="https://oauth.vk.com/authorize?client_id=5169706&display=page&redirect_uri=http://reach-groups-eaksan.c9users.io/token.php&response_type=code&v=5.52">code</a>

<br>
<?php
}
    $file = 'token.txt';
    if(isset($_GET["code"])) {
        $code = $_GET["code"];
        $url1 = file_get_contents("https://oauth.vk.com/access_token?client_id=5169706&client_secret=gyKyAkwHj12BhGpcQtl5&redirect_uri=http://reach-groups-eaksan.c9users.io/token.php&code=" . $code);
        $url1 = substr($url1, 17);
        $token = strstr($url1, '"', true);
        file_put_contents($file, $token);
        echo "Токен сохранен";
    }
?>
</body>
</html>

