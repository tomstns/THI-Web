<?php
require("../start.php"); 

if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
$username = $_SESSION['user']; 

$error_message = "";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    
    try {
        if ($_POST['action'] == 'accept_friend' || $_POST['action'] == 'reject_friend') {
            $targetUsername = $_POST['friendUsername'] ?? ''; 
            
            if ($targetUsername) {
                if ($_POST['action'] == 'accept_friend') {
                    if ($service->friendAccept($targetUsername)) { 
                        $success_message = htmlspecialchars($targetUsername) . " als Freund angenommen.";
                    } else {
                        $error_message = "Fehler beim Annehmen der Anfrage.";
                    }
                } elseif ($_POST['action'] == 'reject_friend') {
                    if ($service->friendDismiss($targetUsername)) { 
                        $success_message = htmlspecialchars($targetUsername) . "'s Anfrage abgelehnt.";
                    } else {
                        $error_message = "Fehler beim Ablehnen der Anfrage.";
                    }
                }
            }
        
        } elseif ($_POST['action'] == 'add_friend') {
            
            $targetUsername = $_POST['friendRequestName'] ?? '';
            
            if ($targetUsername && $targetUsername !== 'none') {
                if ($service->friendRequest(["username" => $targetUsername])) {
                    $success_message = "Freundschaftsanfrage an " . htmlspecialchars($targetUsername) . " gesendet.";
                } else {
                    $error_message = "Fehler beim Senden der Anfrage.";
                }
            } else {
                $error_message = "Bitte wähle einen Benutzer aus der Liste aus.";
            }
        }
        
    } catch (Exception $e) {
        $error_message = "Aktionsfehler: " . $e->getMessage();
        error_log("Friendlist POST error: " . $e->getMessage());
    }
}

if ($_SERVER["REQUEST_METHOD"] == "GET" && isset($_GET['action']) && $_GET['action'] == 'remove_friend') {
    $targetUsername = $_GET['friendUsername'] ?? '';
    if ($targetUsername) {
        try {
            if ($service->removeFriend($targetUsername)) {
                $success_message = htmlspecialchars($targetUsername) . " wurde als Freund entfernt.";
            } else {
                $error_message = "Fehler beim Entfernen des Freundes.";
            }
        } catch (Exception $e) {
            $error_message = "Entfernungsfehler: " . $e->getMessage();
            error_log("Friendlist REMOVE error: " . $e->getMessage());
        }
    }
}

$allUsers = $service->loadUsers();
if ($allUsers === false) $allUsers = [];

$friends = $service->loadFriends();
if ($friends === false) $friends = [];

$friendNames = array_map(function($friend) {
    return $friend->getUsername();
}, $friends);

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Freundesliste</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="container my-5">
        <h1 class="mb-3">Friends of <?php echo htmlspecialchars($username); ?></h1>
    
        <div class="mb-3">
            <a href="logout.php" class="btn btn-sm btn-secondary me-2"> &lt; Logout </a>
            <a href="settings.php" class="btn btn-sm btn-secondary">Settings</a>
        </div>
        <hr>
    
        <?php if ($success_message): ?>
            <div class="alert alert-success" role="alert"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <?php if ($error_message): ?>
            <div class="alert alert-danger" role="alert"><?php echo $error_message; ?></div>
        <?php endif; ?>
    
        <h2 class="mt-4">Friends</h2>
        <ul class="list-group" id="friend-list">
        </ul>
    
        <h2 class="mt-4">New Requests</h2>
        <ul class="list-group" id="request-list">
        </ul>
    
        <h2 class="mt-4">Add Friend to List</h2>
        <form id="add-friend-form" method="POST" action="freundesliste.php">
            <div class="input-group mb-3"> 
                <select name="friendRequestName" id="friend-request-name" class="form-select"> 
                    <option value="none">Bitte einen Benutzer auswählen...</option>
                    <?php foreach ($allUsers as $user): ?>
                        <?php 
                        if ($user !== $username && !in_array($user, $friendNames)): 
                        ?>
                            <option value="<?php echo htmlspecialchars($user); ?>">
                                <?php echo htmlspecialchars($user); ?>
                            </option>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </select>
                <button type="submit" name="action" value="add_friend" id="add-friend-button" class="btn btn-primary">Add</button>
            </div>
        </form>

        <div class="modal fade" id="requestModal" tabindex="-1" aria-labelledby="requestModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="requestModalLabel">Request from <span id="modal-friend-name"></span></h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        Accept request?
                    </div>
                    <div class="modal-footer">
                        <form method="post" action="freundesliste.php" id="modal-request-form" class="d-flex">
                            <input type="hidden" name="friendUsername" id="modal-friend-input" value="">
                            <button type="button" class="btn btn-secondary me-2" data-bs-dismiss="modal">Close</button>
                            <button type="submit" name="action" value="reject_friend" class="btn btn-secondary me-2">Dismiss</button>
                            <button type="submit" name="action" value="accept_friend" class="btn btn-primary">Accept!</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        window.currentUser = <?php echo json_encode($username); ?>;
    </script>
    <script src="../js/main.js"></script>
    <script src="../js/friends.js"></script>
</body>
</html>