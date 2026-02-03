<nav>
    <a href="<?php echo Router::get_url(); ?>">Начало</a>
    <a href="<?php echo Router::get_url(); ?>?action=my-dates">Моите дати</a>
    <a href="<?php echo Router::get_url(); ?>?action=event&a=create">Организирай събитие</a>
    <a href="<?php echo Router::get_url(); ?>?action=users&a=find">Намери приятели</a>
    <?php
        echo "<a href=\"" . Router::get_url() . "?action=user&id=" . SessionManager::get_logged_user_id() ."\">Моят акаунт</a>";
    ?>
    <a href="<?php echo Router::get_url(); ?>?action=logout">Излез</a>
</nav>
