<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Организиране на събития</title>
        <link type="text/css" rel="stylesheet" href="/css/bootstrap/css/bootstrap.min.css">
        <link type="text/css" rel="stylesheet" href="/css/style.css">
    </head>
    <body>
        <header id="header">
            <h1>Система за организиране на събития</h1>
            <?php
                if (!SessionManager::is_logged_in()) {
                    include("navs/guest_nav.php");
                } else {
                    include("navs/logged_nav.php");
                }
            ?>
        </header>