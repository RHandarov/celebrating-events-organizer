<main id="user-details">
    <header>
        <h2>Детайли за потребител <?php echo $user->get_username(); ?></h2>
        <?php
            if ($user->get_id() === SessionManager::get_logged_user_id()) {
                $bottom_text = "Смяна на парола";
                $action = "#";
            } else if ($does_logged_user_follows === true) {
                $bottom_text = "Отследвай";
                $action = "/user/unfollow";
            } else {
                $bottom_text = "Последвай";
                $action = "/user/follow";
            }
        ?>
        <form method="POST" action="<?php echo $action; ?>">
            <input type="hidden" name="user_id" value="<?php echo $user->get_id(); ?>">
            <button type="submit"><?php echo $bottom_text; ?></button>
        </form>
    </header>
    <p>Имейл: <?php echo $user->get_email(); ?></p>
    <h3>Дати</h3>
    <table class="table">
        <tr>
            <th scope="col">Дата</th>
            <th scope="col">Повод</th>
        </tr>
        <?php
            foreach ($user_dates as $date) {
                echo "<tr>";
                echo "<td>" . $date->get_date() . "</td>";
                echo "<td>" . $date->get_title() . "</td>";
                echo "</tr>";
            }
        ?>
    </table>
    <h3>Последователи</h3>
    <?php
        foreach ($user_followers as $follower) {
            echo "<p><a href=\"/user/" . $follower->get_id() . "\">";
            echo $follower->get_username();
            echo "</a></p>";
        }
    ?>
</main>