<?php
require "../start.php";

if (!isset($_SESSION['user'])) {
    http_response_code(401); 
    return;
}

if (!isset($_GET['to'])) {
    http_response_code(400); 
    return;
}

$to = $_GET['to'];
$messages = $service->loadMessages($to);

if (!$messages) {
    $messages = array();
}
echo json_encode($messages);
http_response_code(200);
?>
