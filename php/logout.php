<?php
require("../start.php");

session_unset();
session_destroy();

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="../CSS/stylesheet.css">
    <title>Logout</title>
</head>

<body>
    <img src="../images/logout.png" width="100">
    <div class="center">
        <h1>Logged out...</h1>
        <b>See u!</b>
        <br><br>
        <a href="login.php">Login again</a>
    </div>
</body>

</html>