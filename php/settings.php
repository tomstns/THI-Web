<?php
require("../start.php"); 

// 1. Session-Prüfung: Ist jemand eingeloggt?
if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
$username = $_SESSION['user'];
$success_message = "";

// 2. SPEICHERN: Prüfen, ob das Formular per POST gesendet wurde
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    // Aktuelles Benutzerobjekt laden, um es zu aktualisieren
    $user = $service->loadUser($username); 

    // Daten aus dem Formular holen
    $firstName = $_POST['FirstName'] ?? '';
    $lastName = $_POST['LastName'] ?? '';
    $corT = $_POST['CorT'] ?? 'NeitherNor';
    $aboutMe = $_POST['AboutMe'] ?? '';
    $chatLayout = $_POST['ChatLayout'] ?? ''; // Wert von den Radio-Buttons

    // Daten im User-Objekt setzen (mit den neuen Settern)
    $user->setFirstName($firstName);
    $user->setLastName($lastName);
    $user->setCorT($corT);
    $user->setAboutMe($aboutMe);
    $user->setChatLayout($chatLayout);

    // Aktualisiertes User-Objekt im Backend speichern
    if ($service->saveUser($user)) {
        $success_message = "Einstellungen erfolgreich gespeichert!";
    } else {
        $error_message = "Fehler beim Speichern der Einstellungen.";
    }
}

// 3. LADEN: Benutzerdaten (neu) laden, um das Formular vorab auszufüllen
// (Dies geschieht nach dem Speichern, um die neuesten Daten anzuzeigen)
$user = $service->loadUser($username);

// Daten in Variablen für das HTML-Formular speichern
$firstName = $user->getFirstName() ?? '';
$lastName = $user->getLastName() ?? '';
$corT = $user->getCorT() ?? 'NeitherNor';
$aboutMe = $user->getAboutMe() ?? '';
$chatLayout = $user->getChatLayout() ?? 'OneLine'; // Standardwert

// Hilfsfunktionen, um 'selected' oder 'checked' zu setzen
function isSelected($optionValue, $currentValue) {
    return ($optionValue === $currentValue) ? 'selected' : '';
}
function isChecked($optionValue, $currentValue) {
    return ($optionValue === $currentValue) ? 'checked' : '';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/stylesheet.css">
    <title>Profile Settings</title>
</head>
<body>
    <h1>Profile Settings</h1>

    <?php if (!empty($success_message)): ?>
        <p style="color: green; font-weight: bold;"><?php echo $success_message; ?></p>
    <?php endif; ?>
    
    <form method="POST" action="settings.php"> 
        <fieldset title="BaseData">
            <legend>Base Data</legend>
            
            <div class="form-group">
                <label for="FirstName">First Name</label>
                <div class="input-wrapper">
                    <input type="text" id="FirstName" name="FirstName" class="coalinged" 
                           placeholder="Your name" value="<?php echo htmlspecialchars($firstName); ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="LastName">Last Name</label>
                <div class="input-wrapper">
                    <input type="text" id="LastName" name="LastName" class="coalinged" 
                           placeholder="Your surname" value="<?php echo htmlspecialchars($lastName); ?>">
                </div>
            </div>

            <div class="form-group">
                <label for="CorT">Coffee or Tea?</label>
                <div class="input-wrapper">
                    <select id="CorT" name="CorT" class="coalinged">
                        <option value="NeitherNor" <?php echo isSelected('NeitherNor', $corT); ?>>Neither Nor</option>
                        <option value="Coffee" <?php echo isSelected('Coffee', $corT); ?>>Coffee</option>
                        <option value="Tea" <?php echo isSelected('Tea', $corT); ?>>Tea</option>
                    </select>
                </div>
            </div>
        </fieldset>

        <fieldset title="AboutYou">
            <legend>Tell Something About You</legend>
            <textarea id="AboutMe" name="AboutMe" rows="4" cols="50" 
                      placeholder="Leave a comment here"><?php echo htmlspecialchars($aboutMe); ?></textarea>
        </fieldset>

        <fieldset title="PreferredChatLayout">
            <legend>Preferred Chat Layout</legend>
            <p></p>
            <input type="radio" id="OneLine" name="ChatLayout" value="OneLine" <?php echo isChecked('OneLine', $chatLayout); ?>>
            <label for="OneLine">Username and message in one line</label>
            <p></p>
            <input type="radio" id="SepLines" name="ChatLayout" value="SepLines" <?php echo isChecked('SepLines', $chatLayout); ?>>
            <label for="SepLines">Username and message in separate lines</label>
            <p></p>
        </fieldset>

        <button type="button" onclick="location.href='freundesliste.php'">Cancel</button>
        <button type="submit">Save</button>
    </form>
</body>
</html>