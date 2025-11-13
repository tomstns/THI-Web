<?php
ob_start();


spl_autoload_register(function ($class) {
    $path = str_replace('\\', '/', $class) . '.php';
    
    $file = __DIR__ . '/' . $path;

    if (file_exists($file)) {
        require_once $file;
    }
});

session_start();

define('CHAT_SERVER_URL', 'https://online-lectures-cs.thi.de/chat/');
define('CHAT_SERVER_ID', '7e07164d-8724-4f62-89f7-9587383d66ad');

$service = new Utils\BackendService(CHAT_SERVER_URL, CHAT_SERVER_ID);
?>