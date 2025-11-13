<?php
// freundesliste.php
require("../start.php"); 

if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
$username = $_SESSION['user'];

$error_message = "";
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action'])) {
    $targetUsername = $_POST['friendUsername'] ?? ''; 

    try {
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
?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/stylesheet.css">
    <title>Freundesliste</title>
</head>

<body>
    <h1>Friends of <?php echo htmlspecialchars($username); ?></h1>
    
    <a href="logout.php"> &lt; Logout </a>
    <b> | </b>
    <a href="settings.php">Settings</a>
    <hr>
    
    <?php if ($success_message): ?>
        <p style="color: green; font-weight: bold;"><?php echo $success_message; ?></p>
    <?php endif; ?>
    <?php if ($error_message): ?>
        <p style="color: red; font-weight: bold;"><?php echo $error_message; ?></p>
    <?php endif; ?>
    
    <ul id="friend-list">
        </ul>
    
    <hr>
    <h3>New Requests</h3>

    <ol id="request-list">
        </ol>
    
    <hr>

    <h3>Add Friend to List</h3>
    <form id="add-friend-form">
        <div class="form-row chat-input">
            <input type="text" 
                    id="friend-request-name" 
                    name="friendRequestName" 
                    placeholder="Username" 
                    list="friend-selector">
            
            <button type="button" id="add-friend-button">Add</button>
        </div>
        
        <datalist id="friend-selector">
            </datalist>
    </form>
    
    <script src="../js/main.js"></script>
    <script src="../js/friends.js"></script>
</body>

</html>