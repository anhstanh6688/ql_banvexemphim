<?php
$url = 'https://evgroup.vn/wp-content/uploads/2024/04/thiet_bi_rap_phim_06-1400x700.jpg';
$path = 'c:\xampp\htdocs\web-ql-ve-xem-phim\public\img\hero-new.jpg';

$options = [
    "http" => [
        "header" => "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/58.0.3029.110 Safari/537.3\r\n"
    ]
];

$context = stream_context_create($options);
$data = file_get_contents($url, false, $context);

if ($data === false) {
    echo "Download Failed.\n";
} else {
    file_put_contents($path, $data);
    echo "Downloaded " . strlen($data) . " bytes to $path\n";
    // Check if it's an image
    if (@imagecreatefromjpeg($path)) {
        echo "Valid JPEG.\n";
    } else {
        echo "Warning: File might not be a valid JPEG. First 20 chars: " . substr($data, 0, 20) . "\n";
    }
}
