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
    <title>Login</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">

    </head>

<body>
     <div class="container my-5"> 
            <div class="card p-4 mx-auto shadow" style="max-width: 400px;">
    
            <div class="text-center mb-4">
                        <img src="../images/user.png" width="100" class="mb-3 mx-auto d-block"> 
                        <h1 class="h3 fw-normal">Please sign in</h1> 
            </div>

        <?php if ($error_message): ?>
                        <div class="alert alert-danger" role="alert"><?php echo $error_message; ?></div>
        <?php endif; ?>

                    <form action="login.php" method="post" id="login-form">

                    <div class="form-floating mb-3"> 
                    <input type="text" id="username" name="username" class="form-control" placeholder="Username" value="<?php echo htmlspecialchars($username); ?>" required>
                    <label for="username">Username</label>
                    <span id="username-error" class="error-message text-danger"></span> 
            </div>

                            <div class="form-floating mb-3"> 
                    <input type="password" id="password" name="password" class="form-control" placeholder="Password" required>
                    <label for="password">Password</label>
                    <span id="password-error" class="error-message text-danger"></span>
                </div>

                    <div class="d-flex justify-content-between">
                    <a href="register.php" class="w-100 me-2">
                            <button type="button" class="btn btn-secondary w-100">Register</button>
                    </a>
                                    <button type="submit" name="action" value="login" class="btn btn-primary w-100">Login</button>
                </div>
            </form>
        </div>
    </div>
    
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="../js/main.js"></script>
</body>
</html>