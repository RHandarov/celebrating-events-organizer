<nav>
    <a href="/my-dates">Моите дати</a>
    <a href="/event/create">Организирай събитие</a>
    <?php
        echo "<a href=\"/user/" . SessionManager::get_logged_user_id() . "\">Моят акаунт</a>";
    ?>
    <a href="/logout">Излез</a>
</nav>