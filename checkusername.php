<?php 
    if ($_SERVER["REQUEST_METHOD"] == "GET") {
        $username = $_GET["username"];

        $sql = new mysqli("localhost", "webserver", "password", "webserver");

        if ($sql->connect_error) {
            header("HTTP/1.1 500 Internal Server Error");
            exit;
        }

        $qresult = $sql->query("SELECT username FROM Users WHERE username='".$username."'");

        if (!$qresult || $qresult->num_rows == 0) {
            echo "GOOD";
        }
        else {
            echo "BAD";
        }

        $qresult->free();
        $sql->close();
    }
?>