<?php
require "../start.php";

if (!isset($_SESSION['user'])) {
    http_response_code(401); 
    return;
}

$messageBody = file_get_contents('php://input');
$message = json_decode($messageBody);
$ok = $service->sendMessage($message);
http_response_code($ok ? 204 : 404);
?>