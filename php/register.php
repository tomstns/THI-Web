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
    <title>Register</title>
    
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
</head>

<body>
    <div class="container my-5">
        <div class="card p-4 mx-auto shadow" style="max-width: 400px;">
            
            <div class="text-center mb-4">
                <img src="../images/user.png" width="100" class="mb-3 mx-auto d-block">
                <h1 class="h3 fw-normal">Register yourself</h1>
            </div>
            
            <?php if ($error_message): ?>
                <div class="alert alert-danger" role="alert"><?php echo $error_message; ?></div>
            <?php endif; ?>

            <form action="register.php" method="post" id="register-form" novalidate>
                
                <div class="form-floating mb-3">
                    <input type="text" id="username" name="username" placeholder="Username" class="form-control" value="<?php echo htmlspecialchars($username); ?>" required>
                    <label for="username">Username</label>
                    <div class="invalid-feedback" id="username-error"></div>
                </div>

                <div class="form-floating mb-3">
                    <input type="password" id="password" name="password" placeholder="Password" class="form-control" required>
                    <label for="password">Password</label>
                    <div class="invalid-feedback" id="password-error"></div>
                </div>

                <div class="form-floating mb-3">
                    <input type="password" id="confirm-password" name="confirm-password" placeholder="Confirm Password" class="form-control" required>
                    <label for="confirm-password">Confirm Password</label>
                    <div class="invalid-feedback" id="confirm-password-error"></div>
                </div>

                <div class="d-flex justify-content-between mt-4">
                    <a href="login.php" class="w-100 me-2">
                        <button type="button" class="btn btn-secondary w-100">Cancel</button>
                    </a>
                    <button type="submit" name="action" value="register" class="btn btn-primary w-100">Create Account</button>
                </div>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
    <script src="../js/main.js"></script>
    <script src="../js/register.js"></script>
</body>
</html>