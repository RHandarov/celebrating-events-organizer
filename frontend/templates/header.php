<!DOCTYPE HTML>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Организиране на събития</title>
          <style type="text/css">
        <?php 
        include("frontend/css/style.css");
        ?>
        </style>
    </head>
    <body>
        <header id="header">
            <h1><a href="<?php echo Router::get_url(); ?>">Система за организиране на събития</a></h1>
            <?php
                if (!SessionManager::is_logged_in()) {
                    include("navs/guest_nav.php");
                } else {
                    include("navs/logged_nav.php");
                }
            ?>
        </header>
