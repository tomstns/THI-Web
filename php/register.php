<?php ob_start();
require("../start.php");

$error_message = "";
$username = ""; 


if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
    header("Location: freundesliste.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'register') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    $confirmPassword = $_POST['confirm-password'] ?? '';

    $validation_successful = true;

    if (empty($username) || strlen($username) < 3) {
        $error_message = "Nutzername ist ungültig (min. 3 Zeichen).";
        $validation_successful = false;
    }
    
    if ($validation_successful && (empty($password) || strlen($password) < 8)) {
        $error_message = "Passwort muss min. 8 Zeichen haben.";
        $validation_successful = false;
    }
    
    if ($validation_successful && $password !== $confirmPassword) {
        $error_message = "Passwörter stimmen nicht überein.";
        $validation_successful = false;
    } 

    if ($validation_successful) {
        try {
            if ($service->register($username, $password)) {
                $_SESSION['user'] = $username;
                header("Location: freundesliste.php");
                exit();
            } else {
                $error_message = "Registrierung fehlgeschlagen. Möglicherweise ist der Nutzername bereits vergeben.";
            }
        } catch (Exception $e) {
            $error_message = "Fehler bei der Registrierung: " . $e->getMessage();
            error_log($e->getMessage());
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
    <title>Register</title>
</head>

<body>
    <img src="../images/user.png" width="100">
    <div class="center">
        <h1>Register yourself</h1>
    </div>
    
    <p style="color: red;"><?php echo $error_message; ?></p>

   <form action="register.php" method="post" id="register-form">
        <fieldset>
            <legend>Register</legend>

            <div class="form-group">
                <label for="username">Username</label>
                <div class="input-wrapper">
                    <input type="text" id="username" name="username" placeholder="Username" class="coalinged" value="<?php echo htmlspecialchars($username); ?>">
                    <span id="username-error" class="error-message"></span>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <input type="password" id="password" name="password" placeholder="Password" class="coalinged">
                    <span id="password-error" class="error-message"></span>
                </div>
            </div>

            <div class="form-group">
                <label for="confirm-password">Confirm Password</label>
                <div class="input-wrapper">
                    <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm Password" class="coalinged">
                    <span id="confirm-password-error" class="error-message"></span>
                </div>
            </div>

        </fieldset>
        <a href="login.php">
            <button type="button">Cancel</button>
        </a>
        <button type="submit" name="action" value="register">Create Account</button>
    </form>

    <script src="../js/main.js"></script>
    <script src="../js/register.js"></script>
</body>
</html>