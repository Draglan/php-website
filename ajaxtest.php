<?php 
    if (isset($_GET["value"])) {
        echo $_GET["value"];
    }

    if (isset($_POST["value"])) {
        echo json_encode(["value" => $_POST["value"]]);
    }
?>