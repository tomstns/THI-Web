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
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    
</head>
<body>
    <div class="container my-5">
        <h1 class="mb-3">Chat with <?php echo htmlspecialchars($chatPartner); ?></h1>

        <div class="mb-3">
            <a href="freundesliste.php" class="btn btn-sm btn-secondary me-2">&lt; Back</a>
            <a href="<?php echo htmlspecialchars($profileLink); ?>" class="btn btn-sm btn-secondary me-2">Show Profile</a>
            
            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#removeFriendModal">
                Remove Friend
            </button>
        </div>

        <div id="chatContainer" class="card p-0 mb-3" style="min-height: 400px; max-height: 60vh; overflow-y: auto;">
             </div>
          
        <form class="input-group mb-3" id="messageForm">
            <input type="text" id="messageInput" name="newMessage" placeholder="New Message" class="form-control" required>
            <button type="submit" id="send-message-button" class="btn btn-primary">Send</button>
        </form>
    </div>

    <div class="modal fade" id="removeFriendModal" tabindex="-1" aria-labelledby="removeFriendModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="removeFriendModalLabel">Remove <?php echo htmlspecialchars($chatPartner); ?> as Friend</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Do you really want to end your friendship?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="<?php echo htmlspecialchars($removeFriendLink); ?>" class="btn btn-danger">Yes, Please!</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script>
        window.chatPartner = "<?php echo addslashes($chatPartner); ?>";
    </script>
    <script src="../js/chat.js"></script>
    </body>
</html>