<?php
require("../start.php"); 

if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}

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

$chatLink = "chat.php?friend=" . urlencode($profileUsername);
$removeFriendLink = "freundesliste.php?action=remove_friend&friendUsername=" . urlencode($profileUsername);

$username = htmlspecialchars($user->getUsername() ?? 'Unbekannt');
$aboutMe = htmlspecialchars(@$user->getAboutMe() ?? 'Keine Profilbeschreibung vorhanden.');
$corT = htmlspecialchars(@$user->getCorT() ?? 'Nicht angegeben');

$firstName = htmlspecialchars(@$user->getFirstName() ?? '');
$lastName = htmlspecialchars(@$user->getLastName() ?? '');
$fullName = trim($firstName . ' ' . $lastName);

if (empty($fullName)) {
    $fullName = $username;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/stylesheet.css">
    <title>Profile of <?php echo $username; ?></title>
</head>
<body>
    <h1>Profile of <?php echo $username; ?></h1>
    
    <a href="<?php echo $chatLink; ?>">&lt; Back to Chat</a> | 
    <a href="<?php echo $removeFriendLink; ?>" class="special-link">Remove Friend</a>
    
    <div class="align-left">
        <p></p>
        <img src="../images/profile.png" alt="Profile Picture" width="150" height="150" class="profile-picture"><br>
        <fieldset>
            <?php echo nl2br($aboutMe); 
            <p></p>
            
            <div class="bold">
                Coffee or Tea? <br>
            </div>
            &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $corT; ?><br>

            <div class="bold">
                Name <br>
            </div>
            &nbsp;&nbsp;&nbsp;&nbsp;<?php echo $fullName; ?>
        </fieldset>
    </div>
</body>
</html>