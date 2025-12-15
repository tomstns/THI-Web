<?php
require("../start.php");

if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

$currentUser = $_SESSION['user'];
$profileUsername = $_GET['user'] ?? '';

if (empty($profileUsername)) {
    header("Location: freundesliste.php");
    exit();
}

$user = $service->loadUser($profileUsername);

if ($user === false) {
    header("Location: freundesliste.php?error=UserNotFound");
    exit();
}

$firstName = htmlspecialchars($user->getFirstName() ?? '');
$lastName = htmlspecialchars($user->getLastName() ?? '');
$fullName = trim($firstName . ' ' . $lastName);
if (empty($fullName)) {
    $fullName = $profileUsername;
}

$aboutMe = htmlspecialchars($user->getAboutMe() ?? 'Keine Profilbeschreibung vorhanden.');
$corT = htmlspecialchars($user->getCorT() ?? 'Nicht angegeben');

$status = "Offline";

$chatLink = "chat.php?friend=" . urlencode($profileUsername);
$removeFriendLink = "freundesliste.php?action=remove_friend&friendUsername=" . urlencode($profileUsername);

?>
<!DOCTYPE html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile of <?php echo htmlspecialchars($profileUsername); ?></title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container my-5">
        <h1 class="mb-4">Profile of <?php echo htmlspecialchars($profileUsername); ?></h1>

        <div class="mb-4">
            <a href="<?php echo htmlspecialchars($chatLink); ?>" class="btn btn-sm btn-secondary me-2">
                &lt; Back to Chat
            </a>
            <button type="button" class="btn btn-sm btn-danger" data-bs-toggle="modal" data-bs-target="#removeFriendModal">
                Remove Friend
            </button>
        </div>
        
        <div class="card p-4 shadow-sm">
            
            <div class="row g-4">
                
                <div class="col-md-3 text-center border-end">
                    <img src="../images/user.png" alt="Profile Picture" class="img-fluid rounded-circle mb-3 border border-3 border-secondary" style="width: 120px; height: 120px; object-fit: cover;">
                    <h2 class="h5 mb-0 text-muted"><?php echo htmlspecialchars($profileUsername); ?></h2>
                </div>

                <div class="col-md-9">
                    <h3 class="h5 border-bottom pb-2 mb-3">About Me</h3>
                    <p class="text-secondary"><?php echo nl2br($aboutMe); ?></p>
                </div>
            </div>
            
            <hr class="my-4">
            
            <div class="row">
                <div class="col-12">
                    <h3 class="h5 border-bottom pb-2 mb-3">Details</h3>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Name:</strong>
                            <span><?php echo $fullName; ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Coffee or Tea?</strong>
                            <span><?php echo $corT; ?></span>
                        </li>
                        <li class="list-group-item d-flex justify-content-between align-items-center">
                            <strong>Status:</strong>
                            <span class="badge <?php echo ($status == 'Online' ? 'bg-success' : 'bg-secondary'); ?>">
                                <?php echo $status; ?>
                            </span>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        
    </div>

    <div class="modal fade" id="removeFriendModal" tabindex="-1" aria-labelledby="removeFriendModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="removeFriendModalLabel">Remove <?php echo htmlspecialchars($profileUsername); ?> as Friend</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    Do you really want to end your friendship with <?php echo htmlspecialchars($profileUsername); ?>?
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <a href="<?php echo htmlspecialchars($removeFriendLink); ?>" class="btn btn-danger">Yes, Please!</a>
                </div>
            </div>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>