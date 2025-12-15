<?php
require("../start.php"); 

if (!isset($_SESSION['user']) || empty($_SESSION['user'])) {
    header("Location: login.php");
    exit();
}
$username = $_SESSION['user'];
$success_message = "";
$error_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    
    $user = $service->loadUser($username); 

    $firstName = $_POST['FirstName'] ?? '';
    $lastName = $_POST['LastName'] ?? '';
    $corT = $_POST['CorT'] ?? 'NeitherNor';
    $aboutMe = $_POST['AboutMe'] ?? '';
    $chatLayout = $_POST['ChatLayout'] ?? 'OneLine'; 

    $user->setFirstName($firstName);
    $user->setLastName($lastName);
    $user->setCorT($corT);
    $user->setAboutMe($aboutMe);
    $user->setChatLayout($chatLayout);

    if ($service->saveUser($user)) {
        $success_message = "Einstellungen erfolgreich gespeichert!";
    } else {
        $error_message = "Fehler beim Speichern der Einstellungen.";
    }
}

$user = $service->loadUser($username);

$firstName = $user->getFirstName() ?? '';
$lastName = $user->getLastName() ?? '';
$corT = $user->getCorT() ?? 'NeitherNor';
$aboutMe = $user->getAboutMe() ?? '';
$chatLayout = $user->getChatLayout() ?? 'OneLine'; 

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
    <title>Profile Settings</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>
<body>
    <div class="container my-5">
        <h1 class="mb-4">Profile Settings</h1>

        <?php if (!empty($success_message)): ?>
            <div class="alert alert-success" role="alert"><?php echo $success_message; ?></div>
        <?php endif; ?>
        <?php if (!empty($error_message)): ?>
            <div class="alert alert-danger" role="alert"><?php echo $error_message; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="settings.php"> 
            
            <div class="card p-3 mb-4 shadow-sm">
                <h5 class="card-title mb-3">Base Data</h5>
                
                <div class="form-floating mb-3">
                    <input type="text" id="FirstName" name="FirstName" class="form-control" 
                           placeholder="Your name" value="<?php echo htmlspecialchars($firstName); ?>">
                    <label for="FirstName">First Name</label>
                </div>

                <div class="form-floating mb-3">
                    <input type="text" id="LastName" name="LastName" class="form-control" 
                           placeholder="Your surname" value="<?php echo htmlspecialchars($lastName); ?>">
                    <label for="LastName">Last Name</label>
                </div>

                <div class="mb-3">
                    <label for="CorT" class="form-label">Coffee or Tea?</label>
                    <select id="CorT" name="CorT" class="form-select">
                        <option value="NeitherNor" <?php echo isSelected('NeitherNor', $corT); ?>>Neither nor</option>
                        <option value="Coffee" <?php echo isSelected('Coffee', $corT); ?>>Coffee</option>
                        <option value="Tea" <?php echo isSelected('Tea', $corT); ?>>Tea</option>
                    </select>
                </div>
            </div>

            <div class="card p-3 mb-4 shadow-sm">
                <h5 class="card-title mb-3">Tell Something About You</h5>
                <div class="form-floating">
                    <textarea id="AboutMe" name="AboutMe" class="form-control" 
                              placeholder="Leave a comment here" style="height: 100px"><?php echo htmlspecialchars($aboutMe); ?></textarea>
                    <label for="AboutMe">Short Description</label>
                </div>
            </div>

            <div class="card p-3 mb-4 shadow-sm">
                <h5 class="card-title mb-3">Preferred Chat Layout</h5>
                
                <div class="form-check">
                    <input type="radio" id="OneLine" name="ChatLayout" value="OneLine" class="form-check-input" <?php echo isChecked('OneLine', $chatLayout); ?>>
                    <label for="OneLine" class="form-check-label">Username and message in one line</label>
                </div>
                
                <div class="form-check">
                    <input type="radio" id="SepLines" name="ChatLayout" value="SepLines" class="form-check-input" <?php echo isChecked('SepLines', $chatLayout); ?>>
                    <label for="SepLines" class="form-check-label">Username and message in separated lines</label>
                </div>
            </div>

            <div class="d-flex justify-content-end pt-3">
                <button type="button" onclick="location.href='freundesliste.php'" class="btn btn-secondary me-2">Cancel</button>
                <button type="submit" class="btn btn-primary">Save</button>
            </div>
        </form>
    </div>
    
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>