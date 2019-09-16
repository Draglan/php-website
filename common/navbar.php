<!-- REQUIRES BOOTSTRAP -->
<?php

session_start();
$loggedin = isset($_SESSION["loggedin"]) && $_SESSION["loggedin"];


echo '
<nav class="navbar navbar-expand-md navbar-dark bg-dark sticky-top">
    <a class="navbar-brand" style="color:white;">PHP Stuff</a>

    <ul class="navbar-nav">
        <li class="nav-item">
            <a class="nav-link" href="index.php">Home</a>
        </li>
    </ul>
';

if ($loggedin) {
    echo '
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link" href="profile.php">'.$_SESSION["username"].'\'s Account</a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="logout.php">Logout</a>
        </li>
    </ul>
    ';
}
else {
    echo '
    <ul class="navbar-nav ml-auto">
        <li class="nav-item">
            <a class="nav-link" href="login.php">Log In</a>
        </li>

        <li class="nav-item">
            <a class="nav-link" href="signup.php">Sign up</a>
        </li>
    </ul>
    ';
}

echo '</nav>';
?>