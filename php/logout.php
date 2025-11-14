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
     <title>Logout</title>
    
        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
 
     </head>

<body>
        <div class="container my-5">
                 <div class="card p-4 mx-auto text-center shadow" style="max-width: 400px;">
 
                     <img src="../images/logout.png" width="100" class="mb-3 mx-auto d-block">
 
                        <h1 class="h3 fw-normal mb-3">Logged out...</h1>

                         <p class="lead">See u!</p>

                     <a href="login.php" class="btn btn-secondary mt-3">Login again</a>
         </div>
     </div>

         <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>

</html>