<?php

require "../start.php"; 

header('Content-Type: application/json');

if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    http_response_code(401); 
    echo json_encode(['error' => 'Nicht angemeldet']);
    exit();
}

try {
    $friends = $service->loadFriends();

    if ($friends === false) {
        http_response_code(500);
        echo json_encode(['error' => 'Freunde konnten nicht vom Service geladen werden.']);
    } else {
        http_response_code(200);
        echo json_encode($friends);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>