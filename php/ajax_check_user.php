<?php
require "../start.php";

if (!isset($_GET['username'])) {
    http_response_code(400); 
    return;
}

$exists = $service->userExists($_GET['username']);
http_response_code($exists ? 204 : 404);
?>
