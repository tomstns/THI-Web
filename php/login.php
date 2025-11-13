<?php
ob_start();
require("../start.php");

$error_message = "";
$username = ""; 

if (isset($_SESSION['user']) && !empty($_SESSION['user'])) {
    header("Location: freundesliste.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST['action']) && $_POST['action'] == 'login') {
    $username = trim($_POST['username'] ?? '');
    $password = $_POST['password'] ?? '';
    
    if (empty($username) || empty($password)) {
        $error_message = "Bitte geben Sie Benutzername und Passwort ein.";
    } else {
        try {
            if ($service->login($username, $password)) {
                $_SESSION['user'] = $username;
                header("Location: freundesliste.php");
                exit();
            } else {
                $error_message = "Anmeldung fehlgeschlagen. Überprüfen Sie Benutzername und Passwort.";
            }
        } catch (Exception $e) {
            $error_message = "Fehler bei der Anmeldung: " . $e->getMessage();
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
    <title>Login</title>
</head>

<body>
    <img src="../images/user.png" width="100">
    <div class="center">
        <h1>Login</h1>
    </div>
    
    <p style="color: red;"><?php echo $error_message; ?></p>

    <form action="login.php" method="post" id="login-form">
        <fieldset>
            <legend>Login</legend>

            <div class="form-group">
                <label for="username">Username</label>
                <div class="input-wrapper">
                    <input type="text" id="username" name="username" placeholder="Username" class="coalinged" value="<?php echo htmlspecialchars($username); ?>" required>
                    <span id="username-error" class="error-message"></span>
                </div>
            </div>

            <div class="form-group">
                <label for="password">Password</label>
                <div class="input-wrapper">
                    <input type="password" id="password" name="password" placeholder="Password" class="coalinged" required>
                    <span id="password-error" class="error-message"></span>
                </div>
            </div>

            </fieldset>
        <a href="register.php">
            <button type="button">Register</button>
        </a>
        <button type="submit" name="action" value="login">Login</button>
    </form>

    <script src="../js/main.js"></script>
    </body>
</html>