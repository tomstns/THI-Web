<?php
require("../start.php");

if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$chatPartner = $_GET['friend'] ?? '';

if (empty($chatPartner)) {
    header("Location: freundesliste.php");
    exit();
}

$removeFriendLink = "freundesliste.php?action=remove_friend&friendUsername=" . urlencode($chatPartner);
$profileLink = "profile.php?user=" . urlencode($chatPartner);

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Chat with <?php echo htmlspecialchars($chatPartner); ?></title>
    
    <link rel="stylesheet" href="../CSS/stylesheet.css">
    
    <style>
        .message-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .message-container p {
            margin: 2px 0;
        }
        .message-timestamp {
            font-size: 0.8em;
            color: #777;
            white-space: nowrap;
            margin-left: 15px;
        }
    </style>
</head>
<body>

    <h1 id="chat-title">Chat with <?php echo htmlspecialchars($chatPartner); ?></h1>

    <p>
        &lt; <a href="freundesliste.php">Back</a> | 
        <a href="<?php echo htmlspecialchars($profileLink); ?>">Profile</a> |
        <a href="<?php echo htmlspecialchars($removeFriendLink); ?>" class="special-link">Remove Friend</a>
    </p>

    <hr>
    
    <fieldset id="chatContainer">
        </fieldset>

    <hr>
    
    <form class="form-row chat-input" id="messageForm">
        <input type="text" id="messageInput" name="newMessage" placeholder="New Message">
        <button type="submit" id="send-message-button">Send</button>
</form>

    <script>
        window.chatPartner = "<?php echo addslashes($chatPartner); ?>";
    </script>
    <script src="../js/chat.js"></script>
    <script src="../js/main.js"></script>
</body>
</html>