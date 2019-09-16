<?php
    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $errorMsg = "";

        // initiate database connection
        $sql = new mysqli("localhost", "webserver", "password", "webserver");

        // return error if db connection fails or POST request
        // wasn't made correctly
        if ($sql->connect_error) {
            header("HTTP/1.1 500 Internal Server Error");
            exit;
        }

        if (!isset($_POST["username"]) || !isset($_POST["password"])) {
            header("HTTP/1.1 400 Bad Request");
            exit;
        }

        $username = $_POST["username"];
        $password = $_POST["password"];

        if (strlen($username) > 0) {
            // Step one: check that the username isn't already being used.
            $result = $sql->query("SELECT username FROM Users WHERE username='".$username."'");

            if ($result && $result->num_rows == 0) {
                // Username not taken. Step two: Check that the password length is >=8.
                if (strlen($password) >= 8) {
                    // Step three: Make the account.
                    $uid = random_int(0, pow(2, 31)-1);
                    $query_string = sprintf("INSERT INTO Users (id,username,password) VALUES (%d,\"%s\",\"%s\")", $uid, $username, password_hash($password, PASSWORD_BCRYPT));
                    if (!$sql->query($query_string)) {
                        header("HTTP/1.1 500 Internal Server Error");
                    }

                    // Redirect them to the login page.
                    header("Location: login.php");
                }
                else {
                    $errorMsg = "Password must be at least 8 characters long.";
                }
            }
            else {
                $errorMsg = "That username is already taken.";
            }
        }
        else {
            $errorMsg = "The username field was empty.";
        }

        $result->free();
        $sql->close();
    }
?>

<!doctype html>
<html lang="en">

<head>
    <title>Sign Up</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">

    <link rel="stylesheet" href="common/style.css">
</head>

<body>
    <?php include("common/navbar.php"); ?>

    <div class="container">
        <div style="text-align:center;">

            <div class="alert alert-warning" role="alert" style=<?php echo ($errorMsg=="" ? "display:none;" : "display:block;"); ?>>
                <strong><?php echo $errorMsg; ?></strong>
            </div>

            <form method="POST" class="user-form" id="signupForm">
                <h1>Sign Up for Nothing</h1>
                <div class="form-group">
                    <label for="usernameInput">Username</label>
                    <input class="form-control" type="username" name="username" id="usernameInput"
                        placeholder="Enter username" oninput="checkUsername()">
                </div>

                <div class="alert alert-danger" role="alert" id="usernameAlert" style="display:none;">
                    <strong></strong>
                </div>

                <div class="form-group">
                    <label for="passwordInput1">Password</label>
                    <input class="form-control" type="password" name="password" id="passwordInput1">
                    <small class="form-text text-muted">Be sure it's at least 8 characters long!</small>
                </div>

                <div class="form-group">
                    <label for="passwordInput2">Repeat Password</label>
                    <input class="form-control" type="password" name="passwordText2" id="passwordInput2">
                </div>

                <div class="alert alert-danger" style="display: none;" role="alert" id="passwordAlert">
                    <strong></strong>
                </div>

                <button type="submit" class="btn btn-primary">Sign Up</button>
            </form>
        </div>
    </div>

    <!-- Optional JavaScript -->
    <!-- jQuery first, then Popper.js, then Bootstrap JS -->
    <script src="https://code.jquery.com/jquery-3.3.1.slim.min.js"
        integrity="sha384-q8i/X+965DzO0rT7abK41JStQIAqVgRVzpbzo5smXKp4YfRvH+8abtTE1Pi6jizo" crossorigin="anonymous">
    </script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.14.7/umd/popper.min.js"
        integrity="sha384-UO2eT0CpHqdSJQ6hJty5KVphtPhzWj9WO1clHTMGa3JDZwrnQq4sF86dIHNDz0W1" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/js/bootstrap.min.js"
        integrity="sha384-JjSmVgyd0p3pXB1rRibZUAYoIIy6OrQ6VrjIEaFf/nJGzIxFDsf4x0xIM+B07jRM" crossorigin="anonymous">
    </script>

    <script>
        document.getElementById("signupForm").onsubmit = function () {
            return validateForm();
        }
        var validUsername = false;

        function validateForm() {
            var username = document.getElementById("usernameInput").value;
            var pwd1 = document.getElementById("passwordInput1").value;
            var pwd2 = document.getElementById("passwordInput2").value;

            var usrAlert = document.getElementById("usernameAlert");
            var pwdAlert = document.getElementById("passwordAlert");

            var success = true;

            if (!username) {
                usrAlert.style.display = "block";
                usrAlert.innerText = "Please enter a username.";
                success = false;
            }

            if (pwd1 !== pwd2) {
                pwdAlert.style.display = "block";
                pwdAlert.innerText = "Passwords must match.";
                success = false;
            } else if (pwd1 == pwd2 && pwd1.length < 8) {
                pwdAlert.style.display = "block";
                pwdAlert.innerText = "Password must be at least 8 characters long.";
                success = false;
            }

            return success && validUsername;
        }

        function checkUsername() {
            var username = document.getElementById("usernameInput").value;
            var xmlhttp = new XMLHttpRequest();

            xmlhttp.onreadystatechange = function () {
                var usrAlert = document.getElementById("usernameAlert");
                if (this.readyState == 4 && this.status == 200) {
                    if (this.responseText == "BAD") {
                        usrAlert.style.display = "block";
                        usrAlert.innerText = "That username is already taken.";
                        validUsername = false;
                    } else {
                        usrAlert.style.display = "none";
                        validUsername = true;
                    }
                }
            }

            xmlhttp.open("GET", "checkusername.php?username=" + username);
            xmlhttp.send();
        }
    </script>
</body>

</html>