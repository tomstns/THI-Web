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
    
    $unreadRaw = $service->getUnread();
    $unreadData = (array)$unreadRaw;

    if ($friends === false) {
        http_response_code(500);
        echo json_encode(['error' => 'Freunde konnten nicht vom Service geladen werden.']);
    } else {
        $combinedList = array();

        foreach ($friends as $friend) {
            $friendData = $friend->jsonSerialize(); 
            $friendName = $friend->getUsername();
            
            $unreadCount = 0;

            if (isset($unreadData[$friendName])) {
                $unreadCount = intval($unreadData[$friendName]);
            }

            if ($unreadCount == 0 && ($friendName == 'Tom' || $friendName == 'Jerry')) {
            }

            $friendData['unread'] = $unreadCount;
            $combinedList[] = $friendData;
        }

        usort($combinedList, function($a, $b) {
            if ($a['unread'] != $b['unread']) {
                return $b['unread'] - $a['unread'];
            }
            return strcasecmp($a['username'], $b['username']);
        });

        http_response_code(200);
        echo json_encode($combinedList);
    }

} catch (Exception $e) {
    http_response_code(500);
    echo json_encode(['error' => $e->getMessage()]);
}
?>