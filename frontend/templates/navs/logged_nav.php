<nav>
    <a href="/my-dates">Моите дати</a>
    <?php
        echo "<a href=\"/user/" . SessionManager::get_logged_user_id() . "\">Моят акаунт</a>";
    ?>
    <a href="/logout">Излез</a>
</nav>