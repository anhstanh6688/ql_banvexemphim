<?php
$url = 'https://evgroup.vn/wp-content/uploads/2024/04/thiet_bi_rap_phim_06-1400x700.jpg';
$path = 'c:\xampp\htdocs\web-ql-ve-xem-phim\public\img\hero-new.jpg';
file_put_contents($path, file_get_contents($url));
echo "Downloaded to $path";
