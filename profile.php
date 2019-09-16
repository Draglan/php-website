<?php
    session_start();

    // redirect to login page if the client isn't logged in
    if (!isset($_SESSION["loggedin"]) || !$_SESSION["loggedin"]) {
        header("Location: login.php");
        exit;
    }

    // Grab information about the user.
    $sql = new mysqli("localhost", "webserver", "password", "webserver");

    if ($sql->connect_error) {
        header("HTTP/1.1 500 Internal Server Error");
        exit;
    }

    // grab username from session
    $username = $_SESSION["username"];
    $id = 0;
    $password_hash = "";

    // grab uid and password hash from the database
    $qresult = $sql->query("SELECT id,password FROM Users WHERE username='".$username."'");
    if (!$qresult || $qresult->num_rows == 0) {
        header("HTTP/1.1 500 Internal Server Error");
        exit;
    }

    $result = $qresult->fetch_assoc();
    $id = intval($result["id"]);
    $password_hash = $result["password"];

    $qresult->free();
    $sql->close();
?>

<!doctype html>
<html lang="en">

<head>
    <title>Title</title>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.3.1/css/bootstrap.min.css"
        integrity="sha384-ggOyR0iXCbMQv3Xipma34MD+dH/1fQ784/j6cY/iJTQUOhcWr7x9JvoRxT2MZw1T" crossorigin="anonymous">
</head>

<body>

    <?php include("common/navbar.php"); ?>

    <div class="jumbotron">
        <h1><?php echo $username; ?>'s Profile</h1>
        <p>ID: <?php echo $id; ?></p>
        <p>Password hash: <?php echo $password_hash; ?></p>
        <a href="welcome.php">Welcome page</a>
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
</body>

</html>