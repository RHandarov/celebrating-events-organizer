<main id="user-details">
    <header>
        <h2>Детайли за потребител <?php echo $user->get_username(); ?></h2>
        <?php
            $method = "POST";

            if ($user->get_id() === SessionManager::get_logged_user_id()) {
                $bottom_text = "Смяна на парола";
                $action = "/user/change-password";
                $method = "GET";
            } else if ($does_logged_user_follows === true) {
                $bottom_text = "Отследвай";
                $action = "/user/unfollow";
            } else {
                $bottom_text = "Последвай";
                $action = "/user/follow";
            }
        ?>
        <form method="<?php echo $method; ?>" action="<?php echo $action; ?>">
            <?php
                if ($user->get_id() !== SessionManager::get_logged_user_id()) {
                    echo "<input type=\"hidden\" name=\"followed_id\" value=\"" . $user->get_id() . "\">";
                }
            ?>
            <button type="submit"><?php echo $bottom_text; ?></button>
        </form>
        <?php
            if ($user->get_id() === SessionManager::get_logged_user_id()) {
                echo "<form method=\"GET\" action=\"/user/change-full-name\">";
                echo "<button type=\"submit\">Промени пълното име</button>";
                echo "</form>";
            }
        ?>
    </header>
    <p>Имейл: <?php echo $user->get_email(); ?></p>
    <p>Пълно име: <?php echo $user->get_full_name(); ?></p>
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
    if (count($user_followers) > 0) {
        echo "<ul class='list-group'>";
        foreach ($user_followers as $follower) {
            echo "<li class='list-group-item'>";
            echo "<a href=\"/user/" . $follower->get_id() . "\">";
            echo htmlspecialchars($follower->get_username()); 
            echo "</a>";
            echo "</li>";
        }
        echo "</ul>";
    } else {
        echo "<p class='text-muted'>Този потребител все още няма последователи.</p>";
    }
    ?>
</main>
