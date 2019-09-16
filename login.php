<?php
    session_start();
    
    // Redirect to welcome page if already logged in.
    if (isset($_SESSION["loggedin"]) && $_SESSION["loggedin"]) {
        header("Location: welcome.php");
        exit;
    }

    // Process the form if it's been submitted.
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        // Connect to the database.
        $sql = new mysqli("localhost", "webserver", "password", "webserver");

        if ($sql->connect_error) {
            echo "Error connecting to DB: " . $sql->connect_error . PHP_EOL;
            exit;
        }

        // Get the username and password given by the user.
        $username = $_POST["username"];
        $password = $_POST["password"];

        // Query the DB for the password of the given user.
        $query_result = $sql->query("SELECT password FROM Users WHERE username='".$username."'");

        // If the user exists, check the given password against the actual password.
        if ($query_result && $query_result->num_rows > 0) {
            $target_password = $query_result->fetch_assoc()["password"];

            if (password_verify($password, $target_password)) {
                // If the passwords match, redirect the user to the welcome page.
                header("Location: welcome.php");
                session_start();
                $_SESSION["loggedin"] = true;
                $_SESSION["username"] = $username;
            }
            else {
                // Otherwise, show an error message.
                echo "Nope, wrong password";
            }
        }
        else {
            // If the user doesn't exist, say as much.
            echo "The specified username doesn't exist.";
        }

        // Clean up the database structures used by PHP.
        $query_result->free();
        $sql->close();
    }
?>

<!DOCTYPE html>

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Login</title>
    <meta name="description" content="">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
    <link rel="stylesheet" href="common/style.css">
</head>

<body>
    <?php include("common/navbar.php"); ?>

    <div class="container">
        <div style="text-align:center;">
            <form method="POST" class="user-form">
                <h1>Login to Nothing</h1>
                <br />
                <div class="form-group">
                    <label for="usernameInput">Username</label>
                    <input type="username" class="form-control" id="usernameInput" name="username" placeholder="Enter username">
                </div>

                <div class="form-group">
                    <label for="passwordInput">Password</label>
                    <input type="password" class="form-control" name="password" id="passwordInput">
                </div>

                <button type="submit" class="btn btn-primary">Submit</button>
            </form>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>
</body>

</html>