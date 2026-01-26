<nav>
    <a href="/">Начало</a>
    <a href="/my-dates">Моите дати</a>
    <a href="/event/create">Организирай събитие</a>
    <a href="/users/find">Намери приятели</a>
    <?php
        echo "<a href=\"/user/" . SessionManager::get_logged_user_id() . "\">Моят акаунт</a>";
    ?>
    <a href="/logout">Излез</a>
</nav>
